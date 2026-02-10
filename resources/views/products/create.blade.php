@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">List a New Product</h1>
        <p class="text-gray-600 mt-2">Fill in the details below to add your product to the marketplace</p>
    </div>

    <!-- Progress Steps -->
    <div class="mb-10">
        <div class="flex items-center justify-between relative mb-4">
            <!-- Background Line -->
            <div class="absolute top-1/2 left-0 right-0 h-1 bg-gray-200 transform -translate-y-1/2"></div>
            <!-- Progress Fill -->
            <div id="progressFill" class="absolute top-1/2 left-0 h-1 bg-green-600 transform -translate-y-1/2 transition-all duration-300" style="width: 0%"></div>
            
            <!-- Steps -->
            <div id="step1" class="w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center text-sm font-medium relative z-10 cursor-pointer" onclick="showStep(1)">1</div>
            <div id="step2" class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-medium relative z-10 cursor-pointer" onclick="showStep(2)">2</div>
            <div id="step3" class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-medium relative z-10 cursor-pointer" onclick="showStep(3)">3</div>
        </div>
        
        <!-- Step Labels -->
        <div class="flex justify-between px-2">
            <span id="label1" class="text-sm text-green-600 font-medium cursor-pointer" onclick="showStep(1)">Product Details</span>
            <span id="label2" class="text-sm text-gray-500 cursor-pointer" onclick="showStep(2)">Pricing & Inventory</span>
            <span id="label3" class="text-sm text-gray-500 cursor-pointer" onclick="showStep(3)">Review & Publish</span>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Step 1: Product Details -->
            <div id="step1-content" class="step-content">
                <!-- Product Images -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Product Images</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Main Image -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Main Image</label>
                            <label class="border-2 border-dashed border-gray-300 rounded-lg h-48 flex flex-col items-center justify-center cursor-pointer hover:border-green-500 transition relative overflow-hidden">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-500">Click to upload main image</p>
                                <p class="text-xs text-gray-400 mt-1">Recommended: 800x800px</p>
                                <input type="file" name="main_image" accept="image/*" class="hidden" onchange="previewImage(event, 'mainPreview', 'mainUploadText')" />
                                <img id="mainPreview" class="absolute inset-0 w-full h-full object-cover rounded-lg hidden" />
                                <div id="mainUploadText" class="absolute inset-0 flex flex-col items-center justify-center bg-white bg-opacity-90"></div>
                            </label>
                        </div>
                        
                        <!-- Additional Images -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Images</label>
                            <div class="grid grid-cols-2 gap-3">
                                @for($i = 0; $i < 4; $i++)
                                    <label class="border-2 border-dashed border-gray-300 rounded-lg h-20 flex items-center justify-center cursor-pointer hover:border-green-500 transition relative overflow-hidden">
                                        <i class="fas fa-plus text-gray-400"></i>
                                        <input type="file" name="additional_images[]" accept="image/*" class="hidden" onchange="previewImage(event, 'preview{{$i}}', 'uploadText{{$i}}')" />
                                        <img id="preview{{$i}}" class="absolute inset-0 w-full h-full object-cover rounded-lg hidden" />
                                        <div id="uploadText{{$i}}" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-90">
                                            <i class="fas fa-plus text-gray-400"></i>
                                        </div>
                                    </label>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Product Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                            <input type="text" name="name" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., Fresh Organic Tomatoes" required>
                        </div>

                        <!-- Product Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Describe your product in detail..."></textarea>
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category" id="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Select a category</option>
                                <option value="vegetables">Vegetables</option>
                                <option value="fruits">Fruits</option>
                                <option value="grains">Grains & Cereals</option>
                                <option value="dairy">Dairy Products</option>
                                <option value="poultry">Poultry & Eggs</option>
                                <option value="herbs">Herbs & Spices</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- Unit -->
                        <div>
                            <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                            <select name="unit" id="unit" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="kg">Kilogram (kg)</option>
                                <option value="g">Gram (g)</option>
                                <option value="lb">Pound (lb)</option>
                                <option value="piece">Piece</option>
                                <option value="bunch">Bunch</option>
                                <option value="dozen">Dozen</option>
                                <option value="liter">Liter</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex justify-end pt-6 border-t border-gray-200">
                     <a href="{{ route('farmer.listings') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        ← Back to Listings
                    </a>
                    <button type="button" onclick="showStep(2)" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center">
                        Next: Pricing & Inventory
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- Step 2: Pricing & Inventory -->
            <div id="step2-content" class="step-content hidden">
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Pricing & Inventory</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (UGX)</label>
                            <input type="number" name="price" id="price" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., 5000" required>
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                            <input type="number" name="quantity" id="quantity" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., 100" required>
                        </div>
                    </div>
                </div>

                <!-- Product Attributes -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Product Attributes</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Farming Method -->
                        <div>
                            <label for="farming_method" class="block text-sm font-medium text-gray-700 mb-1">Farming Method</label>
                            <select name="farming_method" id="farming_method" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Select method</option>
                                <option value="organic">Organic</option>
                                <option value="conventional">Conventional</option>
                                <option value="hydroponic">Hydroponic</option>
                                <option value="greenhouse">Greenhouse</option>
                            </select>
                        </div>

                        <!-- Harvest Date -->
                        <div>
                            <label for="harvest_date" class="block text-sm font-medium text-gray-700 mb-1">Harvest Date</label>
                            <input type="date" name="harvest_date" id="harvest_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <!-- Certifications -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Certifications</label>
                            <div class="flex flex-wrap gap-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="certifications[]" value="organic" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                    <span class="ml-2 text-sm text-gray-700">Organic Certified</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="certifications[]" value="local" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                    <span class="ml-2 text-sm text-gray-700">Locally Grown</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="certifications[]" value="pesticide_free" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                    <span class="ml-2 text-sm text-gray-700">Pesticide Free</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex justify-between pt-6 border-t border-gray-200">
                    <button type="button" onclick="showStep(1)" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </button>
                    <button type="button" onclick="showStep(3)" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center">
                        Next: Review & Publish
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- Step 3: Review & Publish -->
            <div id="step3-content" class="step-content hidden">
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Review Your Product</h2>
                    
                    <!-- Product Summary -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Product Summary</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Product Name</p>
                                <p id="review-name" class="font-medium">-</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Category</p>
                                <p id="review-category" class="font-medium">-</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Price</p>
                                <p id="review-price" class="font-medium">-</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Quantity</p>
                                <p id="review-quantity" class="font-medium">-</p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-600">Description</p>
                                <p id="review-description" class="font-medium">-</p>
                            </div>
                        </div>
                    </div>

                    <!-- Image Preview -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Images</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div id="review-main-image" class="border-2 border-dashed border-gray-300 rounded-lg h-24 flex items-center justify-center bg-gray-50">
                                <span class="text-sm text-gray-500">No image</span>
                            </div>
                            @for($i = 0; $i < 4; $i++)
                                <div id="review-additional-{{$i}}" class="border-2 border-dashed border-gray-300 rounded-lg h-24 flex items-center justify-center bg-gray-50">
                                    <span class="text-sm text-gray-500">No image</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex justify-between pt-6 border-t border-gray-200">
                    <button type="button" onclick="showStep(2)" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </button>
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center">
                        <i class="fas fa-check mr-2"></i> Publish Product
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .step-content {
        transition: all 0.3s ease-in-out;
    }
    .required:after {
        content: " *";
        color: #ef4444;
    }
