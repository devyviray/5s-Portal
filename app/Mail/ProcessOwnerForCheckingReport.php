<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\{
    User,
    Report
};

class ProcessOwnerForCheckingReport extends Mailable
{
    use Queueable, SerializesModels;

    protected $processOwner;
    protected $report;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($processOwner, $report)
    {
        $this->inspector = User::findOrFail($processOwner);
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
        $processOwner = $this->processOwner;

        return $this->view('mail.report-for-checking', compact('report', 'processOwner'));
    }
}
