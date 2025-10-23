<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use App\Models\MailSetting;
use Illuminate\Support\Facades\Schema;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            // Check if database tables exist (to avoid errors during migration)
            if (Schema::hasTable('mail_settings')) {
                $mailSettings = MailSetting::getActive();

                if ($mailSettings) {
                    // Apply mail configuration from database
                    Config::set([
                        'mail.default' => $mailSettings->mail_mailer,
                        'mail.mailers.smtp.host' => $mailSettings->mail_host,
                        'mail.mailers.smtp.port' => $mailSettings->mail_port,
                        'mail.mailers.smtp.username' => $mailSettings->mail_username,
                        'mail.mailers.smtp.password' => $mailSettings->mail_password,
                        'mail.mailers.smtp.encryption' => $mailSettings->mail_encryption,
                        'mail.from.address' => $mailSettings->mail_from_address,
                        'mail.from.name' => $mailSettings->mail_from_name,
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Silently fail if database is not available or tables don't exist
            // This prevents errors during migrations or fresh installations
        }
    }
}
