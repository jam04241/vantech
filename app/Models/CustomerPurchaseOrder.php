<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPurchaseOrder extends Model
{
    use HasFactory;

    /**
     * Shown wherever an order has no customer attached.
     *
     * customer_id is nullable: a walk-in buyer who does not give a name is
     * recorded with no customer rather than a placeholder account.
     */
    public const WALK_IN_LABEL = 'Walk-in Customer';

    /**
     * A completed sale. Only these lines count in sales figures.
     */
    public const STATUS_SUCCESS = 'Success';

    /**
     * A sold item that came back broken under warranty. The line stays on its
     * receipt for the record, but no longer counts as a sale.
     */
    public const STATUS_WARRANTY_CLAIM = 'Warranty Claim';

    protected $table = 'customer_purchase_orders';

    protected $fillable = [
        'dr_receipt_id',
        'customer_id',
        'product_id',
        'serial_number',
        'quantity',
        'unit_price',
        'total_price',
        'order_date',
        'status'
    ];

    public function drTransaction()
    {
        return $this->belongsTo(DRTransaction::class, 'dr_receipt_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function paymentMethod()
    {
        return $this->hasOne(PaymentMethod::class);
    }

    public function warrantyClaim()
    {
        return $this->hasOne(WarrantyClaim::class, 'customer_purchase_order_id');
    }

    /**
     * Buyer name for receipts and reports, or the walk-in label.
     */
    public function getCustomerNameAttribute(): string
    {
        return self::customerLabel($this->customer);
    }

    /**
     * Render a customer as a display name, tolerating a missing record.
     */
    public static function customerLabel(?Customer $customer): string
    {
        if (!$customer) {
            return self::WALK_IN_LABEL;
        }

        $name = trim($customer->first_name . ' ' . ($customer->last_name ?? ''));

        return $name !== '' ? $name : self::WALK_IN_LABEL;
    }
}
