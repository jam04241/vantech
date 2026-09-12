<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /** Registered, waiting for the owner to approve. Cannot log in. */
    public const STATUS_PENDING = 'pending';

    /** Approved by the owner. Can log in. */
    public const STATUS_ACTIVE = 'active';

    /** Switched off by the owner. Cannot log in. */
    public const STATUS_INACTIVE = 'inactive';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'username',
        'password',
        'role',
        'status',
        'employee_id',
        'approved_at',
        'approved_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The staff record this login belongs to.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * The owner who approved this account.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Only an approved account may sign in.
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Full name for audit lines and receipts.
     */
    public function getFullNameAttribute(): string
    {
        return trim(implode(' ', array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
        ])));
    }

    /**
     * Why a blocked account cannot sign in, phrased for the login screen.
     */
    public function loginBlockedReason(): ?string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Your account is still waiting for the owner to approve it. '
                . 'Please try again once you have been notified.',
            self::STATUS_INACTIVE => 'Your account has been deactivated. Please contact the owner.',
            default => null,
        };
    }
}
