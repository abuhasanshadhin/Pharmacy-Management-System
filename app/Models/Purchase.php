<?php

namespace App\Models;

use App\Traits\SyncHasManyRelationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    use SyncHasManyRelationTrait;

    protected $guarded = [];

    /**
     * Modify purchase date
     *
     * @param string $value
     * @return void
     */
    public function setPurchaseDateAttribute($value)
    {
        $this->attributes['purchase_date'] = date('Y-m-d', strtotime($value));
    }

    /**
     * Get the supplier that owns the Purchase
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get all of the purchase_details for the Purchase
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchase_details(): HasMany
    {
        return $this->hasMany(PurchaseDetails::class);
    }
}
