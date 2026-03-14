<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'label',
        'name',
        'phone',
        'address',
        'city',
        'state',
        'pincode',
        'landmark',
        'type',
        'is_default',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    /**
     * Get the user that owns this address.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the formatted address
     */
    public function getFormattedAddressAttribute(): string
    {
        $parts = [
            $this->address,
            $this->landmark ? "Near {$this->landmark}" : null,
            $this->city,
            $this->state,
            $this->pincode
        ];

        return implode(', ', array_filter($parts));
    }

    /**
     * Scope to get default address
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Set this address as default and unset others
     */
    public function makeDefault()
    {
        // Unset all other default addresses for this user
        $this->user->addresses()->update(['is_default' => false]);
        
        // Set this one as default
        $this->update(['is_default' => true]);
    }
}
