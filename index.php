<?php
// Connect to database to retrieve reviews
require_once 'includes/db_connect.php';

try {
    $stmt = $pdo->query("SELECT * FROM reviews WHERE approved = 1 ORDER BY created_at DESC LIMIT 3");
    $home_reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $home_reviews = [];
}

// Retrieve 3 featured tour packages from database
try {
    $stmt_pkg = $pdo->query("SELECT * FROM packages WHERE featured = 1 ORDER BY sort_order ASC, id ASC LIMIT 3");
    $home_packages = $stmt_pkg->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $home_packages = [];
}

// Fallback sample reviews if fewer than 3 are present in the database
$fallback_reviews = [
    [
        'author_name' => 'Sarah Jenkins',
        'author_info' => 'United Kingdom',
        'author_avatar' => 'assets/images/sarah_jenkins.jpg',
        'rating' => 5,
        'tour_package' => 'Sigiriya Fortress',
        'review_text' => 'Our family holiday with Elite Ceylon was absolutely flawless. The attention to detail, luxury stays, and the warmth of our guide made us feel completely safe and pampered. Sigiriya was breathtaking!'
    ],
    [
        'author_name' => 'John Doe',
        'author_info' => 'United States',
        'author_avatar' => 'assets/images/john_doe.jpg',
        'rating' => 5,
        'tour_package' => 'Ella Highlands',
        'review_text' => 'The Ella scenic train ride and the Yala wildlife safari exceeded all expectations. We saw three leopards! Thanks to Elite Ceylon Travels for coordinating such a majestic itinerary. Truly 5-star service!'
    ],
    [
        'author_name' => 'Michael Chen',
        'author_info' => 'Canada',
        'author_avatar' => 'assets/images/michael_chen.jpg',
        'rating' => 5,
        'tour_package' => 'Weligama Surf Retreat',
        'review_text' => 'I came for a surfing trip to Weligama and wellness in Kandy. The customized itinerary was a perfect balance of adventure and peaceful luxury. Highly recommend Elite Ceylon!'
    ]
];

$display_reviews = $home_reviews;
if (count($display_reviews) < 3) {
    foreach ($fallback_reviews as $fb) {
        if (count($display_reviews) >= 3) break;
        $display_reviews[] = $fb;
    }
}

$pageTitle = "Elite Ceylon Travels - Luxury Tour & Travel Agency in Sri Lanka";
$metaDescription = "Explore premium, bespoke tour packages for Sri Lanka beach, wildlife, adventure, and cultural tourism with Elite Ceylon Travels. Experience Sri Lanka in luxury.";
$activePage = "home";
include 'includes/header.php';
?>

<!-- ==========================================
     IMMERSIVE HERO BANNER - SPLIT INTERACTIVE SLIDER
     ========================================== -->
