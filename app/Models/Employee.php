<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $fillable = [
        'auth_id',
        'phone_number',
        'address'
    ];


    public function companie() : BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'auth_id');
    }

    public function insurances() : HasMany
    {
        return $this->hasMany(Insurance::class);
    }

    public $timestamps = true;
}
