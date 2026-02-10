<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['farmer_id', 'category', 'amount', 'status'];

    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

}
