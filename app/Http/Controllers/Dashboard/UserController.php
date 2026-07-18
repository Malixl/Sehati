<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Exclude super admins and owners from the user management list for non-owners
        $query = User::with(['healthPost', 'district']);
        
        if (!$user->isOwner()) {
            $query->whereNotIn('role', ['super_admin', 'owner']);
        } else {
            $query->where('id', '!=', $user->id); // Owner sees everyone except themselves
        }
        
        if ($user->isAdminPosyandu()) {
            $query->where('health_post_id', $user->health_post_id);
        } elseif ($request->filled('posyandu_id')) {
            // Super admins can view users of a specific posyandu
            $query->where('health_post_id', $request->posyandu_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();
        
        $posyandus = \App\Models\HealthPost::orderBy('name')->get();
        $posyandu = null;
        if ($request->filled('posyandu_id')) {
            $posyandu = \App\Models\HealthPost::find($request->posyandu_id);
        }

        return view('pages.dashboard.pengguna.index', compact('users', 'posyandus', 'posyandu'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && !$user->isAdminPosyandu()) abort(403);

        $allowedRoles = ['admin_posyandu'];
        if ($user->isOwner()) {
            $allowedRoles[] = 'super_admin';
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:' . implode(',', $allowedRoles),
        ];

        if ($user->isSuperAdmin() && $request->role !== 'super_admin') {
            $rules['health_post_id'] = 'required|exists:health_posts,id';
        }

        $request->validate($rules);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'health_post_id' => ($user->isSuperAdmin() && $request->role !== 'super_admin') ? $request->health_post_id : null,
        ]);

        return back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $authUser = Auth::user();
        if (!$authUser->isSuperAdmin() && !$authUser->isAdminPosyandu()) abort(403);

        $user = User::findOrFail($id);

        if ($authUser->isAdminPosyandu() && $user->health_post_id !== $authUser->health_post_id) {
            abort(403);
        }

        $allowedRoles = ['admin_posyandu'];
        if ($authUser->isOwner()) {
            $allowedRoles[] = 'super_admin';
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'role' => 'required|in:' . implode(',', $allowedRoles),
        ];

        if ($authUser->isSuperAdmin() && $request->role !== 'super_admin') {
            $rules['health_post_id'] = 'required|exists:health_posts,id';
        }

        $request->validate($rules);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'health_post_id' => ($authUser->isSuperAdmin() && $request->role !== 'super_admin') ? $request->health_post_id : null,
        ]);
        
        if ($request->filled('password')) {
            $user->update([
                'password' => bcrypt($request->password),
            ]);
        }

        return back()->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $authUser = Auth::user();
        if (!$authUser->isSuperAdmin() && !$authUser->isAdminPosyandu()) abort(403);

        $user = User::findOrFail($id);
        
        if ($authUser->isAdminPosyandu() && $user->health_post_id !== $authUser->health_post_id) {
            abort(403);
        }

        if ($user->id === $authUser->id) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $user = Auth::user();

        $query = User::with(['healthPost', 'district']);
        
        if (!$user->isOwner()) {
            $query->whereNotIn('role', ['super_admin', 'owner']);
        } else {
            $query->where('id', '!=', $user->id);
        }
        
        if ($user->isAdminPosyandu()) {
            $query->where('health_post_id', $user->health_post_id);
        } elseif ($request->filled('posyandu_id')) {
            $query->where('health_post_id', $request->posyandu_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('name', 'asc')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=Data_Pengguna.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($users) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['No', 'Nama Pengguna', 'Email', 'Role', 'Posyandu'], ';');

            foreach ($users as $index => $u) {
                fputcsv($file, [
                    $index + 1,
                    $u->name,
                    $u->email,
                    $u->role == 'admin_posyandu' ? 'Admin Posyandu' : 'Kader',
                    $u->healthPost->name ?? '-',
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
