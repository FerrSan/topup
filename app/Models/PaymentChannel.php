<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentChannel extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'code',
        'name',
        'type',
        'logo_url',
        'fee_flat',
        'fee_percent',
        'min_amount',
        'max_amount',
        'is_active',
        'sort_order',
        'metadata',
    ];

    protected $casts = [
        'fee_flat' => 'decimal:2',
        'fee_percent' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeProvider($query, $provider)
    {
        return $query->where('provider', $provider);
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function calculateFee(float $amount): float
    {
        $fee = $this->fee_flat;
        
        if ($this->fee_percent > 0) {
            $fee += $amount * ($this->fee_percent / 100);
        }
        
        return round($fee, 2);
    }

    public function isAmountValid(float $amount): bool
    {
        if ($this->min_amount && $amount < $this->min_amount) {
            return false;
        }
        
        if ($this->max_amount && $amount > $this->max_amount) {
            return false;
        }
        
        return true;
    }
}