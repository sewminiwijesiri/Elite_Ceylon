<?php
$pageTitle = "About Us - Elite Ceylon Travels | Luxury Tourism Sri Lanka";
$metaDescription = "Learn more about the vision, mission, core values, and story of Elite Ceylon Travels. We curate premium, bespoke luxury travel experiences in Sri Lanka.";
$activePage = "about";
include 'includes/header.php';
?>

<!-- ==========================================
     ABOUT HERO BANNER
     ========================================== -->
<section class="about-hero" style="background-image: url('assets/images/home%203.jpg');">
    <div class="about-hero-overlay"></div>
    <div class="about-hero-title" data-aos="fade-up">
        <span class="section-subtitle text-gold">Discover Our Heritage</span>
        <h1 class="display-3 font-serif fw-bold text-light">About Us</h1>
    </div>
</section>

<!-- ==========================================
     ABOUT COMPANY STORY
     ========================================== -->
<section class="about-story-section">
    <div class="container">
        <div class="row align-items-center g-5">
            
            <!-- Left Side Image -->
            <div class="col-lg-6" data-aos="fade-right">
                <div class="about-img-frame">
                    <img src="assets/images/G4.jpeg" alt="Colonial Galle Fort Ramparts - Elite Ceylon Story Image">
                </div>
            </div>
            
            <!-- Right Side Story Content -->
            <div class="col-lg-6" data-aos="fade-left">
                <div class="ps-lg-4">
                    <div class="d-flex align-items-center mb-3">
                        <span class="section-subtitle mb-0">Our Story</span>
                        <span class="ms-3 text-gold">|</span>
                        <div class="ms-3 bg-white px-2 py-1 rounded border" style="box-shadow: 0 2px 6px rgba(0,0,0,0.05); border-color: var(--primary-gold) !important;">
                            <img src="assets/logo/logo.png" alt="Elite Ceylon Seal" style="height: 25px; object-fit: contain;">
                        </div>
                    </div>
                    <h2 class="font-serif mb-4 text-dark-navy" style="font-size: 2.5rem; line-height: 1.2;">Guardians of Sri Lankan Hospitality</h2>
                    <p class="text-muted mb-4">
                        Founded with a passion to showcase the raw, untethered beauty of Sri Lanka in a style that is unapologetically luxurious, Elite Ceylon Travels has grown into the island's most reliable bespoke concierge service. We cater to seekers of heritage, wildlife enthusiasts, beach lovers, and wellness retreaters.
                    </p>
                    <p class="text-muted mb-4">
                        Our signature lies in our customized journeys, combining private excursions, handpicked five-star accommodations, and authentic cultural exchanges. We don't just guide you; we invite you to live the soul of Ceylon.
                    </p>
                    
                    <div class="row g-4 pt-2">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-gold me-3 fs-4"></i>
                                <span class="fw-bold text-dark-navy">Bespoke Customization</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-gold me-3 fs-4"></i>
                                <span class="fw-bold text-dark-navy">Handpicked 5-Star Stays</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-gold me-3 fs-4"></i>
                                <span class="fw-bold text-dark-navy">Bilingual Local Experts</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-gold me-3 fs-4"></i>
                                <span class="fw-bold text-dark-navy">Uncompromising Security</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<!-- ==========================================
     MISSION, VISION & VALUES CARDS
     ========================================== -->
<section class="vision-mission-section">
    <div class="container">
        
        <div class="section-title-wrapper text-center mb-5" data-aos="fade-up">
            <span class="section-subtitle">Our Purpose</span>
            <h2 class="section-title text-dark-navy">Mission, Vision & Values</h2>
        </div>
        
        <div class="row g-4">
            
            <!-- Card 1: Mission -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="vmv-card">
                    <div class="vmv-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3>Our Mission</h3>
                    <p>To curate custom-made, immersive, and luxurious journeys across Sri Lanka, elevating local communities and setting high standards of personalized hospitality.</p>
                </div>
            </div>
            
            <!-- Card 2: Vision -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="vmv-card">
                    <div class="vmv-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>Our Vision</h3>
                    <p>To be recognized globally as the leading benchmark for bespoke, ethical, and authentic travel in Sri Lanka, where every guest departs with a piece of Ceylon in their heart.</p>
                </div>
            </div>
            
            <!-- Card 3: Values -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="vmv-card">
                    <div class="vmv-icon">
                        <i class="fas fa-gem"></i>
                    </div>
                    <h3>Our Core Values</h3>
                    <p>Luxurious Excellence, Deep Cultural Authenticity, Sustainable & Responsible Tourism, and Unrivalled Security and Trust in everything we plan.</p>
                </div>
            </div>
            
        </div>
        
    </div>
</section>

<!-- ==========================================
     CTA
     ========================================== -->
<section class="cta-section" style="background-image: url('assets/images/home%202.jpg');">
    <div class="cta-overlay"></div>
    <div class="container text-center cta-content">
        <div class="row justify-content-center" data-aos="zoom-in">
            <div class="col-lg-8">
                <span class="cta-subtitle">Begin Your Journey</span>
                <h2 class="cta-title">Your Private Sri Lankan Escape Awaits</h2>
                <p class="lead mb-4 text-light-50">Speak to our concierge experts and design your custom-made itinerary today.</p>
                <a href="contact.php" class="btn btn-gold">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
