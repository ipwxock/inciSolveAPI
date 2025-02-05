<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable; // Cambia Model a Authenticatable
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable // Extiende Authenticatable para cumplir con el contrato de autenticación
{
    use HasApiTokens, HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'dni',
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
    ];

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'auth_id');
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class, 'auth_id');
    }

    public $timestamps = true;
}
