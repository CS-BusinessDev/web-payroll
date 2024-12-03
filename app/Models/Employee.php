<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'name'];

    public function employeeBusinessEntities(): HasMany
    {
        return $this->hasMany(EmployeeBusinessEntity::class);
    }

    // Mendapatkan Primary Business Entity
    public function primaryBusinessEntity(): HasOne
    {
        return $this->hasOne(EmployeeBusinessEntity::class)->where('is_primary', true);
    }

    // Relasi dengan Salary
    public function salaries(): HasMany
    {
        return $this->hasMany(Salary::class);
    }

    // In Employee model
    public function getGrossSalaryAttribute(): float
    {
        // Ensure employeeBusinessEntities is not empty before summing
        return $this->employeeBusinessEntities->sum('salary') ?? 0;
    }

    public function grossSalary(): float
    {
        // Mengambil semua salary yang terkait dengan karyawan ini dan menghitung totalnya
        return $this->employeeBusinessEntities->sum('salary');
    }
}
