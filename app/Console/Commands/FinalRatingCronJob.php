<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\{
    Report
};

class FinalRatingCronJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rating:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Report rating has been set to final to those reports exceed 72 hours';

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
        $reports = Report::with('reportDetail')->where('date_submitted', '<=', Carbon::now()->subDays(3)->toDateTimeString())
           ->whereNull('ratings')
        //    ->whereNull('is_draft')
           ->get();
           
            foreach($reports as $report){
                $denominator = 0;
                $total_points = 0;
                foreach($report->reportDetail as $r){
                    if($r->points != 'N/A'){
                        $total_points = $total_points + $r->points;
                        $denominator = $denominator + 1;
                    }
                }
                $data = [
                    'ratings' => $total_points / ($denominator * 2) * 100,
                    'status' => 4
                ];
                $report->update($data);
            }
    }
}
