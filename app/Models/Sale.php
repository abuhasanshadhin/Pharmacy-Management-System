<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SyncHasManyRelationTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    use SyncHasManyRelationTrait;

    protected $guarded = [];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($sale) {
            do {
                $latest_number = self::latest('id')->value('invoice_number');
                $number = $latest_number ? explode('-', $latest_number) : null;
                $new_number = isset($number[1]) ? $number[1] + 1 : 1;
                $invoice_number = 'INV-' . str_pad($new_number, 4, '0', STR_PAD_LEFT);
            } while (self::where('invoice_number', $invoice_number)->exists());

            $sale->invoice_number = $invoice_number;
        });
    }

    /**
     * Modify sales date
     *
     * @param string $value
     * @return void
     */
    public function setSaleDateAttribute($value)
    {
        $this->attributes['sale_date'] = date('Y-m-d', strtotime($value));
    }

    /**
     * Get the customer that owns the Purchase
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get all of the sale_details for the Purchase
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sale_details(): HasMany
    {
        return $this->hasMany(SaleDetails::class);
    }

    public function gateway(): BelongsTo
    {
        return $this->belongsTo(Gateway::class,'gateway_id','id');
    }
}
