<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
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

        // Get mail settings
        $mailSettings = MailSetting::getActive();

        return view('admin.accounts.index', compact(
            'users',
            'totalAdmins',
            'activeAdmins',
            'inactiveAdmins',
            'todayLogins',
            'mailSettings'
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

    public function storeMailSettings(Request $request)
    {
        try {
            $request->validate([
                'mail_mailer' => 'required|string',
                'mail_host' => 'required|string',
                'mail_port' => 'required|integer',
                'mail_username' => 'required|email',
                'mail_password' => 'required|string',
                'mail_encryption' => 'nullable|string',
                'mail_from_address' => 'required|email',
                'mail_from_name' => 'required|string',
            ]);

            // Update .env file
            $this->updateEnvFile([
                'MAIL_MAILER' => $request->mail_mailer,
                'MAIL_HOST' => $request->mail_host,
                'MAIL_PORT' => $request->mail_port,
                'MAIL_USERNAME' => $request->mail_username,
                'MAIL_PASSWORD' => $request->mail_password,
                'MAIL_ENCRYPTION' => $request->mail_encryption ?: '',
                'MAIL_FROM_ADDRESS' => '"' . $request->mail_from_address . '"',
                'MAIL_FROM_NAME' => '"' . $request->mail_from_name . '"',
            ]);

            // Create or update mail settings in database
            $mailSettings = MailSetting::updateOrCreate(
                ['is_active' => true],
                $request->only([
                    'mail_mailer',
                    'mail_host',
                    'mail_port',
                    'mail_username',
                    'mail_password',
                    'mail_encryption',
                    'mail_from_address',
                    'mail_from_name'
                ]) + ['is_active' => true]
            );

            // Apply configuration
            $mailSettings->applyConfig();

            return redirect()->route('admin.accounts.index', ['tab' => 'mail-settings'])
                ->with('success', 'Konfigurasi email SMTP berhasil disimpan ke database dan file .env');

        } catch (\Exception $e) {
            Log::error('Mail settings save failed', ['error' => $e->getMessage()]);

            return redirect()->route('admin.accounts.index', ['tab' => 'mail-settings'])
                ->with('error', 'Gagal menyimpan konfigurasi: ' . $e->getMessage());
        }
    }

    /**
     * Update .env file with new values
     */
    private function updateEnvFile(array $data)
    {
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);

        foreach ($data as $key => $value) {
            // Handle empty values
            if (empty($value) && $value !== '0') {
                $value = '';
            }

            // Escape special characters for regex
            $escapedKey = preg_quote($key, '/');

            // Pattern to match the key=value line
            $pattern = "/^{$escapedKey}=.*$/m";

            // New line to replace
            $newLine = "{$key}={$value}";

            if (preg_match($pattern, $envContent)) {
                // Key exists, replace it
                $envContent = preg_replace($pattern, $newLine, $envContent);
            } else {
                // Key doesn't exist, add it at the end
                $envContent .= "\n{$newLine}";
            }
        }

        // Write back to .env file
        file_put_contents($envFile, $envContent);

        Log::info('Updated .env file with mail settings');
    }

    public function testMailSettings(Request $request)
    {
        try {
            $request->validate([
                'mail_host' => 'required|string',
                'mail_port' => 'required|integer',
                'mail_username' => 'required|email',
                'mail_password' => 'required|string',
                'mail_encryption' => 'nullable|string',
                'mail_from_address' => 'required|email',
                'mail_from_name' => 'required|string',
            ]);

            // Temporarily set mail configuration
            Config::set([
                'mail.default' => $request->mail_mailer ?? 'smtp',
                'mail.mailers.smtp.host' => $request->mail_host,
                'mail.mailers.smtp.port' => $request->mail_port,
                'mail.mailers.smtp.username' => $request->mail_username,
                'mail.mailers.smtp.password' => $request->mail_password,
                'mail.mailers.smtp.encryption' => $request->mail_encryption,
                'mail.from.address' => $request->mail_from_address,
                'mail.from.name' => $request->mail_from_name,
            ]);

            // Send test email
            Mail::raw('Ini adalah test email dari sistem Dinas Koperasi. Jika Anda menerima email ini, konfigurasi SMTP Anda sudah benar.', function ($message) use ($request) {
                $message->to($request->mail_username)
                        ->subject('Test Email - Konfigurasi SMTP Berhasil');
            });

            return response()->json([
                'success' => true,
                'message' => 'Test email berhasil dikirim! Periksa inbox Anda.'
            ]);

        } catch (\Exception $e) {
            Log::error('Mail test failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim test email: ' . $e->getMessage()
            ], 422);
        }
    }
}
