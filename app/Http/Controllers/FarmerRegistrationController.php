<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\FarmerRegistrationUnderReviewNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
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
            'phone_number' => ['required', 'string', 'max:20'],
            'id_number' => ['required', 'string', 'max:50'],
            'id_document' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'farm_location' => ['required', 'string', 'max:255'],
            'farm_size' => ['required', 'string', 'max:100'],
            'farmer_categories' => ['required', 'array', 'min:1'],
            'farmer_categories.*' => ['required', Rule::in(array_keys(config('product_categories.list', [])))],
            'farming_experience_years' => ['required', 'integer', 'min:0', 'max:80'],
            'capital_injected' => ['required', 'numeric', 'min:0'],
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
            'farmer_categories' => $request->farmer_categories,
            // Preserve a readable legacy text field for older screens/data consumers.
            'crops_grown' => collect((array) $request->farmer_categories)
                ->map(fn ($key) => config("product_categories.list.{$key}", $key))
                ->implode(', '),
            'phone_number' => $request->phone_number,
            'farming_experience_years' => $request->farming_experience_years,
            'capital_injected' => $request->capital_injected,
            'verification_notes' => $request->additional_info,
        ]);

        // Log the user in
        auth()->login($user);

        rescue(fn () => $user->notify(new FarmerRegistrationUnderReviewNotification()), report: false);

        // Redirect to dashboard with success message
        return redirect()->route('dashboard')
            ->with('success', 'Your farmer registration has been submitted for verification! You will be notified once verified.');
    }
}
