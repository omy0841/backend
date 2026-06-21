@extends('layouts.app')

@section('content')
<div class="hero">
    <h1>🐟 Fresh Seafood Delivered to Your Door</h1>
    <p>Order premium samaki, pweza, ngisi, kaa, and kamba from Zanzibar's best suppliers</p>
    <div class="hero-buttons">
        @auth
            <a href="{{ route('order.create') }}" class="btn btn-primary">Place Order Now</a>
            <a href="{{ route('order.history') }}" class="btn btn-secondary">View History</a>
        @else
            <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
            <a href="{{ route('login') }}" class="btn btn-secondary">Log In</a>
        @endauth
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="section-title">
            <h2>Our Seafood Selection</h2>
            <p>Premium quality fish and seafood sourced fresh from Zanzibar waters</p>
        </div>
        <div class="product-grid">
            <div class="card product-card">
                <div class="product-image">🐟</div>
                <div class="product-info">
                    <h3 class="product-name">Samaki</h3>
                    <p class="product-desc">Whole premium fish perfect for grilling or frying. Available in various sizes for your restaurant needs.</p>
                </div>
            </div>
            <div class="card product-card">
                <div class="product-image">🐙</div>
                <div class="product-info">
                    <h3 class="product-name">Pweza</h3>
                    <p class="product-desc">Fresh octopus cleaned and ready. Perfect for soups and traditional Zanzibar dishes.</p>
                </div>
            </div>
            <div class="card product-card">
                <div class="product-image">🦐</div>
                <div class="product-info">
                    <h3 class="product-name">Ngisi</h3>
                    <p class="product-desc">Authentic local seafood with incredible flavor. Ideal for frying and coastal specialties.</p>
                </div>
            </div>
            <div class="card product-card">
                <div class="product-image">🦀</div>
                <div class="product-info">
                    <h3 class="product-name">Kaa</h3>
                    <p class="product-desc">Live or cooked crabs for stews and grilled plates. A favorite among hotel chefs.</p>
                </div>
            </div>
            <div class="card product-card">
                <div class="product-image">🦞</div>
                <div class="product-info">
                    <h3 class="product-name">Kamba</h3>
                    <p class="product-desc">Sweet lobster from the Indian Ocean. Premium choice for special dishes.</p>
                </div>
            </div>
            <div class="card product-card">
                <div class="product-image">📦</div>
                <div class="product-info">
                    <h3 class="product-name">Custom Orders</h3>
                    <p class="product-desc">Can't find what you need? Add custom seafood items or special requests.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" style="background: #f0f9ff;">
    <div class="container">
        <div class="section-title">
            <h2>How It Works</h2>
            <p>Three simple steps to get fresh seafood delivered to your hotel</p>
        </div>
        <div class="features">
            <div class="feature-box">
                <div class="feature-icon">📝</div>
                <h3>1. Register & Order</h3>
                <p>Create your account, select seafood items, and submit your order with delivery details.</p>
            </div>
            <div class="feature-box">
                <div class="feature-icon">✅</div>
                <h3>2. Admin Reviews</h3>
                <p>Our admin team verifies availability, pricing, and delivery location for your order.</p>
            </div>
            <div class="feature-box">
                <div class="feature-icon">🚚</div>
                <h3>3. Delivery</h3>
                <p>Once approved, your fresh seafood is prepared and delivered to your hotel.</p>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-title">
            <h2>Why Choose Sea Fresh Zanzibar</h2>
            <p>We bring Zanzibar's best seafood directly to your establishment</p>
        </div>
        <div class="features">
            <div class="feature-box">
                <div class="feature-icon">🌊</div>
                <h3>Fresh Daily</h3>
                <p>All seafood is sourced fresh from Zanzibar waters and delivered same-day when possible.</p>
            </div>
            <div class="feature-box">
                <div class="feature-icon">⭐</div>
                <h3>Quality Guaranteed</h3>
                <p>Every item is carefully selected and inspected to ensure premium quality for your guests.</p>
            </div>
            <div class="feature-box">
                <div class="feature-icon">🤝</div>
                <h3>Professional Service</h3>
                <p>Dedicated admin team ensures smooth ordering, approval, and fast delivery for your business.</p>
            </div>
        </div>
    </div>
</section>
@endsection
