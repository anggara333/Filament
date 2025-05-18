<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absen extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'employee_id',
        'jam_masuk',
        'jam_pulang',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
