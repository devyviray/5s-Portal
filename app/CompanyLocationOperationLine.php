<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyLocationOperationLine extends Model
{
    protected $table = 'company_location_operation_line';

    protected $fillable = ['company_location_id', 'operation_line_id'];
    
    public function companyLocation(){
        return $this->belongsTo(CompanyLocation::class);
    }

    public function operationLine(){
        return $this->belongsTo(OperationLine::class);
    }

}
