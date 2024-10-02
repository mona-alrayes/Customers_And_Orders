<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    /**
     * Set the phone number, removing the "+" symbol and spaces.
     *
     * @param string $value
     */
    public function setPhoneAttribute(string $value): void
    {
        // Remove any non-numeric characters (like "+" and spaces)
        $this->attributes['phone'] = preg_replace('/[^0-9]/', '', $value);
    }

    /**
     * Get the phone number formatted with a "+" and spaces.
     *
     * @param string $value
     * @return string
     */
    public function getPhoneAttribute(string $value): string
    {
        // Reformat the number as +963 992 438 798
        return '+'.substr($value, 0, 3).' '.substr($value, 3, 3).' '.substr($value, 6, 3).' '.substr($value, 9);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class);
    }
}
