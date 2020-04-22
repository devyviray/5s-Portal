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

class ReportCreated extends Mailable
{
    use Queueable, SerializesModels;

    protected $inspector;
    protected $area;
    protected $report;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($inspector, $area, $report)
    { 
        $this->inspector = User::findOrFail($inspector);
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
        $inspector = $this->inspector;
        $area = $this->area;
        $report = $this->report;
        $pathToImage = public_path()."/img/better_5sPortal.png";

        return $this->view('mail.report-created', compact('inspector', 'area' , 'report', 'pathToImage'));
    }
}
