<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use HasFactory;

    protected $table = 'users';
    protected $fillable = [
        'dni',
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
    ];

    public function customers() : HasMany
    {
        return $this->hasMany(Customer::class, 'auth_id');
    }
    
    public function employees() : HasMany
    {
        return $this->hasMany(Employee::class, 'auth_id');
    }

    public $timestamps = true;
}
