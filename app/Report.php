<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes;
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

    public function company(){
        return $this->belongsTo(Company::class);
    }

    public function location(){
        return $this->belongsTo(Location::class);
    }

    public function operationLine(){
        return $this->belongsTo(OperationLine::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function area(){
        return $this->belongsTo(Area::class);
    }

    public function inspector(){
        return $this->belongsTo(User::class, 'inspector');
    }

}
