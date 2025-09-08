<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReplacementRequestType extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public $timestamps = false;

    public function replacementRequests()
    {
        return $this->hasMany(ReplacementRequest::class);
    }

}
