<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','slug','publisher','icon_url','description',
        'category','is_active','is_featured','sort_order','metadata','view_count'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'metadata' => 'array',
        'view_count' => 'integer',
    ];

    // --- Routing by slug ---
    public function getRouteKeyName(): string { return 'slug'; }

    // --- Boot: slug unik ---
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Game $game) {
            if (!$game->slug) $game->slug = static::uniqueSlug($game->name);
        });

        static::updating(function (Game $game) {
            if ($game->isDirty('name') && !$game->isDirty('slug')) {
                $game->slug = static::uniqueSlug($game->name, $game->id);
            }
        });
    }

    protected static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'game';
        $slug = $base; $i = 2;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->whereKeyNot($ignoreId))
            ->exists()) {
            $slug = "{$base}-{$i}"; $i++;
        }
        return $slug;
    }

    // --- Relationships ---
    public function products()   { return $this->hasMany(GameProduct::class); }
    public function orders()     { return $this->hasMany(Order::class); }
    public function testimonials(){ return $this->hasMany(Testimonial::class); }

    // --- Scopes ---
    public function scopeActive($q)   { return $q->where('is_active', true); }
    public function scopeFeatured($q) { return $q->where('is_featured', true); }
    public function scopeOrdered($q)  { return $q->orderBy('sort_order')->orderBy('name'); }
    public function scopeSearch($q, ?string $term)
    {
        $term = trim((string)$term);
        return $term === '' ? $q : $q->where(fn($qq) =>
            $qq->where('name','like',"%{$term}%")
               ->orWhere('publisher','like',"%{$term}%")
        );
    }
}