<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'date',
        'description',
        'kredit',
        'debit',
        'balance',
    ];

    /**
     * Tidak ada relasi dengan model lain pada contoh ini
     * Jika Anda memiliki relasi dengan model lain, silakan tambahkan di sini
     */
     
    public static function getMonthName($month)
    {
    $monthNames = [
        '1' => 'January',
        '2' => 'February',
        '3' => 'March',
        '4' => 'April',
        '5' => 'May',
        '6' => 'June',
        '7' => 'July',
        '8' => 'August',
        '9' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December',
    ];

        return $monthNames[$month];
     }
}