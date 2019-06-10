<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\{
    CompanyCategory
};

class CompanyAreaRule implements Rule
{
    protected $company;
    protected $location;
    protected $operationLine;
    protected $category;
    protected $id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($company, $location, $operationLine, $category, $id = null)
    {
        $this->company = $company;
        $this->location = $location;
        $this->operationLine = $operationLine;
        $this->category = $category;
        $this->id = $id;

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
        $companyCategory = CompanyCategory::where('company_id', $this->company)
        ->where('location_id', $this->location)
        ->when($this->operationLine, function($q) { 
            $q->where('operation_line_id', $this->operationLine);
        })
        ->where('category_id', $this->category)
        ->when($this->id, function ($q) {
            $q->where('id','!=', $this->id);
        })->first();
        
        if($companyCategory){
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
        return 'Area for the company already exist';
    }
}
