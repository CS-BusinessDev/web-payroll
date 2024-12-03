<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'basic_salary',
        'total_allowances',
        'total_deductions',
        'take_home_pay',
        'periode'
    ];

   // Relasi dengan Employee
   public function employee(): BelongsTo
   {
       return $this->belongsTo(Employee::class);
   }

   // Relasi dengan SalaryDetails
   public function salaryDetails(): HasMany
   {
       return $this->hasMany(SalaryDetail::class);
   }
}
