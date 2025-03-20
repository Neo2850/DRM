<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\ClientLoginController;
use App\Http\Controllers\AuthenticateController;
use App\Http\Controllers\ClientRegisterController;
use App\Http\Controllers\Client\PCBuilderController;
use App\Http\Controllers\Client\ProductListController;
use App\Http\Controllers\Client\ProductViewController;
use App\Http\Controllers\Client\CategoryProductsController;
use App\Http\Controllers\Client\ComponentSelectionController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderDetailsController;







Route::middleware(['web'])->group(function () {
    Route::controller(CartController::class)->group(function () {
        Route::get('/cart', 'index')->name('cart.index');
        Route::post('/cart/add', 'addToCart')->name('cart.add');
        Route::post('/cart/update-quantity', 'updateQuantity')->name('cart.updateQuantity');
        Route::post('/cart/remove', 'removeFromCart')->name('cart.remove');
        Route::post('/cart/update-selection', 'updateSelection')->name('cart.updateSelection');
        Route::post('/cart/clear', 'clearCart')->name('cart.clear');
        Route::post('/cart/update-all-selections', 'updateAllSelections')
            ->name('cart.updateAllSelections');
        Route::post('/cart/add-multiple', 'addMultiple')->name('cart.add-multiple');
    });
});







require __DIR__ . '/auth.php';
require __DIR__ . '/customer-auth.php';

Route::fallback(function () {
    return Inertia::render('Errors/404');
});




