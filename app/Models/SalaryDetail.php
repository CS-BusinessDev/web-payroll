<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'salary_id',
        'component_id',
        'amount'
    ];

   // Relasi dengan Salary
   public function salary(): BelongsTo
   {
       return $this->belongsTo(Salary::class);
   }

   // Relasi dengan Component
   public function component(): BelongsTo
   {
       return $this->belongsTo(Component::class);
   }
}
