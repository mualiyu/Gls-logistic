<?php

namespace App\Models;

use Illuminate\Cache\Lock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'customer_id',
        'from',
        'to',
        'address_from',
        'address_to',
        'tracking_id',
        'adjusted_amount',
        'total_amount',
        'phone',
        'email',
        'item_type',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function trackings(): HasMany
    {
        return $this->hasMany(Tracking::class);
    }

    public function to_location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'to', 'location');
    }
}
