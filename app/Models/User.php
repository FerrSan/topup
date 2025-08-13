<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'password',
        'avatar_url',
        'balance',
        'referral_code',
        'referred_by',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'last_login_ip',
        'last_login_at',
        'is_banned',
        'ban_reason',
        'preferences',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
        'balance' => 'decimal:2',
        'is_banned' => 'boolean',
        'preferences' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (!$user->referral_code) {
                $user->referral_code = static::generateReferralCode();
            }
        });
    }

    public static function generateReferralCode(): string
    {
        do {
            $code = strtoupper(bin2hex(random_bytes(4)));
        } while (static::where('referral_code', $code)->exists());
        
        return $code;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function getAvatarUrlAttribute($value)
    {
        return $value ?: 'https://ui-avatars.com/api/?name=' . urlencode($this->name);
    }

    public function isBanned(): bool
    {
        return $this->is_banned;
    }

    public function ban($reason = null)
    {
        $this->update([
            'is_banned' => true,
            'ban_reason' => $reason,
        ]);
    }

    public function unban()
    {
        $this->update([
            'is_banned' => false,
            'ban_reason' => null,
        ]);
    }
}