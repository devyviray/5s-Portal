<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UploadedFile extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'report_detail_id', 'checklist_id', 'uploader_id', 
    'file_name', 'file_path','model', 'comment', 'status', 'description'];
}
