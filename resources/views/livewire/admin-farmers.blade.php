

<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-sm text-gray-600">Total Farmers</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg shadow p-4">
                <p class="text-sm text-yellow-600">Pending Review</p>
                <p class="text-2xl font-bold text-yellow-700">{{ $stats['pending'] }}</p>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg shadow p-4">
                <p class="text-sm text-green-600">Verified</p>
                <p class="text-2xl font-bold text-green-700">{{ $stats['verified'] }}</p>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-lg shadow p-4">
                <p class="text-sm text-red-600">Rejected</p>
                <p class="text-2xl font-bold text-red-700">{{ $stats['rejected'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Farmer List -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow">
                    <!-- Search and Filters -->
                    <div class="p-4 border-b">
                        <input type="text" wire:model.live="search" 
                               placeholder="Search farmers..." 
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        
                        <div class="mt-3 flex flex-wrap gap-2">
                            <button wire:click="$set('verificationStatus', '')" 
                                    class="px-3 py-1 text-sm rounded-lg {{ !$verificationStatus ? 'bg-gray-800 text-white' : 'bg-gray-100 hover:bg-gray-200' }}">
                                All
                            </button>
                            <button wire:click="$set('verificationStatus', 'pending')" 
                                    class="px-3 py-1 text-sm rounded-lg {{ $verificationStatus == 'pending' ? 'bg-yellow-500 text-white' : 'bg-yellow-100 hover:bg-yellow-200' }}">
                                Pending
                            </button>
                            <button wire:click="$set('verificationStatus', 'verified')" 
                                    class="px-3 py-1 text-sm rounded-lg {{ $verificationStatus == 'verified' ? 'bg-green-500 text-white' : 'bg-green-100 hover:bg-green-200' }}">
                                Verified
                            </button>
                            <button wire:click="$set('verificationStatus', 'rejected')" 
                                    class="px-3 py-1 text-sm rounded-lg {{ $verificationStatus == 'rejected' ? 'bg-red-500 text-white' : 'bg-red-100 hover:bg-red-200' }}">
                                Rejected
                            </button>
                        </div>
                    </div>

                    <!-- Farmer List -->
                    <div class="divide-y divide-gray-200 max-h-[600px] overflow-y-auto">
                        @forelse($farmers as $farmer)
                        <div wire:click="selectFarmer({{ $farmer->id }})" 
                             class="p-4 hover:bg-gray-50 cursor-pointer transition {{ $selectedFarmer && $selectedFarmer->id == $farmer->id ? 'bg-blue-50 border-l-4 border-blue-500' : '' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <img class="h-10 w-10 rounded-full" 
                                         src="{{ $farmer->profile_photo_url }}" 
                                         alt="{{ $farmer->name }}">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $farmer->name }}</p>
                                        <p class="text-sm text-gray-600 truncate max-w-[150px]">{{ $farmer->email }}</p>
                                    </div>
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full
                                    @if($farmer->verification_status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($farmer->verification_status == 'verified') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($farmer->verification_status) }}
                                </span>
                            </div>
                            <div class="mt-2 text-xs text-gray-500 space-y-1">
                                <p><i class="fas fa-id-card mr-1"></i> ID: {{ $farmer->id_number ?? 'Not provided' }}</p>
                                <p><i class="fas fa-phone mr-1"></i> {{ $farmer->phone_number ?? 'No phone number' }}</p>
                                <p><i class="fas fa-map-marker-alt mr-1"></i> {{ $farmer->farm_location ?? 'No location' }}</p>
                                <p class="text-xs">Registered: {{ $farmer->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="p-8 text-center">
                            <i class="fas fa-users text-gray-300 text-4xl mb-3"></i>
                            <p class="text-gray-500">No farmers found</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="p-4 border-t">
                        {{ $farmers->links() }}
                    </div>
                </div>
            </div>

            <!-- Right Column: Verification Panel -->
            @if($selectedFarmer)
            <div class="lg:col-span-2 space-y-6">
                <!-- Farmer Profile Card -->
                <div class="bg-white rounded-xl shadow">
                    <div class="p-6 border-b">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center space-x-4">
                                <img class="h-16 w-16 rounded-full" 
                                     src="{{ $selectedFarmer->profile_photo_url }}" 
                                     alt="{{ $selectedFarmer->name }}">
                                <div>
                                    <h2 class="text-xl font-bold text-gray-800">{{ $selectedFarmer->name }}</h2>
                                    <p class="text-gray-600">{{ $selectedFarmer->email }}</p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        Registered: {{ $selectedFarmer->created_at->format('F d, Y') }}
                                    </p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($selectedFarmer->verification_status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($selectedFarmer->verification_status == 'verified') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($selectedFarmer->verification_status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Farmer Information -->
                    <div class="p-6 space-y-6">
                        <!-- ID Verification Section -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-id-card text-blue-500 mr-2"></i>
                                Identity Verification
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">National ID Number</p>
                                    <p class="font-medium">{{ $selectedFarmer->id_number ?? 'Not provided' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">ID Document</p>
                                    @if($selectedFarmer->id_document)
                                        <a href="{{ route('admin.farmers.document', $selectedFarmer->id) }}" 
                                           target="_blank" 
                                           class="text-blue-600 hover:text-blue-800 inline-flex items-center">
                                            <i class="fas fa-file-pdf mr-1"></i> View Document
                                        </a>
                                    @else
                                        <p class="text-red-500">No document uploaded</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Farm Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-tractor text-green-500 mr-2"></i>
                                Farm Details
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Farm Location</p>
                                    <p class="font-medium">{{ $selectedFarmer->farm_location ?? 'Not provided' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Farm Size</p>
                                    <p class="font-medium">{{ $selectedFarmer->farm_size ?? 'Not provided' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Farm Categories</p>
                                    @php
                                        $selectedCategories = collect($selectedFarmer->farmer_categories ?? [])
                                            ->map(fn ($key) => config("product_categories.list.{$key}", $key))
                                            ->filter()
                                            ->values();
                                    @endphp
                                    @if($selectedCategories->isNotEmpty())
                                        <div class="flex flex-wrap gap-2 mt-1">
                                            @foreach($selectedCategories as $categoryLabel)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $categoryLabel }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="font-medium">{{ $selectedFarmer->crops_grown ?? 'Not provided' }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-briefcase text-purple-500 mr-2"></i>
                                Experience and Capital
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Phone Number</p>
                                    <p class="font-medium">{{ $selectedFarmer->phone_number ?? 'Not provided' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Farming Experience</p>
                                    <p class="font-medium">
                                        {{ is_null($selectedFarmer->farming_experience_years) ? 'Not provided' : $selectedFarmer->farming_experience_years . ' years' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Capital Injected</p>
                                    <p class="font-medium">
                                        {{ is_null($selectedFarmer->capital_injected) ? 'Not provided' : 'UGX ' . number_format((float) $selectedFarmer->capital_injected, 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Verification Checklist -->
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h4 class="font-medium text-blue-900 mb-3 flex items-center">
                                <i class="fas fa-clipboard-check mr-2"></i>Verification Checklist
                            </h4>
                            <div class="space-y-2">
                                @foreach([
                                    ['key' => 'id_verified', 'label' => 'ID number matches document'],
                                    ['key' => 'document_clear', 'label' => 'Document is clear and legible'],
                                    ['key' => 'location_verified', 'label' => 'Farm location is valid'],
                                    ['key' => 'farm_verified', 'label' => 'Farm details and selected categories are reasonable'],
                                    ['key' => 'phone_verified', 'label' => 'Phone number is valid and reachable'],
                                    ['key' => 'experience_verified', 'label' => 'Farming experience is reviewed and reasonable'],
                                    ['key' => 'capital_verified', 'label' => 'Capital injected amount is reviewed and reasonable'],
                                ] as $check)
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           wire:model="verificationData.{{ $check['key'] }}"
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">{{ $check['label'] }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Verification Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Verification Notes
                            </label>
                            <textarea wire:model="verificationNotes" 
                                      rows="3" 
                                      class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="Add notes about your verification process..."></textarea>
                            <p class="text-xs text-gray-500 mt-1">These notes will be saved with the verification record.</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-between items-center pt-6 border-t">
                            @if($selectedFarmer->verification_status == 'pending')
                            <div>
                                <button wire:click="requestMoreInfo" 
                                        wire:confirm="Send request for more information to this farmer?"
                                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                                    <i class="fas fa-question-circle mr-2"></i>Request More Info
                                </button>
                            </div>
                            
                            <div class="flex space-x-3">
                                <button wire:click="toggleRejectionForm" 
                                        class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
                                    <i class="fas fa-times mr-2"></i>Reject
                                </button>
                                <button wire:click="verifyFarmer" 
                                        wire:confirm="Are you sure you want to verify this farmer?"
                                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                    <i class="fas fa-check mr-2"></i>Verify Farmer
                                </button>
                            </div>
                            @endif
                        </div>

                        <!-- Rejection Form -->
                        @if($showRejectionForm)
                        <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <h4 class="font-medium text-red-800 mb-2">Rejection Reason</h4>
                            <textarea wire:model="rejectionReason" 
                                      rows="3" 
                                      class="w-full rounded-lg border-red-300 focus:border-red-500 focus:ring-red-500"
                                      placeholder="Provide a clear reason for rejection..."></textarea>
                            <div class="mt-3 flex justify-end space-x-2">
                                <button wire:click="toggleRejectionForm" 
                                        class="px-3 py-1 text-sm text-gray-600 hover:text-gray-800">
                                    Cancel
                                </button>
                                <button wire:click="rejectFarmer" 
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                    Confirm Rejection
                                </button>
                            </div>
                        </div>
                        @endif

                        <!-- Previous Verification Notes -->
                        @if($selectedFarmer->verification_notes && $selectedFarmer->verification_status != 'pending')
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-2">
                                @if($selectedFarmer->verification_status == 'verified')
                                    <i class="fas fa-check-circle text-green-500 mr-1"></i> Verification Notes
                                @else
                                    <i class="fas fa-times-circle text-red-500 mr-1"></i> Rejection Reason
                                @endif
                            </h4>
                            <p class="text-sm text-gray-600">{{ $selectedFarmer->verification_notes }}</p>
                            @if($selectedFarmer->verified_at)
                            <p class="text-xs text-gray-500 mt-2">
                                Verified by Admin • {{ $selectedFarmer->verified_at->format('M d, Y H:i') }}
                            </p>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Verification Guidelines -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <h4 class="font-medium text-yellow-800 mb-2 flex items-center">
                        <i class="fas fa-lightbulb mr-2"></i>Verification Guidelines
                    </h4>
                    <ul class="text-sm text-yellow-700 space-y-1">
                        <li>- Always verify ID document is clear and matches the ID number</li>
                        <li>- Farm location should be a real, plausible farming area</li>
                        <li>- Farm size should be reasonable for selected categories</li>
                        <li>- Confirm phone number is reachable and belongs to the applicant</li>
                        <li>- Review farming experience and capital for consistency with farm details</li>
                        <li>- Consider calling the farmer for additional verification if needed</li>
                        <li>- Document all verification steps in the notes</li>
                    </ul>
                </div>
            </div>
            @else
            <!-- Empty State -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow p-12 text-center">
                    <i class="fas fa-users text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-700 mb-2">No Farmer Selected</h3>
                    <p class="text-gray-500 mb-6">Select a farmer from the list to review their application</p>
                    @if($stats['pending'] == 0)
                    <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-lg">
                        <i class="fas fa-check-circle mr-2"></i>
                        All farmers have been reviewed!
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

    </div>
</div>
