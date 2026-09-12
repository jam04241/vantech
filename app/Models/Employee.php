<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    /**
     * Job titles the owner can assign to a staff record.
     *
     * This is the role on the staff record, assigned by the owner after they
     * review an application. The login permission level is a separate field on
     * the user account, and is always 'staff' for applicants.
     */
    public const ROLES = ['Staff', 'Assistant', 'Technical', 'Cashier'];

    /**
     * street, barangay, city and role are nullable in the database. Applicants
     * supply their own address when registering, but may leave parts of it
     * blank, and the position is only set once the owner assigns one.
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'street',
        'barangay',
        'city',
        'phone_number',
        'role',
        'gender',
    ];

    /**
     * The login account belonging to this staff member, if they registered one.
     */
    public function user()
    {
        return $this->hasOne(User::class, 'employee_id');
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Address as one line, skipping any part that was left blank.
     * Returns an empty string when no part of the address is set.
     */
    public function getAddressAttribute(): string
    {
        return trim(implode(', ', array_filter([
            $this->street,
            $this->barangay,
            $this->city,
        ], fn ($part) => filled($part))));
    }

    /**
     * Account state shown on the staff record.
     *
     * Staff added before self-registration existed have no login account at all,
     * which is distinct from having one that is switched off.
     */
    public function getAccountStatusAttribute(): string
    {
        return $this->user?->status ?? 'no_account';
    }

    public function getAccountStatusLabelAttribute(): string
    {
        return match ($this->account_status) {
            User::STATUS_PENDING => 'Pending Approval',
            User::STATUS_ACTIVE => 'Active',
            User::STATUS_INACTIVE => 'Deactivated',
            default => 'No Account',
        };
    }
}
