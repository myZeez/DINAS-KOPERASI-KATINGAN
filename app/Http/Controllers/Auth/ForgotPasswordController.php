<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Messages\MailMessage;

class ForgotPasswordController extends Controller
{
    /**
     * Show forgot password form
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send password reset link
     */
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak ditemukan di sistem.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Send password reset link with better error handling
        $user = User::where('email', $request->email)->first();

        if ($user) {
            try {
                // Use Laravel's built-in password reset with custom response
                $status = Password::sendResetLink(
                    $request->only('email'),
                    function ($user, $token) {
                        $resetUrl = url('password/reset/' . $token . '?email=' . urlencode($user->email));

                        // Send email with retry mechanism
                        $maxRetries = 3;
                        $retryCount = 0;

                        while ($retryCount < $maxRetries) {
                            try {
                                \Illuminate\Support\Facades\Mail::send('emails.password-reset', [
                                    'actionUrl' => $resetUrl,
                                    'count' => config('auth.passwords.users.expire', 60)
                                ], function ($message) use ($user) {
                                    $message->to($user->email);
                                    $message->subject('Reset Password - Dinas Koperasi');
                                    $message->from(config('mail.from.address'), config('mail.from.name'));
                                });

                                \Log::info('Password reset email sent successfully to: ' . $user->email);
                                break; // Success, exit retry loop

                            } catch (\Exception $e) {
                                $retryCount++;
                                \Log::warning("Email send attempt {$retryCount} failed: " . $e->getMessage());

                                if ($retryCount < $maxRetries) {
                                    sleep(2); // Wait 2 seconds before retry
                                } else {
                                    throw $e; // Re-throw after all retries failed
                                }
                            }
                        }
                    }
                );

                if ($status === Password::RESET_LINK_SENT) {
                    return back()->with('status', 'Link reset password telah dikirim ke email Anda.');
                }

                return back()->withErrors(['email' => 'Gagal mengirim email reset password.']);

            } catch (\Exception $e) {
                \Log::error('Password reset failed: ' . $e->getMessage());

                // Check for specific Gmail errors
                if (strpos($e->getMessage(), '421') !== false) {
                    return back()->withErrors(['email' => 'Server email sedang sibuk. Silakan coba lagi dalam beberapa menit.']);
                } elseif (strpos($e->getMessage(), 'authentication') !== false) {
                    return back()->withErrors(['email' => 'Konfigurasi email bermasalah. Hubungi administrator.']);
                } else {
                    return back()->withErrors(['email' => 'Gagal mengirim email: ' . $e->getMessage()]);
                }
            }
        }

        return back()->withErrors(['email' => 'Email tidak ditemukan di sistem.']);
    }

    /**
     * Show reset password form
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with([
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Reset password
     */
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'token.required' => 'Token reset password diperlukan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Password berhasil direset. Silakan login dengan password baru.');
        }

        return back()->withErrors(['email' => 'Token reset password tidak valid atau sudah expired.']);
    }
}