<section class="hero-slider-section">
    <div class="hero-slider-list">
        
        <!-- Slide 1: Sigiriya -->
        <div class="hero-slide-item active" style="background-image: url('assets/images/sigiriya.jpg');">
            <div class="hero-overlay"></div>
            <div class="hero-slide-content container">
                <div class="row align-items-center h-100">
                    <div class="col-lg-6 hero-text-panel">
                        <span class="hero-subtitle-new">Cultural Heritage</span>
                        <h2 class="hero-title-new">SIGIRIYA</h2>
                        <p class="hero-desc-new">Ascend the majestic 8th Wonder of the World. A towering ancient rock fortress surrounded by royal pleasure gardens, engineering marvels, and fresco murals.</p>
                        <div class="hero-btn-wrap-new">
                            <a href="contact.php?package=Sigiriya%20Fortress%20Expedition" class="btn btn-gold me-md-3">Reserve Tour</a>
                            <a href="packages.php" class="btn btn-outline-gold">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Slide 2: Ella -->
        <div class="hero-slide-item" style="background-image: url('assets/images/ella.jpg');">
            <div class="hero-overlay"></div>
            <div class="hero-slide-content container">
                <div class="row align-items-center h-100">
                    <div class="col-lg-6 hero-text-panel">
                        <span class="hero-subtitle-new">Mountain Retreat</span>
                        <h2 class="hero-title-new">ELLA HIGHLANDS</h2>
                        <p class="hero-desc-new">Wander through misty tea plantations, marvel at the stone engineering of the Nine Arch Bridge, and hike the panoramic ridges of Little Adam's Peak.</p>
                        <div class="hero-btn-wrap-new">
                            <a href="contact.php?package=Ella%20Highland%20Magic" class="btn btn-gold me-md-3">Reserve Tour</a>
                            <a href="packages.php" class="btn btn-outline-gold">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Slide 3: Yala -->
        <div class="hero-slide-item" style="background-image: url('assets/images/yala.jpg');">
            <div class="hero-overlay"></div>
            <div class="hero-slide-content container">
                <div class="row align-items-center h-100">
                    <div class="col-lg-6 hero-text-panel">
                        <span class="hero-subtitle-new">Wildlife Safari</span>
                        <h2 class="hero-title-new">YALA SAFARI</h2>
                        <p class="hero-desc-new">Roam the dry zone scrublands where the density of leopards is the highest on Earth. Witness majestic elephant herds, sloth bears, and exotic birds.</p>
                        <div class="hero-btn-wrap-new">
                            <a href="contact.php?package=Yala%20Leopard%20Territory" class="btn btn-gold me-md-3">Reserve Tour</a>
                            <a href="packages.php" class="btn btn-outline-gold">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Slide 4: Galle -->
        <div class="hero-slide-item" style="background-image: url('assets/images/galle.jpg?v=1.1');">
            <div class="hero-overlay"></div>
            <div class="hero-slide-content container">
                <div class="row align-items-center h-100">
                    <div class="col-lg-6 hero-text-panel">
                        <span class="hero-subtitle-new">Coastal History</span>
                        <h2 class="hero-title-new">GALLE FORT</h2>
                        <p class="hero-desc-new">Walk the ancient stone ramparts of the 17th-century Dutch fortress. A vibrant ocean city featuring colonial villas, chic boutiques, and historic lighthouses.</p>
                        <div class="hero-btn-wrap-new">
                            <a href="contact.php?package=Galle%20Fort%20Colonial%20Charm" class="btn btn-gold me-md-3">Reserve Tour</a>
                            <a href="packages.php" class="btn btn-outline-gold">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Slide 5: Mirissa -->
        <div class="hero-slide-item" style="background-image: url('assets/images/Weligama.jpg');">
            <div class="hero-overlay"></div>
            <div class="hero-slide-content container">
                <div class="row align-items-center h-100">
                    <div class="col-lg-6 hero-text-panel">
                        <span class="hero-subtitle-new">Surf & Ocean Luxury</span>
                        <h2 class="hero-title-new">MIRISSA WAVES</h2>
                        <p class="hero-desc-new">Set sail into deep blue waters to spot blue whales and dolphins, surf pristine southern swells, and relax beneath leaning palm groves.</p>
                        <div class="hero-btn-wrap-new">
                            <a href="contact.php?package=Weligama%20Waves%20%26%20Whales" class="btn btn-gold me-md-3">Reserve Tour</a>
                            <a href="packages.php" class="btn btn-outline-gold">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Floating Interactive Destination Cards on the Right (Matches Foxico inspiration) -->
    <div class="hero-thumbnails-container">
        
        <!-- Card 1: Sigiriya -->
        <div class="thumbnail-card active" data-index="0">
            <img src="assets/images/sigiriya.jpg" alt="Sigiriya Preview">
            <div class="thumbnail-info">
                <span class="thumbnail-tag">Heritage</span>
                <h5 class="thumbnail-title">Sigiriya</h5>
            </div>
        </div>
        
        <!-- Card 2: Ella -->
        <div class="thumbnail-card" data-index="1">
            <img src="assets/images/ella.jpg" alt="Ella Preview">
            <div class="thumbnail-info">
                <span class="thumbnail-tag">Highlands</span>
                <h5 class="thumbnail-title">Ella</h5>
            </div>
        </div>
        
        <!-- Card 3: Yala -->
        <div class="thumbnail-card" data-index="2">
            <img src="assets/images/yala.jpg" alt="Yala Preview">
            <div class="thumbnail-info">
                <span class="thumbnail-tag">Wildlife</span>
                <h5 class="thumbnail-title">Yala</h5>
            </div>
        </div>
        
        <!-- Card 4: Galle -->
        <div class="thumbnail-card" data-index="3">
            <img src="assets/images/galle.jpg?v=1.1" alt="Galle Preview">
            <div class="thumbnail-info">
                <span class="thumbnail-tag">Coastal</span>
                <h5 class="thumbnail-title">Galle</h5>
            </div>
        </div>
        
        <!-- Card 5: Mirissa -->
        <div class="thumbnail-card" data-index="4">
            <img src="assets/images/Weligama.jpg" alt="Mirissa Preview">
            <div class="thumbnail-info">
                <span class="thumbnail-tag">Surf</span>
                <h5 class="thumbnail-title">Mirissa</h5>
            </div>
        </div>
        
    </div>

    <!-- Navigation Arrow buttons -->
    <div class="hero-nav-controls">
        <button class="nav-control-btn prev-btn" aria-label="Previous Slide"><i class="fas fa-chevron-left"></i></button>
        <button class="nav-control-btn next-btn" aria-label="Next Slide"><i class="fas fa-chevron-right"></i></button>
    </div>
