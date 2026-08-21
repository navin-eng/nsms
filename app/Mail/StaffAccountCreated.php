<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StaffAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $staffName;
    public $emailId;
    public $plainPassword;
    public $loginUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($staffName, $emailId, $plainPassword)
    {
        $this->staffName = $staffName;
        $this->emailId = $emailId;
        $this->plainPassword = $plainPassword;
        $this->loginUrl = route('admin.login');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Staff User ID & Password')
            ->view('emails.staff-account');
    }
}
