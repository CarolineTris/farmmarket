<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'verification_status',
        'id_number',
        'id_document',
        'farm_location',
        'farm_size',
        'crops_grown',
        'farmer_categories',
        'phone_number',
        'farming_experience_years',
        'capital_injected',
        'verification_notes',
        'verified_at',
        'verified_by',
        'verification_data',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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
            'verified_at' => 'datetime',
            'verification_data' => 'array',
            'farmer_categories' => 'array',
            'capital_injected' => 'decimal:2',
        ];
    }

    public function getVerificationStatusAttribute($value): string
    {
        return $this->role === 'farmer' ? ($value ?? 'pending') : 'verified';
    }
    //a farmer can have many products
    public function products()
    {
        return $this->hasMany(Product::class, 'farmer_id');
    }

    // Orders placed by the user (buyer)
    public function buyerOrders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    // Order items sold by the user (farmer)
    public function soldItems()
    {
        return $this->hasMany(OrderItem::class, 'farmer_id');
    }

    // Orders a farmer is involved in (via items)
    public function farmerOrders()
    {
        return Order::whereHas('items', function ($q) {
            $q->where('farmer_id', $this->id);
        });
    }


    public function reviews() 
    { 
        return $this->hasMany(Review::class, 'farmer_id'); 
    }

    // In app/Models/User.php
    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class, 'user_id');
    }

    public function wishlistProducts()
    {
        return $this->belongsToMany(Product::class, 'wishlists')
                    ->withTimestamps();
    }

    // In app/Models/User.php
    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'user_id');
    }

     
    //Check if user is admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    //Check if user is farmer
    public function isFarmer(): bool
    {
        return $this->role === 'farmer';
    }

    //Check if user is buyer
    public function isBuyer(): bool
    {
        return $this->role === 'buyer';
    }

    //Check if farmer is verified
    public function isVerifiedFarmer(): bool
    {
        return $this->isFarmer() && $this->verification_status === 'verified';
    }

    //Check if farmer is pending verification
    public function isPendingVerification(): bool
    {
        return $this->isFarmer() && $this->verification_status === 'pending';
    }

    
    //Scope for farmers only
    
    public function scopeFarmers($query)
    {
        return $query->where('role', 'farmer');
    }

    
    //Scope for verified farmers
     
    public function scopeVerifiedFarmers($query)
    {
        return $query->where('role', 'farmer')->where('verification_status', 'verified');
    }

    /**
     * Scope for pending farmers
     */
    public function scopePendingFarmers($query)
    {
        return $query->where('role', 'farmer')->where('verification_status', 'pending');
    }


}