</section>

<!-- ==========================================
     WHY CHOOSE US SECTION
     ========================================== -->
<section class="why-choose-section">
    <!-- Subtle Golden Glow Decorator -->
    <div style="position: absolute; top: 10%; left: 10%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(212, 175, 55, 0.05) 0%, rgba(212, 175, 55, 0) 70%); border-radius: 50%; filter: blur(70px); z-index: 1; pointer-events: none;"></div>

    <div class="container text-center" style="position: relative; z-index: 2;">
        
        <div class="section-title-wrapper" data-aos="fade-up">
            <span class="section-subtitle">The Art of Travel</span>
            <h2 class="section-title text-dark-navy">Why Choose Elite Ceylon</h2>
        </div>
        
        <div class="row g-4">
            
            <!-- Card 1: Curated Masterpieces -->
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="why-premium-card" style="background-image: url('assets/images/sigiriya.jpg');">
                    <div class="why-premium-overlay"></div>
                    <div class="why-premium-content">
                        <div class="why-premium-icon">
                            <i class="fas fa-gem"></i>
                        </div>
                        <h3>Bespoke Luxury</h3>
                        <p>Curated luxury travel options designed to optimize value without compromising an ounce of comfort.</p>
                    </div>
                </div>
            </div>
            
            <!-- Card 2: Trusted Service -->
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="why-premium-card" style="background-image: url('assets/images/Weligama.jpg');">
                    <div class="why-premium-overlay"></div>
                    <div class="why-premium-content">
                        <div class="why-premium-icon">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <h3>Elite Hospitality</h3>
                        <p>Five-star hospitality, seamless logistics, and premium security standards to protect your peace of mind.</p>
                    </div>
                </div>
            </div>
            
            <!-- Card 3: Experienced Guides -->
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="why-premium-card" style="background-image: url('assets/images/ella.jpg');">
                    <div class="why-premium-overlay"></div>
                    <div class="why-premium-content">
                        <div class="why-premium-icon">
                            <i class="fas fa-compass"></i>
                        </div>
                        <h3>Heritage Guides</h3>
                        <p>Passionate island native experts who reveal hidden sights, sacred tales, and authentic culture.</p>
                    </div>
                </div>
            </div>
            
            <!-- Card 4: 24/7 Customer Support -->
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="why-premium-card" style="background-image: url('assets/images/yala.jpg');">
                    <div class="why-premium-overlay"></div>
                    <div class="why-premium-content">
                        <div class="why-premium-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3>24/7 Concierge</h3>
                        <p>A dedicated concierge team is at your beck and call, checking in at every stage of your path.</p>
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
</section>

<!-- ==========================================
     FEATURED TOUR PACKAGES (FROM DATABASE)
     ========================================== -->
