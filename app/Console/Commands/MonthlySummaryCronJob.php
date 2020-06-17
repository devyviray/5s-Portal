<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Auth;
use DB;
use Mail;
use Carbon\Carbon;
use Config;
use Exception;
use App\{
    User,
    Report,
    ErrorLog
};
use App\Mail\{
    Monthly5sSummary
};

class MonthlySummaryCronJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthly:summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Montly 5s summary has been sent to Department Head, BU head and Group President';

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
        DB::beginTransaction();
        try {
            $final_rating = [];
            $now = Carbon::now();
            $month = $now->month == 1 ? 12 : $now->month -1;
            $year = $now->month == 1 ? $now->year -1 : $now->year;

            $final_array = [];
            $reports = Report::with('company', 'location', 'operationLine', 'category', 'area', 'inspector', 'processOwner', 'reportDetail')
            ->where('reporting_month',$month)
            ->where('reporting_year',$year)
            ->where('status',Config::get('constants.status.final'))
            ->get()
            ->groupBy(['company_id','location_id'])
            ->toArray();

            if($reports){
                foreach($reports as $report){
                    foreach($report as $r){
                        array_push($final_array,$r);
                    }
                }

                if($final_array){
                    foreach($final_array as $final){
                        $total_areas = 0;
                        $total_ratings = 0;
                        foreach($final as $f){
                            $total_areas = $total_areas + 1;
                            $total_ratings = floatval($total_ratings) + floatval($f['ratings']);
                        }
                        $final_rating[] = [
                            'company' => $final[0]['company']['name'] .' - '.  $final[0]['location']['name'],
                            'rating' => round($total_ratings / $total_areas,2),
                            'company_id' => $final[0]['company']['id'],
                            'location_id' => $final[0]['location']['id'],

                        ];
                    }
                }
                // Sort rating from highest to lowest
                usort($final_rating, function ($item1, $item2) {
                    return $item2['rating'] <=> $item1['rating'];
                });
                $rank = 0;
                $count_ranking = count($final_rating);
                foreach($final_rating as $final){
                    $rank = $rank + 1;
                    $bu_head = User::whereHas('companies', function ($q) use ($final){
                        $q->where('company_id',$final['company_id']);
                    })->whereHas('roles', function ($q){
                        $q->where('role_id', Config::get('constants.roles.bu_head.id'));
                    })->where('company_location', $final['location_id'])
                    ->get();
                    
                    $president = User::whereHas('roles', function ($q){
                        $q->where('role_id', Config::get('constants.roles.group_president.id'));
                    })->get();

                    $notify = $bu_head->merge($president);
                    Mail::to($notify)->send(new Monthly5sSummary($final,$rank,$count_ranking ));
                }
            }

        } catch (Exception $e) {
            $error = ErrorLog::create(['message' => $e->getMessage(),'model' => 'App\Report','user_id' => 1]);
            DB::commit();
        }
    }
}
