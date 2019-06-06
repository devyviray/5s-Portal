<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyCategory extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 
        'company_id',
        'location_id',
        'operation_line_id',
        'category_id'
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

    public function areas(){
        return $this->belongsToMany(Area::class);
    }
}