<section id="packages" class="packages-grid-container" style="background-color: var(--cream-light); padding: 5rem 0 6rem 0;">
    <div class="container">
        
        <div class="section-title-wrapper text-center mb-5" data-aos="fade-up">
            <span class="section-subtitle"><i class="fas fa-route text-gold me-2"></i>Curated Itineraries</span>
            <h2 class="section-title" style="color: var(--dark-navy);">Popular Tour Packages</h2>
            <p class="lead text-muted mx-auto" style="max-width: 680px; font-size: 1.05rem;">Handcrafted luxury journeys across the most enchanting landmarks, wildlife sanctuaries, and coastal heavens of Sri Lanka.</p>
        </div>

        <div class="premium-package-grid" data-aos="fade-up" data-aos-delay="100">
            <?php foreach ($home_packages as $pkg): ?>
            <div class="premium-card" onclick="window.location.href='package_details.php?id=<?php echo $pkg['id']; ?>'">
                <img src="<?php echo htmlspecialchars($pkg['image']); ?>" alt="<?php echo htmlspecialchars($pkg['title']); ?>" loading="lazy">
                <div class="premium-card-content">
                    <span class="premium-card-tag"><?php echo htmlspecialchars($pkg['duration']); ?></span>
                    <h3 class="premium-card-title"><?php echo htmlspecialchars($pkg['title']); ?></h3>
                    <p class="premium-card-desc"><?php echo htmlspecialchars($pkg['description']); ?></p>
                    <div class="premium-card-footer">
                        <a href="package_details.php?id=<?php echo $pkg['id']; ?>" class="premium-card-btn">View Details</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- View All Packages CTA Button -->
        <div class="text-center mt-5" data-aos="fade-up">
            <a href="packages.php" class="btn btn-gold rounded-pill px-5 py-3 fw-bold shadow-sm">
                Explore All Packages <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>

    </div>
</section>

<!-- ==========================================
     INTERACTIVE TRAVEL MAP SECTION
     ========================================== -->
