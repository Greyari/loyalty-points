<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'module',
        'action',
        'user_id',
        'target_id',
        'changes'
    ];

    protected $casts = [
        'changes' => 'array'
    ];
}
