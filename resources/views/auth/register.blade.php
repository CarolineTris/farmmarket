<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <div class="text-center">
                <x-authentication-card-logo />
                <h2 class="mt-4 text-2xl font-bold text-gray-900">Join FarmMarket</h2>
                <p class="text-gray-600 mt-2">Choose how you want to join our community</p>
            </div>
        </x-slot>

        <!-- Registration Options Cards -->

        <!-- Divider -->
        <div class="relative my-8">
            <div class="absolute inset-0 flex items-center">
                
            </div>
            <div class="relative flex justify-center text-sm">
                 <!-- Buyer Registration Card -->
            <div class=" rounded-xl p-5 text-center hover:bg-blue-50  transition duration-200">
                <div class="mb-4">
                    <i class="bi bi-cart-check text-4xl text-blue-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Register as Buyer</h3>
                <p class="text-sm text-gray-600 mb-4">Buy fresh produce directly from farmers</p>
                
            </div>
            </div>
        </div>

        <!-- Buyer Registration Form -->
        <div id="buyer-form">
            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Hidden role field - default to buyer -->
                <input type="hidden" name="role" value="buyer">

                <div class="mb-4">
                    <x-label for="name" value="{{ __('Full Name') }}" />
                    <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Enter your full name" />
                </div>

                <div class="mb-4">
                    <x-label for="email" value="{{ __('Email Address') }}" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="email" placeholder="your@email.com" />
                </div>

                <div class="mb-4">
                    <x-label for="password" value="{{ __('Password') }}" />
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="Minimum 8 characters" />
                    <p class="text-xs text-gray-500 mt-1">Must be at least 8 characters</p>
                </div>

                <div class="mb-4">
                    <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                    <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Re-enter your password" />
                </div>

                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="mb-6">
                        <x-label for="terms">
                            <div class="flex items-start">
                                <x-checkbox name="terms" id="terms" required class="mt-1" />
                                
                                <div class="ms-2">
                                    <p class="text-sm text-gray-600">
                                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                                        ]) !!}
                                    </p>
                                </div>
                            </div>
                        </x-label>
                    </div>
                @endif

                <div class="flex items-center justify-center  mt-6">

                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="bi bi-person-plus mr-2"></i>
                        {{ __('Register as Buyer') }}
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1  gap-6 mb-8">
            <!-- Farmer Registration Card -->
            <div class=" rounded-xl p-5 text-center hover:bg-green-50  transition duration-200">
                <div class="mb-4">
                    <i class="bi bi-person-badge text-4xl text-green-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Register as Farmer</h3>
                <p class="text-sm text-gray-600 mb-4">Sell your fresh produce directly to buyers</p>
                <a href="{{ route('register.farmer') }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="bi bi-person-plus mr-2"></i>
                    Become a Farmer
                </a>
            </div>
        </div>

        <!-- Additional Links -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <!-- Browse as Guest -->
            <div class="text-center mb-4">
                <p class="text-sm text-gray-600 mb-2">
                <a class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                        {{ __('Already have an account?') }}
                    </a>
                </p>
                <a href="{{ route('marketplace') }}" 
                   class="inline-flex items-center text-sm text-gray-700 hover:text-gray-900">
                    <i class="bi bi-shop mr-2"></i>
                    {{ __('Explore Marketplace') }}
                </a>
            </div>
            
            <!-- Back to Home -->
            <div class="text-center">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                    <i class="bi bi-arrow-left mr-2"></i>
                    {{ __('Back to FarmMarket Home') }}
                </a>
            </div>
            
            <!-- Already have account -->
            <div class="text-center mt-4">
                <p class="text-sm text-gray-500">
                    {{ __('By registering, you agree to our terms and conditions') }}
                </p>
            </div>
        </div>
    </x-authentication-card>
</x-guest-layout>