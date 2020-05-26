<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\{
    User,
    Area,
    Report
};

class ReturnedInspection extends Mailable
{
    use Queueable, SerializesModels;

    protected $processOwner;
    protected $area;
    protected $report;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($processOwner, $area, $report)
    {
        $this->processOwner = User::findOrFail($processOwner);
        $this->area = Area::findOrFail($area);
        $this->report = Report::findOrFail($report);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $processOwner = $this->processOwner;
        $area = $this->area;
        $report = $this->report;
        $pathToImage = public_path()."/img/better_5sPortal.png";

        return $this->view('mail.report-for-checking', compact('report', 'area', 'processOwner', 'pathToImage'));
    }
}
