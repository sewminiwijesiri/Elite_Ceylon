<?php
// Start session for admin check
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isAdmin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// Connect to DB
require_once 'includes/db_connect.php';

$pageTitle = "Client Reviews - Elite Ceylon Travels | Guest Testimonials";
$metaDescription = "Read authentic reviews and travel experiences from guests who explored Sri Lanka with Elite Ceylon Travels. Book your premium bespoke journey.";
$activePage = "reviews";

// Retrieve approved reviews
try {
    $stmt = $pdo->query("SELECT * FROM reviews WHERE approved = 1 ORDER BY created_at DESC");
    $db_reviews = $stmt->fetchAll();
} catch (Exception $e) {
    $db_reviews = [];
}



// Distribute approved reviews across 3 columns
$columns = [[], [], []];
foreach ($db_reviews as $index => $review) {
    $columns[$index % 3][] = $review;
}

include 'includes/header.php';
?>

<!-- ==========================================
     REVIEWS HERO BANNER
     ========================================== -->
<section class="about-hero" style="background-image: url('assets/images/yala.jpg');">
    <div class="about-hero-overlay"></div>
    <div class="about-hero-title" data-aos="fade-up">
        <span class="section-subtitle text-gold">Explorer Feedbacks</span>
        <h1 class="display-3 font-serif fw-bold text-light">Reviews from our users</h1>
    </div>
</section>

<!-- ==========================================
     REVIEWS MASONRY GRID SECTION
     ========================================== -->
<section class="reviews-page-section" style="background-color: var(--cream-light); background: radial-gradient(circle at 50% 50%, rgba(197, 168, 92, 0.05) 0%, var(--cream-light) 100%); padding: 5rem 0 8rem 0; overflow: hidden; position: relative;">
    
    <!-- Background Decorator Orbs (Matching Hero glow feel) -->
    <div style="position: absolute; top: 15%; left: 5%; width: 450px; height: 450px; background: radial-gradient(circle, rgba(197, 168, 92, 0.07) 0%, rgba(197, 168, 92, 0) 70%); border-radius: 50%; filter: blur(65px); z-index: 1; pointer-events: none;"></div>
    <div style="position: absolute; bottom: 15%; right: 5%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(197, 168, 92, 0.07) 0%, rgba(197, 168, 92, 0) 70%); border-radius: 50%; filter: blur(65px); z-index: 1; pointer-events: none;"></div>

    <div class="container" style="position: relative; z-index: 2;">
        

        
        <!-- Staggered Masonry-Style Columns -->
        <div class="reviews-masonry-grid">
            
            <?php for ($colIndex = 0; $colIndex < 3; $colIndex++): ?>
            <!-- COLUMN <?php echo $colIndex + 1; ?> -->
            <div class="reviews-column" data-aos="fade-up" data-aos-delay="<?php echo ($colIndex + 1) * 100; ?>">
                
                <?php foreach ($columns[$colIndex] as $review): ?>
                <div class="reviews-page-card">
                    <div class="card-author-header">
                        <?php if (!empty($review['author_avatar'])): ?>
                            <img src="<?php echo htmlspecialchars($review['author_avatar']); ?>" alt="<?php echo htmlspecialchars($review['author_name']); ?> Avatar" class="card-author-avatar">
                        <?php else: 
                            $initials = strtoupper(substr($review['author_name'], 0, 1));
                            ?>
                            <div class="card-author-avatar-initials"><?php echo $initials; ?></div>
                        <?php endif; ?>
                        <div class="card-author-info">
                            <h5><?php echo htmlspecialchars($review['author_name']); ?></h5>
                            <span><?php echo htmlspecialchars($review['author_info']); ?></span>
                        </div>
                    </div>
                    <div class="testi-stars">
                        <?php 
                        $rating = intval($review['rating']);
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $rating) {
                                echo '<i class="fas fa-star"></i>';
                            } else {
                                echo '<i class="far fa-star"></i>';
                            }
                        }
                        ?>
                    </div>
                    <p class="card-review-text">
                        "<?php echo htmlspecialchars($review['review_text']); ?>"
                    </p>
                    <div class="card-review-footer">
                        <span class="card-review-date"><?php echo date("g:i A · M d, Y", strtotime($review['created_at'])); ?></span>
                        <?php if (!empty($review['tour_package'])): ?>
                            <span class="card-review-badge text-gold" style="border-color: rgba(212, 175, 55, 0.35); color: var(--primary-gold) !important;"><?php echo htmlspecialchars($review['tour_package']); ?></span>
                        <?php else: ?>
                            <span class="card-review-badge">Verified Guest</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
            <?php endfor; ?>
            
        </div>
        
    </div>
</section>

<!-- ==========================================
     WRITE A REVIEW CTA SECTION
     ========================================== -->
<section class="cta-section" style="background-image: url('assets/images/home%203.jpg');">
    <div class="cta-overlay"></div>
    <div class="container text-center cta-content">
        <div class="row justify-content-center" data-aos="zoom-in">
            <div class="col-lg-8">
                <span class="cta-subtitle">Share Your Experience</span>
                <h2 class="cta-title">Have You Traveled With Us?</h2>
                <p class="lead mb-4 text-light-50">We would love to hear about your signature Ceylon journey. Your feedback helps us shape luxury travels.</p>
                <a href="contact.php?package=Custom%20Luxury%20Itinerary" class="btn btn-gold me-md-3">Book New Journey</a>
                <a href="#write-review" id="write-review-btn" class="btn btn-outline-gold">Write a Review</a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
