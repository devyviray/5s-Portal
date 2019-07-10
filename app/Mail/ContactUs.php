<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\{
    User
};

class ContactUs extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $date;
    protected $subjects;
    protected $messages;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userId, $date, $subjects, $messages)
    {
        $this->user = User::findOrFail($userId);
        $this->date = $date;
        $this->subjects = $subjects;
        $this->messages = $messages;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subjects)->view('mail.contact-us')
                ->with([
                    'user' => $this->user,
                    'date' => $this->date,
                    'subjects' => $this->subjects,
                    'messages' => $this->messages,
                ]);
    }
}
