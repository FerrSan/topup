<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'name',
        'nominal_code',
        'price',
        'original_price',
        'cost',
        'currency',
        'is_hot',
        'is_promo',
        'is_active',
        'sort_order',
        'process_time',
        'metadata',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'cost' => 'decimal:2',
        'is_hot' => 'boolean',
        'is_promo' => 'boolean',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'product_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeHot($query)
    {
        return $query->where('is_hot', true);
    }

    public function scopePromo($query)
    {
        return $query->where('is_promo', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }

    public function getDiscountPercentageAttribute()
    {
        if (!$this->original_price || $this->original_price <= $this->price) {
            return 0;
        }
        
        return round((($this->original_price - $this->price) / $this->original_price) * 100);
    }

    public function getProfitAttribute()
    {
        if (!$this->cost) {
            return null;
        }
        
        return $this->price - $this->cost;
    }

    public function getProfitMarginAttribute()
    {
        if (!$this->cost || $this->cost == 0) {
            return null;
        }
        
        return round((($this->price - $this->cost) / $this->cost) * 100, 2);
    }
}