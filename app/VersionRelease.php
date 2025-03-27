<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class VersionRelease extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'version',
        'release_date'
    ];

    public function releaseNotes() {
        return $this->hasMany(VersionReleaseNote::class);
    }

    public function feedbacks() {
        return $this->hasMany(VersionReleaseFeedback::class);
    }
}
