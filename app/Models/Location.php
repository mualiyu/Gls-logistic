<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'region',
        'city',
        'zone',
        'location',
    ];

    public function charges(): HasMany
    {
        return $this->hasMany(Charge::class);
    }

    public function package_to(): HasMany
    {
        return $this->hasMany(Package::class);
    }
}
