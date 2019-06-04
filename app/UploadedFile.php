<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UploadedFile extends Model
{
    protected $fillable = ['user_id', 'report_detail_id', 'checklist_id', 'uploader_id', 
    'file_name', 'file_path','model', 'comment'];
}
