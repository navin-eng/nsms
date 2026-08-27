<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProviderTwoFactorMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
    public $providerName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($code, $providerName)
    {
        $this->code = $code;
        $this->providerName = $providerName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('SaaS Provider God Mode - Your Login Code')
                    ->html("
                        <div style='font-family: sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;'>
                            <h3 style='color: #005f1a;'>NSMS Cloud — God Mode Authentication</h3>
                            <p>Hi <strong>{$this->providerName}</strong>,</p>
                            <p>Your Provider account is attempting to log in. Please use the following one-time password (OTP) to complete the authentication process.</p>
                            <div style='background: #f8fafc; padding: 20px; border-radius: 6px; margin: 25px 0; border: 1px solid #cbd5e1; text-align: center;'>
                                <p style='margin: 0; font-size: 14px; color: #64748b;'>YOUR SECURE CODE</p>
                                <p style='margin: 10px 0 0 0; font-size: 32px; font-weight: bold; color: #10b981; letter-spacing: 5px;'>{$this->code}</p>
                            </div>
                            <p style='color: #64748b; font-size: 13px;'>This code is valid for the next 10 minutes. If you did not attempt to log in, please ignore this email and verify your account security.</p>
                        </div>
                    ");
    }
}
