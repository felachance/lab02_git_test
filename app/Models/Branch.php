<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $table = 'branches';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['name','civic_no','road','city'];
}
