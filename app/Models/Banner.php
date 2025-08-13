<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_url',
        'mobile_image_url',
        'link_url',
        'link_type',
        'position',
        'start_at',
        'end_at',
        'is_active',
        'sort_order',
        'click_count',
        'view_count',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_active' => 'boolean',
    ];

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

    public function scopePosition($query, $position)
    {
        return $query->where('position', $position);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    public function incrementView()
    {
        $this->increment('view_count');
    }

    public function incrementClick()
    {
        $this->increment('click_count');
    }

    public function getImageAttribute()
    {
        if (request()->userAgent() && str_contains(strtolower(request()->userAgent()), 'mobile')) {
            return $this->mobile_image_url ?: $this->image_url;
        }
        
        return $this->image_url;
    }

    public function getCtrAttribute()
    {
        if ($this->view_count === 0) {
            return 0;
        }
        
        return round(($this->click_count / $this->view_count) * 100, 2);
    }
}