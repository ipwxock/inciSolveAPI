<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Issue;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Insurance extends Model
{

    use HasFactory;

    protected $table = 'insurances';
    protected $fillable = [
        'subject_type',
        'description',
        'customer_id',
        'employee_id',
    ];

    function customer() : BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    function issues() : HasMany
    {
        return $this->hasMany(Issue::class);
    }

    function employee() : BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public $timestamps = true;
}
