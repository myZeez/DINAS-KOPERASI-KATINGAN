<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        $totalAdmins = User::count();
        $activeAdmins = User::where('is_active', true)->count();
        $inactiveAdmins = User::where('is_active', false)->count();
        $todayLogins = User::whereDate('last_login_at', today())->count();

        return view('admin.accounts.index', compact(
            'users',
            'totalAdmins',
            'activeAdmins',
            'inactiveAdmins',
            'todayLogins'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:super_admin,admin,editor',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true
        ];

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $userData['avatar'] = $avatarPath;
        }

        User::create($userData);

        return redirect()->route('admin.accounts.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function update(Request $request, User $user)
    {
        try {
            Log::info('User update started', [
                'user_id' => $user->id,
                'request_data' => $request->except(['password', '_token'])
            ]);

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
                'role' => 'required|in:super_admin,admin,editor',
                'password' => 'nullable|string|min:8',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            if ($request->hasFile('avatar')) {
                // Delete old avatar
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }

                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $userData['avatar'] = $avatarPath;
            }

            $user->update($userData);

            Log::info('User updated successfully', ['user_id' => $user->id]);

            return redirect()->route('admin.accounts.index')
                ->with('success', 'User berhasil diupdate');
        } catch (\Exception $e) {
            Log::error('User update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function toggleStatus(User $user)
    {
        try {
            Log::info('Toggle status request received', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'current_status' => $user->is_active
            ]);

            $newStatus = !$user->is_active;
            $user->update(['is_active' => $newStatus]);

            $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

            Log::info('User status toggled successfully', [
                'user_id' => $user->id,
                'new_status' => $user->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => "User berhasil {$status}",
                'status' => $user->is_active
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling user status', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status'
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        // Add debugging
        Log::info('Delete user request received', ['user_id' => $user->id, 'user_name' => $user->name]);

        // Prevent deletion of super admin if only one exists
        if ($user->role === 'super_admin') {
            $superAdminCount = User::where('role', 'super_admin')->count();
            if ($superAdminCount <= 1) {
                Log::warning('Attempted to delete last super admin', ['user_id' => $user->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus super admin terakhir'
                ], 400);
            }
        }

        // Delete avatar
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
            Log::info('Avatar deleted', ['avatar_path' => $user->avatar]);
        }

        $userName = $user->name;
        $user->delete();

        Log::info('User deleted successfully', ['user_name' => $userName]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus'
        ]);
    }
}
