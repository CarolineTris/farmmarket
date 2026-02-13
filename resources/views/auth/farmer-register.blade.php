<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <div class="text-center">
                <x-authentication-card-logo />
                <h2 class="mt-4 text-2xl font-bold text-gray-900">Farmer Registration</h2>
                <p class="text-gray-600 mt-2">Register to sell your products on FarmMarket</p>
            </div>
        </x-slot>

        <x-validation-errors class="mb-6" />

        <form method="POST" action="{{ route('register.farmer.submit') }}" enctype="multipart/form-data">
            @csrf

            <!-- Hidden role field -->
            <input type="hidden" name="role" value="farmer">

            <!-- Basic Information -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-label for="name" value="{{ __('Full Name') }}" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    </div>
                    <div>
                        <x-label for="email" value="{{ __('Email Address') }}" />
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="email" />
                    </div>
                    <div>
                        <x-label for="password" value="{{ __('Password') }}" />
                        <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                    </div>
                    <div>
                        <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                        <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                    </div>
                    <div>
                        <x-label for="phone_number" value="{{ __('Phone Number') }}" />
                        <x-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number')" required placeholder="e.g., 2567XXXXXXXX" />
                    </div>
                </div>
            </div>

            <!-- Verification Documents -->
            <div class="mb-6 pt-6 border-t">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Verification Documents</h3>
                
                <!-- National ID -->
                <div class="mb-4">
                    <x-label for="id_number" value="{{ __('National ID Number') }}" />
                    <x-input id="id_number" class="block mt-1 w-full" type="text" name="id_number" :value="old('id_number')" required placeholder="Enter your National ID number" />
                </div>
                
                <!-- ID Document Upload -->
                <div class="mb-4">
                    <x-label for="id_document" value="{{ __('Upload National ID (Front & Back)') }}" />
                    <input id="id_document" 
                           class="block mt-1 w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                           type="file" 
                           name="id_document" 
                           accept="image/*,.pdf" 
                           required />
                    <p class="text-xs text-gray-500 mt-1">Upload clear images or PDF of your National ID</p>
                </div>
                
                <!-- Farm Information -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <x-label for="farm_location" value="{{ __('Farm Location') }}" />
                        <x-input id="farm_location" class="block mt-1 w-full" type="text" name="farm_location" :value="old('farm_location')" required placeholder="District, Village" />
                    </div>
                    <div>
                        <x-label for="farm_size" value="{{ __('Farm Size (Acres)') }}" />
                        <x-input id="farm_size" class="block mt-1 w-full" type="text" name="farm_size" :value="old('farm_size')" required placeholder="e.g., 5 acres" />
                    </div>
                </div>

                <div class="mb-4">
                    <x-label value="{{ __('Farm Categories (Select one or more)') }}" />
                    <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach(config('product_categories.list', []) as $key => $label)
                            <label class="inline-flex items-center rounded border border-gray-200 px-3 py-2 bg-white hover:bg-gray-50">
                                <input type="checkbox"
                                       name="farmer_categories[]"
                                       value="{{ $key }}"
                                       class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                       {{ in_array($key, old('farmer_categories', []), true) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('farmer_categories')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('farmer_categories.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Experience and Capital -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <x-label for="farming_experience_years" value="{{ __('Farming Experience (Years)') }}" />
                        <x-input id="farming_experience_years" class="block mt-1 w-full" type="number" min="0" max="80" name="farming_experience_years" :value="old('farming_experience_years')" required placeholder="e.g., 8" />
                    </div>
                    <div>
                        <x-label for="capital_injected" value="{{ __('Capital Injected (UGX)') }}" />
                        <x-input id="capital_injected" class="block mt-1 w-full" type="number" min="0" step="0.01" name="capital_injected" :value="old('capital_injected')" required placeholder="e.g., 2500000" />
                    </div>
                </div>
                
                <!-- Additional Information -->
                <div>
                    <x-label for="additional_info" value="{{ __('Additional Information (Optional)') }}" />
                    <textarea id="additional_info" 
                              name="additional_info" 
                              rows="3" 
                              class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                              placeholder="Tell us more about your farming experience, equipment, etc.">{{ old('additional_info') }}</textarea>
                </div>
            </div>

            <!-- Terms Agreement -->
            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mb-6">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />
                            <div class="ms-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <!-- Additional Terms -->
            <div class="mb-6">
                <div class="flex items-center">
                    <x-checkbox name="verification_consent" id="verification_consent" required />
                    <label for="verification_consent" class="ml-2 text-sm text-gray-600">
                        I agree that my information will be verified by FarmMarket administrators
                    </label>
                </div>
            </div>
            
            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="bi bi-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Your application will be reviewed within 24-48 hours. You'll receive an email notification once verified.
                            You won't be able to list products until your account is verified.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-center mt-6">
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="bi bi-person-badge mr-2"></i>
                    {{ __('Submit Registration for Verification') }}
                </button>
            </div>
        </form>

        <!-- Additional Links -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="text-center space-y-4">
                <!-- Already have account -->
                <div>
                    <p class="text-sm text-gray-600">
                        {{ __('Already have an account?') }}
                        <a href="{{ route('login') }}" class="font-medium text-green-600 hover:text-green-500 ml-1">
                            {{ __('Log in here') }}
                        </a>
                    </p>
                </div>
                
                <!-- Register as buyer -->
                <div>
                    <p class="text-sm text-gray-600">
                        {{ __('Want to buy instead?') }}
                        <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500 ml-1">
                            {{ __('Register as Buyer') }}
                        </a>
                    </p>
                </div>
                
                <!-- Browse marketplace -->
                <div>
                    <a href="{{ route('marketplace') }}" 
                       class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                        <i class="bi bi-shop mr-2"></i>
                        {{ __('Browse Marketplace') }}
                    </a>
                </div>
                
                <!-- Back to home -->
                <div>
                    <a href="{{ url('/index') }}" 
                       class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                        <i class="bi bi-arrow-left mr-2"></i>
                        {{ __('Back to FarmMarket Home') }}
                    </a>
                </div>
            </div>
        </div>
    </x-authentication-card>
</x-guest-layout>
