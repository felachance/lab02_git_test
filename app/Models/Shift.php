<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Shift extends Model
{
    protected $table = 'shifts';
    public $timestamps = true;
    protected $fillable = [
        'id_branch',
        'date',
        'start_time',
        'end_time',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'id_branch');
    }

    public function mostRecentAssignment()
    {
        return $this->hasOne(Assignment::class, "id_shift")->orderBy('assigned_at', 'desc');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'id_shift');
    }
}
