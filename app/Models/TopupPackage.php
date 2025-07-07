<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopupPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'name',
        'amount',
        'price',
        'description',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'integer',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the game that owns this package
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Get transactions for this package
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get formatted amount with currency
     */
    public function getFormattedAmountAttribute()
    {
        return $this->amount . ' ' . $this->game->currency_name;
    }
} 