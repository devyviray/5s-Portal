<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\{
    CompanyLocation,
    CompanyLocationOperationLine
};

class CompanyOperationLineRule implements Rule
{
    protected $location;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($location)
    {
        $this->location = $location;
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
        $companyLocation = CompanyLocation::where('company_id', $value)->where('location_id', $this->location)->first();
        $companyLocationOperationLine = CompanyLocationOperationLine::where('company_location_id', $companyLocation->id)->get();
        if(!$companyLocationOperationLine->isEmpty()){
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
        return 'Operation Line for this company already exist';
    }
}