<section class="travel-map-section py-5" style="background-color: var(--cream-light); position: relative; overflow: hidden;">
    <!-- Decorative glow background -->
    <div style="position: absolute; top: 20%; right: 5%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(212, 175, 55, 0.05) 0%, rgba(212, 175, 55, 0) 70%); border-radius: 50%; filter: blur(60px); z-index: 1; pointer-events: none;"></div>

    <div class="container py-4" style="position: relative; z-index: 2;">
        
        <div class="section-title-wrapper text-center mb-5" data-aos="fade-up">
            <span class="section-subtitle"><i class="fas fa-map-marked-alt text-gold me-2"></i>Bespoke Routes</span>
            <h2 class="section-title text-dark-navy">Explore Sri Lanka</h2>
            <p class="lead text-muted mx-auto" style="max-width: 700px; font-size: 1.05rem;">Follow our curated luxury paths to discover the heart of Ceylon. From pristine coastlines and misty mountains to sacred ancient cities.</p>
        </div>

        <div class="row align-items-center g-5">
            
            <!-- Left Column: Map Image -->
            <div class="col-lg-6" data-aos="fade-right">
                <div class="map-visual-wrapper position-relative" style="cursor: zoom-in;" data-bs-toggle="modal" data-bs-target="#mapLightboxModal">
                    <div class="map-glow-effect"></div>
                    <img src="assets/images/luxury_travel_map.jpg?v=1.2" alt="Ceylon Travel Route Map" class="img-fluid rounded-4 shadow-lg border-gold-premium" style="width: 100%; transition: var(--transition-smooth); border: 2px solid rgba(212, 175, 55, 0.25); filter: contrast(1.08) brightness(1.02); image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;">
                </div>
            </div>

            <!-- Right Column: Curated Timeline Stops (No Dropdown) -->
            <div class="col-lg-6" data-aos="fade-left">
                <div class="map-routes-content">
                    <h3 class="font-serif text-dark-navy mb-4" style="font-size: 2rem; margin-bottom: 2rem !important;">Curated Sri Lanka Journeys</h3>
                    
                    <div class="custom-route-timeline">
                        
                        <!-- Stop 1: Mirissa -->
                        <div class="route-timeline-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <span class="route-number-badge me-3">01</span>
                                <h4 class="font-serif text-dark-navy mb-0" style="font-weight: 700; font-size: 1.15rem;">
                                    Mirissa & South Coast
                                    <span class="badge bg-light text-dark-navy border ms-2" style="font-size: 0.75rem; font-weight: 600; border-color: rgba(212, 175, 55, 0.3) !important; letter-spacing: 0.02em;">310 km</span>
                                </h4>
                            </div>
                            <p class="text-muted ps-5 mb-0" style="font-size: 0.88rem; line-height: 1.5;">
                                Pristine sandy beaches, whale watching, and tropical sunset surf.
                            </p>
                        </div>

                        <!-- Stop 2: Yala -->
                        <div class="route-timeline-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <span class="route-number-badge me-3">02</span>
                                <h4 class="font-serif text-dark-navy mb-0" style="font-weight: 700; font-size: 1.15rem;">
                                    Yala Safari
                                    <span class="badge bg-light text-dark-navy border ms-2" style="font-size: 0.75rem; font-weight: 600; border-color: rgba(212, 175, 55, 0.3) !important; letter-spacing: 0.02em;">240 km</span>
                                </h4>
                            </div>
                            <p class="text-muted ps-5 mb-0" style="font-size: 0.88rem; line-height: 1.5;">
                                Thrilling wildlife game drives to spot leopards and wild elephant herds.
                            </p>
                        </div>

                        <!-- Stop 3: Ella -->
                        <div class="route-timeline-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <span class="route-number-badge me-3">03</span>
                                <h4 class="font-serif text-dark-navy mb-0" style="font-weight: 700; font-size: 1.15rem;">
                                    Misty Ella
                                    <span class="badge bg-light text-dark-navy border ms-2" style="font-size: 0.75rem; font-weight: 600; border-color: rgba(212, 175, 55, 0.3) !important; letter-spacing: 0.02em;">185 km</span>
                                </h4>
                            </div>
                            <p class="text-muted ps-5 mb-0" style="font-size: 0.88rem; line-height: 1.5;">
                                Scenic hikes, cascading waterfalls, and the iconic Nine Arch Bridge.
                            </p>
                        </div>

                        <!-- Stop 4: Kandy -->
                        <div class="route-timeline-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <span class="route-number-badge me-3">04</span>
                                <h4 class="font-serif text-dark-navy mb-0" style="font-weight: 700; font-size: 1.15rem;">
                                    Kandy Kingdom
                                    <span class="badge bg-light text-dark-navy border ms-2" style="font-size: 0.75rem; font-weight: 600; border-color: rgba(212, 175, 55, 0.3) !important; letter-spacing: 0.02em;">110 km</span>
                                </h4>
                            </div>
                            <p class="text-muted ps-5 mb-0" style="font-size: 0.88rem; line-height: 1.5;">
                                The sacred Temple of the Tooth Relic and surrounding tea-draped hills.
                            </p>
                        </div>

                        <!-- Stop 5: Sigiriya -->
                        <div class="route-timeline-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <span class="route-number-badge me-3">05</span>
                                <h4 class="font-serif text-dark-navy mb-0" style="font-weight: 700; font-size: 1.15rem;">
                                    Sigiriya Rock
                                    <span class="badge bg-light text-dark-navy border ms-2" style="font-size: 0.75rem; font-weight: 600; border-color: rgba(212, 175, 55, 0.3) !important; letter-spacing: 0.02em;">74 km</span>
                                </h4>
                            </div>
                            <p class="text-muted ps-5 mb-0" style="font-size: 0.88rem; line-height: 1.5;">
                                Standing in awe before the ancient 8th-wonder royal sky fortress.
                            </p>
                        </div>

                        <!-- Stop 6: Trincomalee -->
                        <div class="route-timeline-item">
                            <div class="d-flex align-items-center mb-2">
                                <span class="route-number-badge me-3">06</span>
                                <h4 class="font-serif text-dark-navy mb-0" style="font-weight: 700; font-size: 1.15rem;">
                                    Trincomalee
                                    <span class="badge bg-light text-dark-navy border ms-2" style="font-size: 0.75rem; font-weight: 600; border-color: rgba(212, 175, 55, 0.3) !important; letter-spacing: 0.02em;">215 km</span>
                                </h4>
                            </div>
                            <p class="text-muted ps-5 mb-0" style="font-size: 0.88rem; line-height: 1.5;">
                                Unwinding along pristine white-sand shores and natural marine sanctuaries.
                            </p>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ==========================================
     MAP LIGHTBOX MODAL
     ========================================== -->
