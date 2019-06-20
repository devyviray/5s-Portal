<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomePage extends Model
{
    use SoftDeletes;

    protected $fillable = ['file_name', 'file_path', 'uploaded_by']; 
}
