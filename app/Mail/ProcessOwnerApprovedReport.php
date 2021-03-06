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

class ProcessOwnerApprovedReport extends Mailable
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
        $this->processOwner = User::findOrFail($processOwner);
        $this->report = Report::with('company', 'location','area','inspector')->where('id',$report)->first();
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
        $pathToImage = public_path()."/img/better_5sPortal.png";

        return $this->view('mail.report-approved', compact('report', 'processOwner', 'pathToImage'));
    }
}