<div class="modal fade" id="mapLightboxModal" tabindex="-1" aria-labelledby="mapLightboxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 bg-transparent">
            <div class="modal-header border-0 p-0 justify-content-end">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="font-size: 1.5rem; filter: drop-shadow(0 2px 5px rgba(0,0,0,0.5)); margin-bottom: 10px;"></button>
            </div>
            <div class="modal-body p-0 text-center">
                <img src="assets/images/luxury_travel_map.jpg?v=1.2" alt="Ceylon Travel Route Map" class="img-fluid rounded-4 shadow-lg" style="max-height: 85vh; object-fit: contain; width: 100%;">
            </div>
        </div>
    </div>
</div>

<!-- ==========================================
     TRAVEL GALLERY SECTION (SWIPER SLIDER)
     ========================================== -->
<section id="gallery" class="home-gallery-section">
    <div class="container">
        
        <div class="section-title-wrapper text-center mb-4" data-aos="fade-up">
            <span class="section-subtitle"><i class="fas fa-camera-retro text-gold me-2"></i>Photo Memories</span>
            <h2 class="section-title">Explore Our Gallery</h2>
        </div>

        <!-- Swiper Gallery Slider -->
        <div class="swiper home-gallery-slider" data-aos="fade-up">
            <div class="swiper-wrapper">
                
                <!-- Slide 1: G1 (Demodara / Ella Rails) -->
                <div class="swiper-slide">
                    <div class="gallery-slide-item gallery-item" data-title="Nine Arch Demodara Rails" data-location="Ella Highlands">
                        <img src="assets/images/G1.jpeg" alt="Nine Arch Demodara Rails Walk Ella" class="img-g1" loading="lazy">
                        <div class="gallery-slide-overlay">
                            <div class="gallery-slide-icon"><i class="fas fa-expand-alt"></i></div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2: G2 (Tea Country Explorers) -->
                <div class="swiper-slide">
                    <div class="gallery-slide-item gallery-item" data-title="Highland Tea Country Explorers" data-location="Nuwara Eliya">
                        <img src="assets/images/G2.jpeg" alt="Highland Tea Country Nuwara Eliya" class="img-g2" loading="lazy">
                        <div class="gallery-slide-overlay">
                            <div class="gallery-slide-icon"><i class="fas fa-expand-alt"></i></div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3: G3 (Village Dining Experience) -->
                <div class="swiper-slide">
                    <div class="gallery-slide-item gallery-item" data-title="Authentic Village Feast" data-location="Culinary Heritage">
                        <img src="assets/images/G3.jpeg" alt="Authentic Village Dining Experience" class="img-g3" loading="lazy">
                        <div class="gallery-slide-overlay">
                            <div class="gallery-slide-icon"><i class="fas fa-expand-alt"></i></div>
                        </div>
                    </div>
                </div>

                <!-- Slide 4: G4 (King Coconut Moments) -->
                <div class="swiper-slide">
                    <div class="gallery-slide-item gallery-item" data-title="Tropical King Coconut Break" data-location="Southern Coast">
                        <img src="assets/images/G4.jpeg" alt="Tropical King Coconut Refreshment" class="img-g4" loading="lazy">
                        <div class="gallery-slide-overlay">
                            <div class="gallery-slide-icon"><i class="fas fa-expand-alt"></i></div>
                        </div>
                    </div>
                </div>

                <!-- Slide 5: G5 (Warm Ceylon Welcome) -->
                <div class="swiper-slide">
                    <div class="gallery-slide-item gallery-item" data-title="Warm Ceylon Welcome" data-location="Luxury Retreat Arrival">
                        <img src="assets/images/G5.jpeg" alt="Traditional Flower Greeting Welcome" class="img-g5" loading="lazy">
                        <div class="gallery-slide-overlay">
                            <div class="gallery-slide-icon"><i class="fas fa-expand-alt"></i></div>
                        </div>
                    </div>
                </div>

                <!-- Slide 6: G7 (Coastal Tuk-Tuk Safari) -->
                <div class="swiper-slide">
                    <div class="gallery-slide-item gallery-item" data-title="Coastal Tuk-Tuk Adventures" data-location="Mirissa & Weligama Bay">
                        <img src="assets/images/G7.jpeg" alt="Coastal Tuk-Tuk Safari Weligama" class="img-g7" loading="lazy">
                        <div class="gallery-slide-overlay">
                            <div class="gallery-slide-icon"><i class="fas fa-expand-alt"></i></div>
                        </div>
                    </div>
                </div>

                <!-- Slide 7: G8 (Historic Galle Ramparts) -->
                <div class="swiper-slide">
                    <div class="gallery-slide-item gallery-item" data-title="Historic Galle Ramparts" data-location="Colonial Galle Fort">
                        <img src="assets/images/G8.jpeg" alt="Historic Galle Fort Ramparts Walk" class="img-g8" loading="lazy">
                        <div class="gallery-slide-overlay">
                            <div class="gallery-slide-icon"><i class="fas fa-expand-alt"></i></div>
                        </div>
                    </div>
                </div>

            </div>
            
            <!-- Swiper Pagination -->
            <div class="swiper-pagination"></div>
        </div>

        <!-- View Full Gallery CTA Button -->
        <div class="text-center mt-3" data-aos="fade-up">
            <a href="gallery.php" class="btn btn-outline-gold rounded-pill px-4 py-2 fw-semibold">
                Explore Full Gallery <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>

    </div>
