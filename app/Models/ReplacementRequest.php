<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReplacementRequest extends Model
{
    protected $fillable = [
        'description',
        'id_replacement_request_type',
        'id_assignment',
    ];

    public $timestamps = true;

    public function replacementRequestType()
    {
        return $this->belongsTo(ReplacementRequestType::class, 'id_replacement_request_type');
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'id_assignment');
    }
}
