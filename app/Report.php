<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'location_id', 'operation_line_id','category_id', 'area_id', 'process_owner', 'inspector', 
        'date_of_inspection','time_of_inspection', 'checklist_id','checklist_batch','name','points','status'
    ];

    // Relation
    public function uploadedFiles(){
        return $this->hasMany(UploadedFile::class);
    }

}
