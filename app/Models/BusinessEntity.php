<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessEntity extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name'];

    public function employeeBusinessEntities(): HasMany
    {
        return $this->hasMany(EmployeeBusinessEntity::class);
    }
}