</style>

@push('scripts')
<script>
let currentStep = 1;

function showStep(step) {
    // Hide all steps
    document.querySelectorAll('.step-content').forEach(el => {
        el.classList.add('hidden');
    });
    
    // Show current step
    document.getElementById(`step${step}-content`).classList.remove('hidden');
    
    // Update progress
    updateProgress(step);
    
    // If going to review step, update review content
    if (step === 3) {
        updateReviewContent();
    }
    
    currentStep = step;
}

function updateProgress(step) {
    const progressFill = document.getElementById('progressFill');
    const progressWidth = (step - 1) * 50; // 0%, 50%, 100%
    progressFill.style.width = `${progressWidth}%`;
    
    // Update step circles and labels
    for (let i = 1; i <= 3; i++) {
        const stepCircle = document.getElementById(`step${i}`);
        const stepLabel = document.getElementById(`label${i}`);
        
        if (i <= step) {
            stepCircle.classList.remove('bg-gray-300', 'text-gray-600');
            stepCircle.classList.add('bg-green-600', 'text-white');
            stepLabel.classList.remove('text-gray-500');
            stepLabel.classList.add('text-green-600', 'font-medium');
        } else {
            stepCircle.classList.remove('bg-green-600', 'text-white');
            stepCircle.classList.add('bg-gray-300', 'text-gray-600');
            stepLabel.classList.remove('text-green-600', 'font-medium');
            stepLabel.classList.add('text-gray-500');
        }
    }
}

function previewImage(event, previewId, textId) {
    const input = event.target;
    const file = input.files[0];
    const preview = document.getElementById(previewId);
    const uploadText = document.getElementById(textId);

    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (uploadText) {
                uploadText.classList.add('hidden');
            }
        };
        reader.readAsDataURL(file);
    }
}

function updateReviewContent() {
    // Update basic info
    document.getElementById('review-name').textContent = document.getElementById('name').value || '-';
    document.getElementById('review-category').textContent = document.getElementById('category').options[document.getElementById('category').selectedIndex].text || '-';
    document.getElementById('review-price').textContent = document.getElementById('price').value ? `UGX ${Number(document.getElementById('price').value).toLocaleString()}` : '-';
    document.getElementById('review-quantity').textContent = document.getElementById('quantity').value || '-';
    document.getElementById('review-description').textContent = document.getElementById('description').value || '-';

    // Update main image preview in review
    const mainPreview = document.getElementById('mainPreview');
    const reviewMainImage = document.getElementById('review-main-image');
    if (mainPreview.src && !mainPreview.classList.contains('hidden')) {
        reviewMainImage.innerHTML = `<img src="${mainPreview.src}" class="w-full h-full object-cover rounded-lg" />`;
    }

    // Update additional images preview in review
    for (let i = 0; i < 4; i++) {
        const additionalPreview = document.getElementById(`preview${i}`);
        const reviewAdditional = document.getElementById(`review-additional-${i}`);
        if (additionalPreview.src && !additionalPreview.classList.contains('hidden')) {
            reviewAdditional.innerHTML = `<img src="${additionalPreview.src}" class="w-full h-full object-cover rounded-lg" />`;
        }
    }
}

// Initialize progress
updateProgress(1);
</script>
@endpush
@endsection