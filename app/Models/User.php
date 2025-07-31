<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
    'avatar',
    'facebook_id',
     'shipping_street',
        'shipping_city',
        'shipping_state',
        'shipping_postal_code',
        'shipping_country',
        'latitude',
        'longitude',
        'current_address'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
      public function getFullShippingAddressAttribute()
    {
        return implode(', ', array_filter([
            $this->shipping_street,
            $this->shipping_city,
            $this->shipping_state,
            $this->shipping_postal_code,
            $this->shipping_country
        ]));
    }

    // Mutator for current location (if using latitude/longitude)
    public function setCurrentLocationAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['latitude'] = $value['latitude'] ?? null;
            $this->attributes['longitude'] = $value['longitude'] ?? null;
        }
    }

    public function getCurrentLocationAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude
            ];
        }
        return null;
    }
}
