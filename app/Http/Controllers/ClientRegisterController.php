<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Customer;
use App\Models\CustomerAddresses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class ClientRegisterController extends Controller
{
    public function create() {
        return Inertia::render("Auth/Register");
    }

    public function show() {
        $customer = Auth::guard('customer')->user();
        return Inertia::render("ClientSide/Customer/RegisterProcess", [
            'customer' => $customer,
            'addresses' => $customer->addresses
        ]);
    }

    public function store(Request $request) {
        $credentials = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = Customer::create([
            'first_name' => $credentials['first_name'],
            'last_name' => $credentials['last_name'],
            'email' => $credentials['email'],
            'password' => bcrypt($credentials['password']),
        ]);

        event(new Registered($user));

        Auth::guard('customer')->login($user);

        return redirect()->route('customer.register.process');
    }

    public function update(Request $request)
{
    // Get the authenticated customer
    $customer = Auth::guard('customer')->user();


    if (!$customer instanceof Customer) {
        // Handle the case where the customer is not found or not authenticated
        return redirect()->route('home')->withErrors('Customer not found.');
    }

    // Validate the request data
    $data = $request->validate([
        'username' => 'sometimes|string|max:255|unique:customers,username,' . $customer->id,
        'gender' => 'sometimes|string',
        'birthday' => 'sometimes|date',
        'phone' => 'required|string|max:15',
        'province' => 'required|string',
        'city' => 'required|string',
        'zip_code' => 'required|string|max:10',
        'address' => 'required|string|max:500',
    ]);

    // Update the customer's details
    $customer->update($data);

    // Update or create the customer's address
   // Update or create the customer's address
   $address = $customer->addresses()->updateOrCreate(
    [
        'customer_id' => $customer->id, // Match by customer_id
    ],
    [
        'customer_id' => $customer->id, // Ensure customer_id is included
        'first_name' => $customer->first_name,
        'last_name' => $customer->last_name,
        'phone_number' => $data['phone'],
        'province' => $data['province'],
        'city' => $data['city'],
        'zip_code' => $data['zip_code'],
        'complete_address' => $data['address'],
        'default_address' => true, // Assuming this is the default address
    ]
);

// Check if the default address is set to true
if ($address->default_address) {
    // Update the default_address_id in the customer table
    $customer->default_address_id = $address->id;
    $customer->save();
}

    return redirect()->route('home')->with('success', 'Profile updated successfully.');
}



    public function destroy(Request $request) {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
