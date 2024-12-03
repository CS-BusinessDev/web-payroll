<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeBusinessEntity extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'business_entity_id', 'salary', 'is_primary'];

    // Relasi dengan Employee
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    // Relasi dengan BusinessEntity
    public function businessEntity(): BelongsTo
    {
        return $this->belongsTo(BusinessEntity::class);
    }

    public function grossSalary(): float
   {
       // Mengambil semua data gaji dari EmployeeBusinessEntity dan menjumlahkan salary
       return $this->employeeBusinessEntities->sum('salary');
   }
}
