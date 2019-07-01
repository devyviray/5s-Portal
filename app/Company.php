<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'name'];

    // Relation
    public function locations() {
        return $this->belongsToMany(Location::class)->withTimestamps();
    }

    public function companySpecificLocation($locationId){
        return $this->belongsToMany(Location::class)->where('location_id', '=', $locationId);
    }
}
