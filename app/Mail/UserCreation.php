<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreation extends Mailable
{
    use Queueable, SerializesModels;

    protected $role;
    protected $default_password;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($role, $default_password)
    {
        $this->$role = $role;
        $this->default_password = $default_password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $role = $this->role;
        $default_password = $this->default_password;
        $pathToImage = public_path()."/img/better_5sPortal.png";
        return $this->view('mail.user-creation', compact('role','default_password','pathToImage'));
    }
}
