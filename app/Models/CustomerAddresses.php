<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class CustomerAddresses extends Model
{
    protected $fillable = [
        'customer_id',
        'first_name',
        'last_name',
        'phone_number',
        'province',
        'city',
        'zip_code',
        'complete_address',
        'default_address'
    ];

    protected $casts = [
        'default_address' => 'boolean',
    ];

    // Relationship with Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orders()
    {
        return $this->hasMany(Orders::class, 'default_address_id');
    }

    // Helper method to get full name
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Helper method to get complete formatted address
    public function getFormattedAddressAttribute(): string
    {
        return "{$this->complete_address}, {$this->city}, {$this->province} {$this->zip_code}";
    }
}
