<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'status',
        'note',
        'return_date'
    ];

    public function student() {
        return $this->belongsTo('App\Models\Student');
    }

    public function loan_items() {
        return $this->hasMany('App\Models\LoanItem');
    }
}
