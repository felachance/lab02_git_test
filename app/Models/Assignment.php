<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Assignment extends Pivot
{
    protected $table = 'assignment';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_shift',
        'status',
    ];

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'id_shift');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function replacementRequests()
    {
        return $this->hasMany(ReplacementRequest::class, 'id_assignment');
    }
}
