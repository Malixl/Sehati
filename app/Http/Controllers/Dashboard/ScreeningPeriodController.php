<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ScreeningPeriod;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Support\Facades\Notification;

class ScreeningPeriodController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $periods = ScreeningPeriod::with('creator')->orderBy('start_date', 'desc')->paginate(10);
        return view('pages.dashboard.periode.index', compact('periods'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        ScreeningPeriod::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => false,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('dashboard.periode.index')->with('success', 'Periode skrining berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $period = ScreeningPeriod::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $period->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('dashboard.periode.index')->with('success', 'Periode skrining berhasil diperbarui.');
    }

    public function toggleActive($id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $period = ScreeningPeriod::findOrFail($id);
        
        if ($period->is_expired) {
            return redirect()->route('dashboard.periode.index')->with('error', 'Tidak dapat mengubah status periode yang sudah kedaluwarsa.');
        }
        
        // If we only want one active period at a time:
        if (!$period->is_active) {
            ScreeningPeriod::where('id', '!=', $id)->update(['is_active' => false]);
            $period->update(['is_active' => true]);
            $msg = 'Periode diaktifkan. Periode lain otomatis ditutup.';
            
            // Send notification to all admin_posyandu
            $admins = User::where('role', 'admin_posyandu')->get();
            Notification::send($admins, new SystemNotification(
                'Periode Skrining Aktif',
                'Periode ' . $period->name . ' telah diaktifkan.',
                route('dashboard.skrining.index')
            ));
        } else {
            $period->update(['is_active' => false]);
            $msg = 'Periode ditutup.';
        }

        return redirect()->route('dashboard.periode.index')->with('success', $msg);
    }

    public function destroy($id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $period = ScreeningPeriod::findOrFail($id);
        
        if ($period->screenings()->count() > 0) {
            return redirect()->route('dashboard.periode.index')->with('error', 'Periode tidak dapat dihapus karena sudah memiliki data skrining.');
        }

        $period->delete();

        return redirect()->route('dashboard.periode.index')->with('success', 'Periode skrining berhasil dihapus.');
    }
}
