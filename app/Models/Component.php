<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Component extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type'];

    const ALLOWANCE = 'allowance';
    const DEDUCTION = 'deduction';

    // Menentukan tipe data
    protected $casts = [
        'type' => 'string',
    ];

    public function salaryDetails(): HasMany
    {
        return $this->hasMany(SalaryDetail::class);
    }
}
