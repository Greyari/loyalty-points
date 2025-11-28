<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
