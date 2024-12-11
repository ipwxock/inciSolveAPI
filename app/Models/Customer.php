<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Insurance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
//
    use hasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'auth_id',
        'phone_number',
        'address',
    ];

    function insurances() : HasMany
    {
        return $this->hasMany(Insurance::class);
    }

    function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'auth_id');
    }
    public $timestamps = true;
}
