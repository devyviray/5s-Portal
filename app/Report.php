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
        'company_id', 'location_id', 'operation_line_id','category_id', 'area_id', 'process_owner_id', 'inspector_id', 
        'date_of_inspection','start_time_of_inspection', 'end_time_of_inspection','ratings','status', 'reporting_month', 
        'reporting_year', 'accompanied_by','department_head_id', 'cluster_head_id', 'group_president_id'
    ];

    // Relation
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
        return $this->belongsTo(User::class, 'inspector_id');
    }

    public function processOwner(){
        return $this->belongsTo(User::class, 'process_owner_id');
    }

    public function reportDetail(){
        return $this->hasMany(ReportDetail::class);
    }

    public function topManagements() {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
