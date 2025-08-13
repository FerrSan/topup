<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'provider',
        'event_type',
        'reference_id',
        'signature',
        'headers',
        'payload',
        'verified',
        'processed',
        'error_message',
        'ip_address',
    ];

    protected $casts = [
        'headers' => 'array',
        'payload' => 'array',
        'verified' => 'boolean',
        'processed' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function scopeUnprocessed($query)
    {
        return $query->where('processed', false)
            ->where('verified', true);
    }

    public function scopeProvider($query, $provider)
    {
        return $query->where('provider', $provider);
    }

    public function markAsProcessed()
    {
        $this->update(['processed' => true]);
    }

    public function markAsFailed($errorMessage)
    {
        $this->update([
            'processed' => true,
            'error_message' => $errorMessage,
        ]);
    }
}
