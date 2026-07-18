<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HealthPost;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;

class PosyanduController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Only super admin can see all, admin_posyandu only sees theirs
        $query = HealthPost::filterByRole($user)
            ->with('village.district')
            ->withCount('users'); // users as kader

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhereHas('village', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $posyanduList = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();

        $villages = \App\Models\Village::orderBy('name')->get();

        return view('pages.dashboard.posyandu.index', compact('posyanduList', 'villages'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'village_id' => 'nullable|exists:villages,id',
        ]);

        DB::transaction(function () use ($request) {
            HealthPost::create([
                'name' => $request->name,
                'village_id' => $request->village_id,
                'is_active' => true,
            ]);

            // Notify owner
            $owners = User::where('role', 'owner')->get();
            if ($owners->count() > 0) {
                Notification::send($owners, new SystemNotification(
                    'Fasilitas Kesehatan Baru',
                    'Fasilitas ' . $request->name . ' telah ditambahkan.',
                    route('dashboard.posyandu.index')
                ));
            }
        }); 
        
        return back()->with('success', 'Posyandu berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->isSuperAdmin()) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'village_id' => 'nullable|exists:villages,id',
            'is_active' => 'boolean',
        ]);

        $posyandu = HealthPost::findOrFail($id);
        $posyandu->update([
            'name' => $request->name,
            'village_id' => $request->village_id,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Posyandu berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if (!Auth::user()->isSuperAdmin()) abort(403);

        $posyandu = HealthPost::findOrFail($id);
        
        if ($posyandu->users()->count() > 0 || $posyandu->respondents()->count() > 0) {
            return back()->with('error', 'Gagal menghapus! Posyandu ini masih memiliki kader atau responden yang terdaftar.');
        }

        $posyandu->delete();
        return back()->with('success', 'Posyandu berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        $query = HealthPost::filterByRole($user)
            ->with('village.district')
            ->withCount('users');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhereHas('village', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $posyanduList = $query->orderBy('name', 'asc')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=Data_Posyandu.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($posyanduList) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['No', 'Nama Posyandu', 'Desa', 'Kecamatan', 'Total Operator', 'Status'], ';');

            foreach ($posyanduList as $index => $posyandu) {
                fputcsv($file, [
                    $index + 1,
                    $posyandu->name,
                    $posyandu->village->name ?? '-',
                    $posyandu->village->district->name ?? '-',
                    $posyandu->users_count,
                    $posyandu->is_active ? 'Aktif' : 'Nonaktif',
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
