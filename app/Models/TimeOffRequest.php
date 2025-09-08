<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\TimeOffRequestType;
use App\Models\User;

class TimeOffRequest extends Model
{
    public function type(): BelongsTo {
        return $this->belongsTo(TimeOffRequestType::class, 'type_id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
}
