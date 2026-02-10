<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page</title>
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <!-- Your content here -->

    <footer id="contact" class="w-full bg-[#e6f4e6] text-gray-800 py-12 px-6 mt-12 border-t border-gray-300">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
                <!-- Branding -->
                <div>
                    <h5 class="font-bold text-lg text-green-800">NakawaFarm</h5>
                    <p class="mt-2 text-sm">
                        Connecting small-scale farmers to buyers in Nakawa and across Kampala.<br>
                        We charge a 5% platform fee per transaction.
                    </p>
                </div>

                <!-- Navigation Links -->
                <div>
                    <h6 class="uppercase font-semibold text-sm text-green-800 mb-3">Links</h6>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-green-600 hover:underline transition">Marketplace</a></li>
                        <li><a href="#" class="hover:text-green-600 hover:underline transition">Farmers</a></li>
                        <li><a href="#how-it-works" class="hover:text-green-600 hover:underline transition">How it works</a></li>
                        <li><a href="#about" class="hover:text-green-600 hover:underline transition">About</a></li>
                    </ul>
                </div>

                <!-- Contact & Social -->
                <div>
                    <h6 class="uppercase font-semibold text-sm text-green-800 mb-3">Contact</h6>
                    <p class="text-sm mb-1">fm@farmmarket.com</p>
                    <p class="text-sm">+256 740 445281</p>
                    <div class="flex space-x-4 mt-4">
    <a href="#" class="bg-blue-600 text-white rounded-full p-3 hover:bg-blue-700 transition w-10 h-10 flex items-center justify-center">
        <i class="bi bi-facebook"></i>
    </a>
    <a href="#" class="bg-pink-500 text-white rounded-full p-3 hover:bg-pink-600 transition w-10 h-10 flex items-center justify-center">
        <i class="bi bi-instagram"></i>
    </a>
    <a href="#" class="bg-green-500 text-white rounded-full p-3 hover:bg-green-600 transition w-10 h-10 flex items-center justify-center">
        <i class="bi bi-whatsapp"></i>
    </a>
</div>
                </div>
            </div>

            <!-- Newsletter Signup -->
            <div class="max-w-xl mx-auto text-center mb-10">
                <h6 class="mb-3 font-semibold text-green-800">Subscribe to Our Newsletter</h6>
                <form class="flex flex-col sm:flex-row justify-center gap-2">
                    <input type="email" class="w-full sm:w-auto flex-1 px-3 py-2 rounded border border-green-600 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter your email" required />
                    <button type="submit"
                            class="px-5 py-2 rounded font-semibold 
                                   bg-green-600 text-white 
                                   hover:bg-green-700 
                                   focus:outline-none focus:ring-2 focus:ring-green-500 transition">
                        Subscribe
                    </button>
                </form>
            </div>

            <hr class="border-gray-400 mb-4" />
            <div class="text-center text-sm text-gray-600">
                <small>&copy; 2025 FarmMarket — All rights reserved</small>
            </div>
        </div>
    </footer>
</body>
</html>