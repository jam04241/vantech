<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product_Stocks;

class Product extends Model
{
    use HasFactory;

    /**
     * Canonical value stored in `warranty_period` for items sold without a warranty.
     *
     * The `products.warranty_period` column is NOT NULL in the database, so items
     * without coverage (cables, consumables, peripherals, most second hand stock)
     * are stored with this sentinel instead of NULL or an empty string.
     */
    public const NO_WARRANTY = 'No Warranty';

    /**
     * Values that all mean "this item has no warranty coverage".
     */
    private const NO_WARRANTY_ALIASES = ['', 'none', 'n/a', 'na', 'no warranty', 'no-warranty', 'null', '-'];

    protected $fillable = [
        'product_name',
        'brand_id',
        'category_id',
        'supplier_id',
        'warranty_period',
        'serial_number',
        'product_condition',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class);
    }

    public function stock()
    {
        return $this->hasOne(Product_Stocks::class, 'product_id');
    }

    /**
     * Get customer purchase orders for this product.
     */
    public function customerPurchaseOrders()
    {
        return $this->hasMany(\App\Models\CustomerPurchaseOrder::class, 'product_id');
    }

    /**
     * Normalise anything that means "no warranty" to the canonical sentinel.
     *
     * Guarantees the NOT NULL column never receives null or an empty string, so a
     * product saved without picking a warranty is explicitly uncovered rather than
     * being left blank and then guessed at by the POS.
     */
    public static function normalizeWarranty($value): string
    {
        $value = trim((string) $value);

        if (in_array(strtolower($value), self::NO_WARRANTY_ALIASES, true)) {
            return self::NO_WARRANTY;
        }

        return $value;
    }

    public function setWarrantyPeriodAttribute($value): void
    {
        $this->attributes['warranty_period'] = self::normalizeWarranty($value);
    }

    /**
     * True when the product actually carries warranty coverage.
     */
    public function hasWarranty(): bool
    {
        return self::normalizeWarranty($this->warranty_period) !== self::NO_WARRANTY;
    }

    /**
     * Warranty text safe to print on receipts, reports and the POS.
     *
     * Never invents coverage: an item with no warranty reads "No Warranty".
     */
    public function getWarrantyLabelAttribute(): string
    {
        return self::normalizeWarranty($this->warranty_period);
    }
}
