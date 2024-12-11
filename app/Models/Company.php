<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{

    use HasFactory;

    protected $table = 'companies';

    protected $fillable = [
        'name',
        'description',
    ];
    
    //
    public function employees() : HasMany
    {
        return $this->hasMany(Employee::class, 'company_id');
    }
    public $timestamps = true;
}
