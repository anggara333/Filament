<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'stock',
    ];
    
        /**
     * posts
     *
     * @return void
     */
    public function storages()
    {
        return $this->hasMany(Storage::class);
    }
    
    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }
}
