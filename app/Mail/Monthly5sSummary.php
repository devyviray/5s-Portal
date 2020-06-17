<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Monthly5sSummary extends Mailable
{
    use Queueable, SerializesModels;

    protected $report;
    protected $rank;
    protected $count_ranking;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($final,$rank,$count_ranking)
    {
        $this->report = $final;
        $this->rank = $rank;
        $this->count_ranking = $count_ranking;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $report = $this->report;
        $rank = $this->rank;
        $count_ranking = $this->count_ranking;
        return $this->view('mail.report-monthly-summary',compact('report','rank','count_ranking'));
    }
}
