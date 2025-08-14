<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_spend',
        'max_discount',
        'usage_limit',
        'used_count',
        'user_limit',
        'start_at',
        'end_at',
        'is_active',
        'applicable_games',
        'applicable_products',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_spend' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'user_limit' => 'integer',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_active' => 'boolean',
        'applicable_games' => 'array',
        'applicable_products' => 'array',
    ];

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('start_at')
                          ->orWhere('start_at', '<=', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('end_at')
                          ->orWhere('end_at', '>=', now());
                    });
    }

    // Helper methods
    public function isValid()
    {
        if (!$this->is_active) return false;
        
        if ($this->start_at && $this->start_at > now()) return false;
        if ($this->end_at && $this->end_at < now()) return false;
        
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        
        return true;
    }

    public function calculateDiscount($amount)
    {
        if (!$this->isValid()) return 0;
        
        if ($this->min_spend && $amount < $this->min_spend) return 0;
        
        $discount = $this->type === 'percent' 
            ? ($amount * $this->value / 100)
            : $this->value;
        
        if ($this->max_discount && $discount > $this->max_discount) {
            $discount = $this->max_discount;
        }
        
        return $discount;
    }
}