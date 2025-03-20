<?php

namespace App\Models;

use App\Models\Orders;
use App\Models\CustomerAddresses;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable implements MustVerifyEmail
{

    use Notifiable;
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'gender',
        'birthday',
        'phone',
        'email',
        'email_verified_at',
        'default_address_id',
        'profile_picture',
        'password',
        ];

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

    // Relationship with CustomerAddress
    public function addresses()
    {
        return $this->hasMany(CustomerAddresses::class);
    }

    public function orders()
    {
        return $this->hasMany(Orders::class);
    }

    // Relationship with default address
    public function defaultAddress()
    {
        return $this->belongsTo(CustomerAddresses::class, 'default_address_id');
    }

    // Helper method to get full name
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

}