</section>

<!-- ==========================================
     CUSTOMER TESTIMONIALS SECTION
     ========================================== -->
<section id="reviews" class="testimonials-section">
    <div class="container">
        
        <div class="section-title-wrapper text-center" data-aos="fade-up">
            <span class="section-subtitle">Reviews</span>
            <h2 class="section-title">Voices of Adventurers</h2>
        </div>
        
        <!-- Swiper Slider -->
        <div class="swiper testimonials-slider" data-aos="fade-up" data-aos-delay="200">
            <div class="swiper-wrapper">
                
                <?php foreach ($display_reviews as $rev): ?>
                <div class="swiper-slide">
                    <div class="testimonial-card">
                        <div class="testi-badge">
                            <i class="fas fa-gem"></i> <?php echo !empty($rev['tour_package']) ? htmlspecialchars($rev['tour_package']) : 'Elite Guest'; ?>
                        </div>
                        <div class="testi-stars">
                            <?php 
                            $rating = intval($rev['rating'] ?? 5);
                            for ($s = 1; $s <= 5; $s++) {
                                echo ($s <= $rating) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                            }
                            ?>
                        </div>
                        <p class="testi-text">
                            "<?php echo htmlspecialchars($rev['review_text']); ?>"
                        </p>
                        <div class="testi-user">
                            <?php if (!empty($rev['author_avatar']) && file_exists($rev['author_avatar'])): ?>
                                <img src="<?php echo htmlspecialchars($rev['author_avatar']); ?>" alt="<?php echo htmlspecialchars($rev['author_name']); ?> Customer Review" class="testi-user-img">
                            <?php else: 
                                $initial = strtoupper(substr($rev['author_name'] ?? 'G', 0, 1));
                            ?>
                                <div class="card-author-avatar-initials me-3" style="width: 48px; height: 48px; font-size: 1.1rem; flex-shrink: 0;"><?php echo $initial; ?></div>
                            <?php endif; ?>
                            <div>
                                <h5 class="testi-user-name"><?php echo htmlspecialchars($rev['author_name']); ?></h5>
                                <span class="testi-user-country"><?php echo htmlspecialchars($rev['author_info']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                
            </div>
            
            <!-- Swiper Pagination Bullets -->
            <div class="swiper-pagination"></div>
        </div>

        <!-- View All Reviews CTA Button -->
        <div class="text-center mt-4" data-aos="fade-up">
            <a href="reviews.php" class="btn btn-outline-gold rounded-pill px-4 py-2 fw-semibold">
                Read All Reviews <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        
    </div>
</section>

<!-- ==========================================
     CALL TO ACTION (CTA)
     ========================================== -->
<section class="cta-section" style="background-image: url('assets/images/adil-murshed-uzs1IV2c1xI-unsplash.jpg');">
    <div class="cta-overlay"></div>
    <div class="container text-center cta-content">
        <div class="row justify-content-center" data-aos="zoom-in">
            <div class="col-lg-8">
                <span class="cta-subtitle">Ready For Your Next Adventure?</span>
                <h2 class="cta-title">Explore Sri Lanka's Hidden Gems In Pure Comfort</h2>
                <a href="contact.php" class="btn btn-gold">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
