<?php

namespace App\Http\Controllers\Client;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\EnsureCustomerIsAuthenticated;

class ClientProfileController extends Controller
{

    public function index()
    {
         // Check if user is authenticated


        $customer = Auth::guard('customer')->user();
        $addresses = $customer->addresses;
        $defaultAddress = $addresses->where('id', $customer->default_address_id)->first();
        return Inertia::render('ClientSide/Customer/Profile', [
            'customer' => $customer,
            'addresses' => $addresses,
            'defaultAddress' => $defaultAddress,
        ]);
    }
}
