<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperationLine extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['name'];
}
