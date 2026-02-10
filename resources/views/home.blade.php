<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FarmMarket</title>
  <link href="{{ asset('vendor/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.css') }}" rel="stylesheet" />
  <style>
    html { scroll-behavior: smooth; }
    body { font-family: "Figtree", "Segoe UI", system-ui, -apple-system, sans-serif; background-color: #f8f9fa; }
    .hero-content {
      height: 400px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      color: white;
    }
    .hero-content h1 { font-size: 3rem; font-weight: bold; }
    .hero-content p { font-size: 1.2rem; }
    .how-it-works-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
    }
    .how-it-works-card:hover {
      transform: scale(1.05);
      box-shadow: 0 0 15px rgba(0,0,0,0.2);
    }
    .footer {
      background-color: #343a40;
      color: white;
      padding: 40px 20px;
    }
    .footer a {
      color: #ccc;
      text-decoration: none;
      transition: color 0.3s ease;
    }
    .footer a:hover { color: #fff; }
    .social-icon {
      font-size: 1.5rem;
      margin-right: 15px;
      transition: transform 0.3s ease;
    }
    .social-icon.facebook { color: #1877F2; }
    .social-icon.instagram { color: #E1306C; }
    .social-icon.whatsapp { color: #25D366; }
    .social-icon:hover { transform: scale(1.2); }
    .newsletter-input { max-width: 300px; }
  </style>
</head>
<body>

<!-- Navbar -->
 <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" style="position: relative">
<nav class="navbar navbar-expand-lg navbar-dark position-absolute w-100" style="top: 0; z-index: 10; background: rgba(0,0,0,0.3);">
  <div class="container">
    <a class="navbar-brand fw-bold text-white" href="{{ route('home') }}">
          <img src="{{ asset('images/logo.png') }}" 
           alt="FarmMarket" 
           class="me-2" 
           style="height: 30px; width: auto;">FarmMarket
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link text-white" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#">Marketplace</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#about">About</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#how-it-works">How It Works</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#contact">Contact</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="{{ route('login') }}">Login</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="{{ route('register') }}">Register</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Carousel with Hero Content -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" style="position: relative;">
  <div class="carousel-inner">
    <div class="carousel-item active" style="background: url('images/vegetables.jpeg') center/cover no-repeat; height: 500px;"></div>
    <div class="carousel-item" style="background: url('images/second.jpg') center/cover no-repeat; height: 500px;"></div>
    <div class="carousel-item" style="background: url('images/third.jpg') center/cover no-repeat; height: 500px;"></div>
    <div class="carousel-item" style="background: url('images/fruits.jpg') center/cover no-repeat; height: 500px;"></div>
    <div class="carousel-item" style="background: url('images/legumes.jpg') center/cover no-repeat; height: 500px;"></div>
    <div class="carousel-item" style="background: url('images/spices.jpeg') center/cover no-repeat; height: 500px;"></div>
  </div>

  <!-- Hero Content Overlay -->
  <div class="carousel-caption d-flex flex-column justify-content-center align-items-center text-center" style="top: 0; bottom: 0;">
    <h1 class="text-white fw-bold mb-3" style="font-size: 2.5rem;">Buy and Sell Agriculture Produce Online</h1>
    <p class="text-white mb-4" style="font-size: 1.2rem;">Connecting farmers and buyers with fair prices and secure payments</p>
    <div>
      <a href="{{ route('register.farmer') }}" class="btn btn-success btn-lg me-2">Start Selling</a>
      <a href="{{route('marketplace')}}" class="btn btn-outline-light btn-lg">Browse Marketplace</a>
    </div>
  </div>
</div>
 </div>

  <!-- Market Highlights -->
 <section class="container my-5 text-center">
  <div style="background-color: #e6f4e6; padding: 30px; border-radius: 12px;">
    <div class="row g-3">
      <div class="col-md-3">
        <div style=" border-radius: 8px; padding: 20px;">
          <i class="bi bi-sunrise fs-2 text-success"></i>
          <h4 class="mt-2">Farm‑Fresh Daily</h4>
          <p>New listings added every morning</p>
        </div>
      </div>
      <div class="col-md-3">
        <div style="border-radius: 8px; padding: 20px;">
          <i class="bi bi-people fs-2 text-primary"></i>
          <h4 class="mt-2">Direct From Farmers</h4>
          <p>No middlemen, fair pricing</p>
        </div>
      </div>
      <div class="col-md-3">
        <div style=" border-radius: 8px; padding: 20px;">
          <i class="bi bi-phone fs-2 text-success"></i>
          <h4 class="mt-2">Mobile Money Ready</h4>
          <p>Pay using MTN or Airtel</p>
        </div>
      </div>
      <div class="col-md-3">
        <div style="border-radius: 8px; padding: 20px;">
          <i class="bi bi-shield-check fs-2 text-warning"></i>
          <h4 class="mt-2">Trusted Quality</h4>
          <p>Verified farmers and clear profiles</p>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- About Section -->
<section id="about" style=" padding: 80px 0; margin-bottom: 140px;">
  <div class="container">
    <div class="row align-items-center gy-5 gx-5">
      
      <!-- Left side (images) -->
      <div class="col-md-6 text-center">
        <div style="position: relative; display: inline-block;">
          <img src="images/farmers.jpg" alt="Farmers smiling"
               style="width: 100%; border-radius: 20px; box-shadow: 0 8px 20px rgba(0,0,0,0.15);">
          <img src="images/farmer.jpg" alt="Harvested crops"
               style="position: absolute; bottom: -130px; right: -70px; width: 65%; border-radius: 20px; border: 5px solid #fff; box-shadow: 0 8px 20px rgba(0,0,0,0.15);">
        </div>
      </div>

      <!-- Right side (text) -->
      <div class="col-md-6">
        <div style="margin-left: 30px; margin-right: -40px;">

        <small style="text-transform: uppercase; color: #198754; font-weight: bold;">About</small>
        <h2 style="margin-top: 10px; font-weight: bold;">Welcome to FarmMarket</h2>
        <h5 style="margin-bottom: 20px;">Empowering Farmers Across Kampala</h5>
        <p style="color: #555; font-size: 1.1rem;">
          FarmMarket is a free platform that empowers farmers to sell their produce directly to buyers by eliminating middlemen and ensuring better profits.
        </p>
        <p style="color: #555; font-size: 1.1rem;">
          We connect farmers across Kampala and beyond, helping them maximize earnings by selling locally or wherever they get the best price.
        </p>
        <div style="text-align: center; margin-top: 30px;">
          <a href="#" class="btn btn-success px-4 py-2 ">Get Started</a>
        </div>
      </div>
    </div>

    </div>
  </div>
</section>

  
  <!-- Buy & Sell Categories -->
    <section class="my-5">
        <div class="container" style="background-color: #e6f4e6; padding: 32px 24px; border-radius: 12px;">
            <h2 class="text-center mb-3">Buy and Sell Categories</h2>
            <p class="text-center text-muted mb-3">Popular items from local farmers</p>
            <div class="row g-3 justify-content-center">
                <div class="col-6 col-md-3 text-center">
                    <div class="mx-auto rounded-circle overflow-hidden shadow" style="width: 140px; height: 140px;">
                        <img src="images/fruits.jpg" alt="Fruits" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h6 class="mt-3">Fruits</h6>
                    <small class="text-muted">Seasonal picks</small>
                </div>
                <div class="col-6 col-md-3 text-center">
                    <div class="mx-auto rounded-circle overflow-hidden shadow" style="width: 140px; height: 140px;">
                        <img src="images/vegetables.jpeg" alt="Vegetables" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h6 class="mt-3">Vegetables</h6>
                    <small class="text-muted">Fresh daily</small>
                </div>
                <div class="col-6 col-md-3 text-center">
                    <div class="mx-auto rounded-circle overflow-hidden shadow" style="width: 140px; height: 140px;">
                        <img src="images/legumes.jpg" alt="Legumes" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h6 class="mt-3">Legumes</h6>
                    <small class="text-muted">Protein rich</small>
                </div>
                <div class="col-6 col-md-3 text-center">
                    <div class="mx-auto rounded-circle overflow-hidden shadow" style="width: 140px; height: 140px;">
                        <img src="images/plantain.jpg" alt="Plantain" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h6 class="mt-3">Plantain</h6>
                    <small class="text-muted">Customer favorite</small>
                </div>
                <div class="col-6 col-md-3 text-center">
                    <div class="mx-auto rounded-circle overflow-hidden shadow" style="width: 140px; height: 140px;">
                        <img src="images/potatoes.jpg" alt="Potatoes" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h6 class="mt-3">Potatoes</h6>
                    <small class="text-muted">Best sellers</small>
                </div>
                <div class="col-6 col-md-3 text-center">
                    <div class="mx-auto rounded-circle overflow-hidden shadow" style="width: 140px; height: 140px;">
                        <img src="images/tubers.jpg" alt="Tubers" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h6 class="mt-3">Tubers</h6>
                    <small class="text-muted">Staple foods</small>
                </div>
                <div class="col-6 col-md-3 text-center">
                    <div class="mx-auto rounded-circle overflow-hidden shadow" style="width: 140px; height: 140px;">
                        <img src="images/dairy.jpg" alt="Dairy" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h6 class="mt-3">Dairy</h6>
                    <small class="text-muted">Fresh milk & more</small>
                </div>
                <div class="col-6 col-md-3 text-center">
                    <div class="mx-auto rounded-circle overflow-hidden shadow" style="width: 140px; height: 140px;">
                        <img src="images/spices.jpeg" alt="Spices" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h6 class="mt-3">Spices</h6>
                    <small class="text-muted">Aromatic blends</small>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="#" class="btn btn-outline-success">Explore more</a>
            </div>
        </div>
    </section>

  <!-- How It Works -->
  <section id="how-it-works" class="container my-6">
    <h2 class="text-center mb-4">How It Works</h2>
    <div class="row text-center">
      <div class="col-md-4 mb-4">
        <div class="card how-it-works-card p-4">
          <i class="bi bi-person-check fs-1 text-success"></i>
          <h5 class="mt-3">Create Your Account</h5>
          <p>Sign up as a buyer or farmer to start using the platform.</p>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card how-it-works-card p-4">
          <i class="bi bi-basket fs-1 text-primary"></i>
          <h5 class="mt-3">Browse & Add to Cart</h5>
          <p>Buyers explore listings, compare prices, and add items.</p>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card how-it-works-card p-4">
          <i class="bi bi-phone fs-1 text-warning"></i>
          <h5 class="mt-3">Pay by Mobile Money</h5>
          <p>Checkout securely with MTN or Airtel mobile money.</p>
        </div>
      </div>
    </div>

       <div class="row text-center">
      <div class="col-md-4 mb-4">
        <div class="card how-it-works-card p-4">
          <i class="bi bi-shop fs-1 text-success"></i>
          <h5 class="mt-3">Farmers Fulfill Orders</h5>
          <p>Farmers confirm, prepare, and update order status.</p>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card how-it-works-card p-4">
          <i class="bi bi-truck fs-1 text-primary"></i>
          <h5 class="mt-3">Delivery & Tracking</h5>
          <p>Buyers track orders until they are completed.</p>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card how-it-works-card p-4">
          <i class="bi bi-check-circle fs-1 text-warning"></i>
          <h5 class="mt-3">Complete & Review</h5>
          <p>Orders close once completed, and buyers leave reviews.</p>
        </div>
      </div>
    </div>
  </section>

<!-- Footer -->
    <footer id="contact" class="footer mt-5" style="background-color: #e6f4e6; padding: 40px 20px; color: #333;">
        <div class="container">
            <div class="row">
            <!-- Branding -->
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold">FarmMarket</h5>
                <p>Connecting small-scale farmers to buyers in Nakawa and across Kampala.<br>
                We charge a 5% platform fee per transaction.</p>
            </div>

            <!-- Navigation Links -->
            <div class="col-md-4 mb-4">
                <h6 class="text-uppercase">Links</h6>
                <ul class="list-unstyled">
                <li><a href="#" class="footer-link">Marketplace</a></li>
                <li><a href="#" class="footer-link">Farmers</a></li>
                <li><a href="#how-it-works" class="footer-link">How it works</a></li>
                <li><a href="#about" class="footer-link">About</a></li>
                </ul>
            </div>

            <!-- Contact & Social -->
            <div class="col-md-4 mb-4">
                <h6 class="text-uppercase">Contact</h6>
                <p class="mb-1">fm@farmmarket.example</p>
                <p>+256 700 000000</p>
                <div style="margin-top:15px; display:flex;">
                <a href="#" class="social-icon facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="social-icon instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="social-icon whatsapp"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>
            </div>

            <!-- Newsletter Signup -->
            <div class="row mt-4">
            <div class="col-md-6 mx-auto text-center">
                <h6 class="mb-3">Subscribe to Our Newsletter</h6>
                <form class="d-flex justify-content-center">
                <input type="email" class="form-control newsletter-input me-2" placeholder="Enter your email" required />
                <button type="submit" class="btn btn-success">Subscribe</button>
                </form>
            </div>
            </div>

            <hr class="border-light mt-4" />
            <div class="text-center">
            <small>&copy; 2025 FarmMarket All rights reserved</small>
            </div>
        </div>
    </footer>

    <script src="{{ asset('vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
