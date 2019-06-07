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

class InspectorValidateReport extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($inspector, $report)
    {
        $this->inspector = User::findOrFail($inspector);
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
        $inspector = $this->inspector;

        return $this->view('mail.report-validate', compact('report', 'inspector'));
    }
}
