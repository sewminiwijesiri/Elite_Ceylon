<?php
$pageTitle = "Luxury Tour Packages - Elite Ceylon Travels | Sri Lanka Custom Travel";
$metaDescription = "Explore premium, bespoke tour packages for Sri Lanka beach, wildlife, adventure, and cultural tourism with Elite Ceylon Travels. Experience Sri Lanka in luxury.";
$activePage = "packages";
include 'includes/header.php';
?>

<!-- ==========================================
     PACKAGES HERO BANNER
     ========================================== -->
<section class="about-hero" style="background-image: url('assets/images/adil-murshed-uzs1IV2c1xI-unsplash.jpg');">
    <div class="about-hero-overlay"></div>
    <div class="about-hero-title" data-aos="fade-up">
        <h1 class="display-3 font-serif fw-bold text-light">Travel Packages<br><span class="text-gold" style="font-style: italic;">Crafted</span> for You</h1>
        <p class="lead text-light-50 mt-3 mx-auto" style="max-width: 750px; font-size: 1.15rem; opacity: 0.85;">Discover stunning destinations, unique experiences, and custom itineraries that make your travel dreams reality.</p>
    </div>
</section>

<?php
require_once 'includes/db_connect.php';
try {
    $stmt_all_pkg = $pdo->query("SELECT * FROM packages ORDER BY sort_order ASC, id ASC");
    $all_packages = $stmt_all_pkg->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $all_packages = [];
}
?>

<!-- ==========================================
     TOUR PACKAGES GRID CONTAINER
     ========================================== -->
<section class="packages-grid-container">
    <div class="container">
        
        <div class="premium-package-grid">
            <?php foreach ($all_packages as $pkg): ?>
            <div class="premium-card" onclick="window.location.href='package_details.php?id=<?php echo $pkg['id']; ?>'">
                <img src="<?php echo htmlspecialchars($pkg['image']); ?>" alt="<?php echo htmlspecialchars($pkg['title']); ?>">
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
