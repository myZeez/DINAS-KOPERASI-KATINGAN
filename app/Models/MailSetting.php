<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class MailSetting extends Model
{
    protected $fillable = [
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        'is_active'
    ];

    protected $casts = [
        'mail_port' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Get the active mail setting
     */
    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Apply mail configuration to Laravel config
     */
    public function applyConfig()
    {
        if ($this->is_active) {
            Config::set([
                'mail.default' => $this->mail_mailer,
                'mail.mailers.smtp.host' => $this->mail_host,
                'mail.mailers.smtp.port' => $this->mail_port,
                'mail.mailers.smtp.username' => $this->mail_username,
                'mail.mailers.smtp.password' => $this->mail_password,
                'mail.mailers.smtp.encryption' => $this->mail_encryption,
                'mail.from.address' => $this->mail_from_address,
                'mail.from.name' => $this->mail_from_name,
            ]);
        }
    }

    /**
     * Set this setting as active and deactivate others
     */
    public function setAsActive()
    {
        // Deactivate all other settings
        static::where('id', '!=', $this->id)->update(['is_active' => false]);

        // Activate this setting
        $this->update(['is_active' => true]);

        // Apply configuration
        $this->applyConfig();
    }
}
