<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\{
    Report
};


class NotifiedProcessOwner extends Mailable
{
    use Queueable, SerializesModels;

    protected $report;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($report)
    {
        $this->report = Report::findOrFail($report);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $report = $this->report;
        
        return $this->view('mail.report-notified', compact('report'));
    }
}
