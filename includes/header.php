<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isAdminLogged = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// Default SEO Page Configuration variables if not explicitly defined
if (!isset($pageTitle)) {
    $pageTitle = "Elite Ceylon Travels - Luxury Tour & Travel Agency in Sri Lanka";
}
if (!isset($metaDescription)) {
    $metaDescription = "Discover the wonders of Sri Lanka with Elite Ceylon Travels. We curate premium, bespoke tour packages for luxury beach, wildlife, adventure, and cultural tourism.";
}
if (!isset($activePage)) {
    $activePage = "home";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    
    <!-- Meta Tags for Search Engine Optimization -->
    <meta name="description" content="<?php echo $metaDescription; ?>">
    <meta name="keywords" content="Sri Lanka travel, Elite Ceylon, Sri Lanka tour, luxury travel, Sri Lanka safari, Sigiriya tour, Weligama surf, Ella train tour, bespoke travels Sri Lanka">
    <meta name="author" content="Elite Ceylon Travels">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo $pageTitle; ?>">
    <meta property="og:description" content="<?php echo $metaDescription; ?>">
    <meta property="og:image" content="assets/logo/logo.png">

    <!-- Stylesheets / CDNs -->
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- AOS Animations CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Swiper.js CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <!-- Custom Style Sheet -->
    <link rel="stylesheet" href="assets/css/style.css?v=11.2">
</head>
<body class="preloader-active">

    <!-- ==========================================
         PRELOADER SCREEN
         ========================================== -->
    <div id="preloader">
        <div class="preloader-inner">
            <div class="preloader-logo-container">
                <img src="assets/logo/logo.png" alt="Elite Ceylon Travels Logo" class="preloader-logo">
                <div class="preloader-glow"></div>
            </div>
            <div class="preloader-loading-bar-wrap">
                <div class="preloader-loading-bar"></div>
            </div>
            <div class="preloader-brand-text">Elite Ceylon Travels</div>
            <div class="preloader-tagline">Luxury Tour & Travel</div>
        </div>
    </div>

    <!-- Sticky Glassmorphism Header / Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <div class="container">
            <!-- Brand Logo -->
            <a class="navbar-brand" href="index.php">
                <div class="navbar-brand-logo-wrap">
                    <img src="assets/logo/logo.png" alt="Elite Ceylon Travels Logo">
                </div>
            </a>
            
            <!-- Navbar Toggler for Mobile Screen -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="toggler-icon"></span>
                <span class="toggler-icon"></span>
                <span class="toggler-icon"></span>
            </button>
            
            <!-- Navigation Menu Links -->
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($activePage == 'home') ? 'active' : ''; ?>" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($activePage == 'packages') ? 'active' : ''; ?>" href="packages.php">Packages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($activePage == 'gallery') ? 'active' : ''; ?>" href="gallery.php">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($activePage == 'about') ? 'active' : ''; ?>" href="about.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($activePage == 'reviews') ? 'active' : ''; ?>" href="reviews.php">Reviews</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($activePage == 'contact') ? 'active' : ''; ?>" href="contact.php">Contact</a>
                    </li>
                    <?php if ($isAdminLogged): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($activePage == 'dashboard') ? 'active' : ''; ?>" href="admin_dashboard.php" style="color: var(--primary-gold) !important; font-weight: 700;">Dashboard</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-gold" href="contact.php#book-now">Book Now</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
