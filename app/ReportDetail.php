<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportDetail extends Model
{
    use SoftDeletes;
      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'checklist_id', 'checklist_batch', 'report_id','name', 'points', 'previous_rating', 'recurrence_number', 'criticality'
    ];

    // Relation
    public function uploadedFiles(){
        return $this->hasMany(UploadedFile::class);
    }
}
