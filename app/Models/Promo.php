<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type', // percentage, fixed, bonus
        'discount_value',
        'min_amount',
        'max_discount',
        'usage_limit',
        'used_count',
        'is_active',
        'start_date',
        'end_date',
        'applicable_games', // JSON array of game IDs
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'applicable_games' => 'array',
    ];

    /**
     * Check if promo is valid
     */
    public function isValid()
    {
        $now = now();
        
        return $this->is_active &&
               $this->start_date <= $now &&
               $this->end_date >= $now &&
               $this->used_count < $this->usage_limit;
    }

    /**
     * Check if promo can be used for a specific game
     */
    public function isApplicableForGame($gameId)
    {
        return empty($this->applicable_games) || in_array($gameId, $this->applicable_games);
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount($amount)
    {
        if ($this->discount_type === 'percentage') {
            $discount = $amount * ($this->discount_value / 100);
            return min($discount, $this->max_discount ?? $discount);
        } elseif ($this->discount_type === 'fixed') {
            return min($this->discount_value, $amount);
        } elseif ($this->discount_type === 'bonus') {
            return $this->discount_value;
        }
        
        return 0;
    }

    /**
     * Check if promo can be applied to transaction
     */
    public function canBeApplied($amount, $gameId = null)
    {
        if (!$this->isValid()) {
            return false;
        }

        if ($amount < $this->min_amount) {
            return false;
        }

        if ($gameId && !$this->isApplicableForGame($gameId)) {
            return false;
        }

        return true;
    }

    /**
     * Increment usage count
     */
    public function incrementUsage()
    {
        $this->increment('used_count');
    }

    /**
     * Get formatted discount text
     */
    public function getDiscountTextAttribute()
    {
        return match($this->discount_type) {
            'percentage' => $this->discount_value . '%',
            'fixed' => 'Rp ' . number_format($this->discount_value, 0, ',', '.'),
            'bonus' => '+' . $this->discount_value . ' Bonus',
            default => 'Tidak diketahui',
        };
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        if (!$this->is_active) {
            return 'badge-secondary';
        }
        
        if (!$this->isValid()) {
            return 'badge-danger';
        }
        
        return 'badge-success';
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        if (!$this->is_active) {
            return 'Tidak Aktif';
        }
        
        if (!$this->isValid()) {
            return 'Kadaluarsa';
        }
        
        return 'Aktif';
    }
} 