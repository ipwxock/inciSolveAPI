<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Insurance;

class Issue extends Model
{
    use HasFactory;

    protected $table = 'issues';            
    protected $fillable = ['insurance_id', 'subject', 'status'];

    public function insurance()
    {
        return $this->belongsTo(Insurance::class,'insurance_id');
    }

    public $timestamps = true;
}
