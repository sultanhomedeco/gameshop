<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'currency_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get topup packages for this game
     */
    public function topupPackages()
    {
        return $this->hasMany(TopupPackage::class);
    }

    /**
     * Get active topup packages for this game
     */
    public function activeTopupPackages()
    {
        return $this->hasMany(TopupPackage::class)->where('is_active', true);
    }

    /**
     * Get transactions for this game
     */
    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, TopupPackage::class);
    }
} 