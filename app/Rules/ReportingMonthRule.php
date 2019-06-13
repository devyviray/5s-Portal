<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;
use App\{
    Report
};

class ReportingMonthRule implements Rule
{
    protected $company;
    protected $location;
    protected $operationLine;
    protected $category;
    protected $area;
    protected $reportingMonth;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($company, $location, $operationLine = null, $category, $area, $reportingMonth)
    {
        $this->company = $company;
        $this->location = $location;
        $this->operationLine = $operationLine;
        $this->category = $category;
        $this->area = $area;
        $this->reportingMonth = $reportingMonth;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $date = Carbon::parse($this->reportingMonth);
        $processOwner = Report::where('company_id',   $this->company)
        ->where('location_id', $this->location)
        ->when($this->operationLine, function ($q) { 
            $q->where('operation_line_id', $this->operationLine);
        })
        ->where('process_owner_id', $value)
        ->where('reporting_month',  $date->isoFormat('M'))
        ->where('reporting_year',  $date->isoFormat('Y'))->first();
        
        if($processOwner){
            return false;
        }else{
            return true; 
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Report for this month already exist';
    }
}
