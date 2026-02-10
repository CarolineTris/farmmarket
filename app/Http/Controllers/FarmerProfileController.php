<?php

namespace App\Http\Controllers;

use App\Models\User;

class FarmerProfileController extends Controller
{
    public function show(User $user)
    {
        // Only farmers
        if ($user->role !== 'farmer') {
            abort(404);
        }

        // Only approved farmers visible to buyers
        if ($user->verification_status !== 'verified') {
            abort(404);
        }

        // Load farmer products
        $products = $user->products()->latest()->get();

        return view('farmers.show', compact('user', 'products'));
    }
}
