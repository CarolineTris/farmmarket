<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class FarmerRegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.farmer-register');
    }

    public function register(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'id_number' => ['required', 'string', 'max:50'],
            'id_document' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'farm_location' => ['required', 'string', 'max:255'],
            'farm_size' => ['required', 'string', 'max:100'],
            'crops_grown' => ['required', 'string', 'max:255'],
            'additional_info' => ['nullable', 'string'],
            'verification_consent' => ['required', 'accepted'],
            'terms' => ['required', 'accepted'],
        ]);

        // Handle ID document upload
        $idDocumentPath = null;
        if ($request->hasFile('id_document')) {
            $idDocumentPath = $request->file('id_document')->store('id_documents', 'public');
        }

        // Create the farmer user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'farmer',
            'verification_status' => 'pending', // Start as pending
            'id_number' => $request->id_number,
            'id_document' => $idDocumentPath,
            'farm_location' => $request->farm_location,
            'farm_size' => $request->farm_size,
            'crops_grown' => $request->crops_grown,
            'verification_notes' => $request->additional_info,
        ]);

        // Log the user in
        auth()->login($user);

        // Redirect to dashboard with success message
        return redirect()->route('dashboard')
            ->with('success', 'Your farmer registration has been submitted for verification! You will be notified once verified.');
    }
}