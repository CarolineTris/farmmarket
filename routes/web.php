<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Livewire\BuyerDashboard;
use App\Livewire\FarmerDashboard;
use App\Livewire\AdminDashboard;
use App\Livewire\Listings;
use App\Livewire\Orders;
use App\Livewire\Marketplace;
use App\Livewire\BuyerOrders;
use App\Livewire\Wishlist;
use App\Livewire\Cart;
use App\Livewire\AdminFarmers;
use App\Livewire\AdminProducts;
use App\Livewire\AdminOrders;
use App\Http\Controllers\FarmerRegistrationController;
use App\Http\Controllers\FarmerProfileController;
use App\Http\Controllers\PaymentController;
use App\Livewire\Checkout;
use Illuminate\Support\Facades\Storage;


// Public routes
//Route::get('/', function () {
    //return view('welcome');
//});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/farmers/{user}', [FarmerProfileController::class, 'show'])
    ->name('farmers.show');

Route::get('/checkout', \App\Livewire\Checkout::class)
    ->middleware('auth')
    ->name('checkout');



Route::resource('products', ProductController::class)
    ->except(['index', 'show'])
    ->middleware(['auth', 'verified_farmer']);

Route::get('/marketplace', Marketplace::class)->name('marketplace');

// Public media (fallback when storage symlink isn't available)
Route::get('/media/{path}', function (string $path) {
    $path = urldecode($path);
    if (str_contains($path, '..')) {
        abort(404);
    }

    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }

    return Storage::disk('public')->response($path);
})->where('path', '.*')->name('media');

Route::get('/register/farmer', function () {
    return view('auth.farmer-register');
})->name('register.farmer');

// Authenticated & Verified Users
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Redirect based on role
    Route::get('/dashboard', function () {
        switch (auth()->user()->role) {
            case 'farmer':
                return redirect()->route('farmer.dashboard');
            case 'admin':
                return redirect()->route('admin.dashboard');
            default:
                return redirect()->route('buyer.dashboard');
        }
    })->name('dashboard');

    // Role-specific dashboards using Livewire components
    Route::get('/buyer/dashboard', BuyerDashboard::class)->name('buyer.dashboard');
    Route::get('/farmer/dashboard', FarmerDashboard::class)->name('farmer.dashboard');
    Route::get('/admin/dashboard', AdminDashboard::class)->name('admin.dashboard');

    //farmer listings and orders
    Route::get('/farmer/listings', Listings::class)
        ->middleware('verified_farmer')
        ->name('farmer.listings');
    Route::get('/farmer/orders', Orders::class)
        ->middleware('verified_farmer')
        ->name('farmer.orders');

    
    
    Route::get('/buyer/orders', BuyerOrders::class)->name('buyer.orders');
    Route::get('/payments/flutterwave/{order}', [PaymentController::class, 'initFlutterwave'])
        ->name('payments.flutterwave.init');
    Route::get('/payments/flutterwave/callback', [PaymentController::class, 'flutterwaveCallback'])
        ->name('payments.flutterwave.callback');

    // routes/web.php
    Route::get('/wishlist', Wishlist::class)->name('wishlist');
    
    Route::get('/cart', Cart::class)->name('cart');

    //admin routes
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
        Route::get('/farmers', AdminFarmers::class)->name('farmers');
        //Route::get('/products', AdminProducts::class)->name('products');
        //Route::get('/orders', AdminOrders::class)->name('orders');
        Route::get('/farmers/{user}/document', function (\App\Models\User $user) {
            if ($user->role !== 'farmer' || !$user->id_document) {
                abort(404);
            }

            if (!Storage::disk('public')->exists($user->id_document)) {
                abort(404);
            }

            return Storage::disk('public')->response($user->id_document);
        })->name('farmers.document');
    });

});

// Farmer registration submission (protected)
Route::post('/register/farmer', [FarmerRegistrationController::class, 'register'])
->name('register.farmer.submit');
