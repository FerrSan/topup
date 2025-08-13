<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'PENDING';
    const STATUS_WAITING_PAYMENT = 'WAITING_PAYMENT';
    const STATUS_PAID = 'PAID';
    const STATUS_PROCESSING = 'PROCESSING';
    const STATUS_SUCCESS = 'SUCCESS';
    const STATUS_FAILED = 'FAILED';
    const STATUS_REFUNDED = 'REFUNDED';
    const STATUS_EXPIRED = 'EXPIRED';
    const STATUS_CANCELLED = 'CANCELLED';

    protected $fillable = [
        'invoice_no',
        'user_id',
        'game_id',
        'product_id',
        'coupon_id',
        'qty',
        'buyer_note',
        'player_uid',
        'player_server',
        'player_name',
        'price',
        'discount',
        'fee',
        'grand_total',
        'status',
        'payment_provider',
        'payment_method',
        'payment_ref',
        'payment_token',
        'payment_url',
        'payment_data',
        'paid_at',
        'processed_at',
        'completed_at',
        'expired_at',
        'ip_address',
        'user_agent',
        'referer',
        'metadata',
        'vendor_response',
        'retry_count',
        'idempotency_key',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'fee' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'payment_data' => 'array',
        'metadata' => 'array',
        'vendor_response' => 'array',
        'paid_at' => 'datetime',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->invoice_no) {
                $order->invoice_no = static::generateInvoiceNo();
            }
            
            if (!$order->idempotency_key) {
                $order->idempotency_key = Str::uuid()->toString();
            }
            
            if (!$order->expired_at) {
                $order->expired_at = now()->addHours(24);
            }
        });
    }

    public static function generateInvoiceNo(): string
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(6));
        
        return "{$prefix}{$date}{$random}";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function product()
    {
        return $this->belongsTo(GameProduct::class, 'product_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function events()
    {
        return $this->hasMany(OrderEvent::class);
    }

    public function testimonial()
    {
        return $this->hasOne(Testimonial::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', self::STATUS_SUCCESS);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isSuccess(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_WAITING_PAYMENT,
        ]);
    }

    public function canBeRefunded(): bool
    {
        return in_array($this->status, [
            self::STATUS_SUCCESS,
            self::STATUS_FAILED,
        ]) && $this->paid_at !== null;
    }

    public function logEvent(string $type, array $payload = [], string $actor = 'system')
    {
        return $this->events()->create([
            'type' => $type,
            'actor' => $actor,
            'description' => $this->getEventDescription($type),
            'payload' => $payload,
            'ip_address' => request()->ip(),
        ]);
    }

    private function getEventDescription(string $type): string
    {
        $descriptions = [
            'CREATED' => 'Order created',
            'WEBHOOK_PAID' => 'Payment received via webhook',
            'WEBHOOK_FAILED' => 'Payment failed via webhook',
            'WEBHOOK_EXPIRED' => 'Payment expired via webhook',
            'TOPUP_STARTED' => 'Top-up process started',
            'TOPUP_RETRY' => 'Top-up process retried',
            'TOPUP_SUCCESS' => 'Top-up completed successfully',
            'TOPUP_FAILED' => 'Top-up process failed',
            'REFUND_INITIATED' => 'Refund initiated',
            'REFUND_COMPLETED' => 'Refund completed',
        ];

        return $descriptions[$type] ?? $type;
    }
}