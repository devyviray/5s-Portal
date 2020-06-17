<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;
use Carbon\Carbon;
use App\{
    User,
    Report
};
use App\Mail\{
    NotifiedProcessOwner,
};

class NotifiedProcessOwnerCronJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notified:processowner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email has been sent to process owner with reports close to two days older';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $reports = Report::where('date_submitted', '>=',  Carbon::parse('+48 hours'))
            ->whereNull('ratings')
            ->whereNull('is_draft')
            ->get();

        foreach($reports as $report){
            // Send email to process owner
            Mail::to(User::findOrFail($report->process_owner_id))->send(new NotifiedProcessOwner($report->inspector_id,$report->area_id,$report->id));
        }
    }
}
