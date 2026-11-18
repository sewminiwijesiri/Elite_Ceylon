<?php
require_once 'includes/db_connect.php';

// Get package ID from query parameter
$package_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$package_title_param = isset($_GET['package']) ? trim($_GET['package']) : '';

$package = null;

try {
    if ($package_id > 0) {
        $stmt = $pdo->prepare("SELECT * FROM packages WHERE id = ? LIMIT 1");
        $stmt->execute([$package_id]);
        $package = $stmt->fetch(PDO::FETCH_ASSOC);
    } elseif (!empty($package_title_param)) {
        $stmt = $pdo->prepare("SELECT * FROM packages WHERE title LIKE ? LIMIT 1");
        $stmt->execute(['%' . $package_title_param . '%']);
        $package = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fallback to first package if not found
    if (!$package) {
        $stmt = $pdo->query("SELECT * FROM packages ORDER BY sort_order ASC, id ASC LIMIT 1");
        $package = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch other related packages
    $stmt_related = $pdo->prepare("SELECT * FROM packages WHERE id != ? ORDER BY sort_order ASC, id ASC LIMIT 3");
    $stmt_related->execute([$package['id'] ?? 1]);
    $related_packages = $stmt_related->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $package = null;
    $related_packages = [];
}

$pageTitle = htmlspecialchars($package['title'] ?? 'Tour Package Details') . " - Elite Ceylon Travels";
$metaDescription = htmlspecialchars($package['description'] ?? 'Discover luxury private Sri Lanka tour packages with Elite Ceylon Travels.');
$activePage = "packages";

include 'includes/header.php';

// Define coordinates for major locations in Sri Lanka
$locationCoordinates = [
    'Airport' => [7.1804, 79.8837, 'Colombo International Airport (CMB)'],
    'Colombo Airport' => [7.1804, 79.8837, 'Colombo International Airport (CMB)'],
    'Colombo' => [6.9271, 79.8612, 'Colombo City'],
    'Negombo' => [7.2089, 79.8353, 'Negombo Coast'],
    'Sigiriya' => [7.9570, 80.7603, 'Sigiriya Rock Fortress'],
    'Anuradhapura' => [8.3114, 80.4037, 'Anuradhapura Ancient City'],
    'Trincomalee' => [8.5873, 81.2152, 'Trincomalee Bay'],
    'Kandy' => [7.2906, 80.6337, 'Kandy Hill Capital'],
    'Nuwara Eliya' => [6.9497, 80.7891, 'Nuwara Eliya Tea Hills'],
    'Ella' => [6.8724, 81.0510, 'Ella Scenic Highlands'],
    'Udawalawe' => [6.4380, 80.8890, 'Udawalawe Wildlife Reserve'],
    'Yala' => [6.3687, 81.5173, 'Yala Safari Park'],
    'Mirissa' => [5.9482, 80.4573, 'Mirissa Beach Stay']
];

// Determine the tour stops based on the package ID
$tourStops = [];
$packageId = (int)($package['id'] ?? 0);

switch ($packageId) {
    case 1: // 7-Day Classic Sri Lanka Highlights Tour
        $tourStops = ['Airport', 'Sigiriya', 'Kandy', 'Nuwara Eliya', 'Ella', 'Yala', 'Mirissa'];
        break;
    case 2: // 12-Day Grand Sri Lanka Adventure
        $tourStops = ['Airport', 'Sigiriya', 'Kandy', 'Nuwara Eliya', 'Ella', 'Yala', 'Mirissa', 'Colombo'];
        break;
    case 3: // 5-Day Sri Lanka Highlights & Safari Tour
        $tourStops = ['Airport', 'Kandy', 'Nuwara Eliya', 'Ella', 'Udawalawe', 'Mirissa'];
        break;
    case 4: // 15-Day Ultimate Sri Lanka Explorer
        $tourStops = ['Airport', 'Negombo', 'Anuradhapura', 'Trincomalee', 'Sigiriya', 'Kandy', 'Nuwara Eliya', 'Ella', 'Yala', 'Colombo'];
        break;
    case 5: // 10-Day Essential Sri Lanka Highlights
        $tourStops = ['Airport', 'Sigiriya', 'Kandy', 'Nuwara Eliya', 'Ella', 'Yala', 'Mirissa'];
        break;
    default:
        // Default fallback: Airport -> Sigiriya -> Kandy -> Nuwara Eliya -> Ella -> Yala -> Mirissa
        $tourStops = ['Airport', 'Sigiriya', 'Kandy', 'Nuwara Eliya', 'Ella', 'Yala', 'Mirissa'];
        break;
}

// Generate javascript-friendly stops array
$jsStops = [];
foreach ($tourStops as $stopName) {
    if (isset($locationCoordinates[$stopName])) {
        $jsStops[] = [
            'name' => $stopName,
            'fullName' => $locationCoordinates[$stopName][2],
            'lat' => $locationCoordinates[$stopName][0],
            'lng' => $locationCoordinates[$stopName][1]
        ];
    }
}
?>

<!-- Leaflet Map CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />


<!-- ==========================================
     PACKAGE HERO HEADER BANNER
     ========================================== -->
<section class="package-details-hero" style="background-image: url('<?php echo htmlspecialchars($package['image'] ?? 'assets/images/home 3.jpg'); ?>');">
    <div class="package-details-hero-overlay"></div>
    <div class="container package-details-hero-content" data-aos="fade-up">
        
        <!-- Breadcrumb navigation -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb luxury-breadcrumb">
                <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home me-1"></i> Home</a></li>
                <li class="breadcrumb-item"><a href="packages.php">Packages</a></li>
                <li class="breadcrumb-item active text-gold" aria-current="page"><?php echo htmlspecialchars($package['title']); ?></li>
            </ol>
        </nav>

        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
            <span class="badge-pill-tag"><?php echo htmlspecialchars($package['tag'] ?? 'Best Seller'); ?></span>
            <span class="badge-pill-duration"><i class="fas fa-clock me-1"></i> <?php echo htmlspecialchars($package['duration']); ?></span>
        </div>

        <h1 class="display-4 font-serif fw-bold text-light mb-3"><?php echo htmlspecialchars($package['title']); ?></h1>
        <p class="lead text-light-50" style="max-width: 800px; font-size: 1.15rem;"><?php echo htmlspecialchars($package['description']); ?></p>

    </div>
</section>

<!-- ==========================================
     MAIN PACKAGE CONTENT & STICKY SIDEBAR
     ========================================== -->
<section class="package-details-main-section py-5" style="background-color: #fdfbf7;">
    <div class="container py-4">

        <div class="row g-5">
            
            <!-- LEFT COLUMN: FULL TOUR DETAILS & ITINERARY (8 Cols) -->
            <div class="col-lg-7" data-aos="fade-up">
                
                <!-- Quick Key Highlights Bar -->
                <div class="pkg-highlights-bar mb-5 p-4 rounded-4 shadow-sm bg-white border">
                    <div class="row g-3 text-center text-md-start">
                        <div class="col-4 col-md-4">
                            <span class="text-muted small text-uppercase fw-bold d-block">Duration</span>
                            <h6 class="fw-bold text-dark mb-0 mt-1"><i class="fas fa-calendar-days text-gold me-1"></i> <?php echo htmlspecialchars($package['duration']); ?></h6>
                        </div>
                        <div class="col-4 col-md-4">
                            <span class="text-muted small text-uppercase fw-bold d-block">Tour Type</span>
                            <h6 class="fw-bold text-dark mb-0 mt-1"><i class="fas fa-gem text-gold me-1"></i> Private Bespoke</h6>
                        </div>
                        <div class="col-4 col-md-4">
                            <span class="text-muted small text-uppercase fw-bold d-block">Availability</span>
                            <h6 class="fw-bold text-dark mb-0 mt-1"><i class="fas fa-circle-check text-success me-1"></i> Year-Round</h6>
                        </div>
                    </div>
                </div>

                <!-- Section: Comprehensive Tour Overview -->
                <div class="pkg-content-block mb-5">
                    <h3 class="font-serif fw-bold text-dark mb-3">
                        <i class="fas fa-compass text-gold me-2"></i> Journey Overview
                    </h3>
                    <p class="text-secondary lead" style="font-size: 1.05rem; line-height: 1.8;">
                        <?php echo nl2br(htmlspecialchars($package['description'])); ?>
                    </p>
                    <p class="text-secondary" style="line-height: 1.8;">
                        Every moment is meticulously planned to provide unmatched comfort, private chauffeur navigation in luxury air-conditioned vehicles, handpicked premium boutique accommodations, authentic local gastronomy, and immersive cultural access guided by island specialists.
                    </p>
                </div>

                <!-- Section: Day-by-Day Itinerary -->
                <?php
                // Parse full itinerary
                $raw_itinerary = $package['full_itinerary'] ?? '';
                $parsed_itinerary = [];
                if (!empty(trim($raw_itinerary))) {
                    $blocks = preg_split('/(?=^Day\s+\d+)/m', trim($raw_itinerary), -1, PREG_SPLIT_NO_EMPTY);
                    foreach ($blocks as $block) {
                        $lines = array_values(array_filter(array_map('trim', explode("\n", $block)), 'strlen'));
                        if (empty($lines)) continue;
                        $dayLabel = $lines[0];
                        $title = isset($lines[1]) ? $lines[1] : '';
                        $desc = (count($lines) > 2) ? implode(" ", array_slice($lines, 2)) : '';
                        $parsed_itinerary[] = [
                            'day' => $dayLabel,
                            'title' => $title,
                            'desc' => $desc
                        ];
                    }
                }
                ?>

                <?php if (!empty($parsed_itinerary)): ?>
                <div class="pkg-content-block mb-5">
                    <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                        <h3 class="font-serif fw-bold text-dark mb-0">
                            <i class="fas fa-route text-gold me-2"></i> Day-by-Day Itinerary
                        </h3>
                        <span class="badge bg-gold text-dark px-3 py-2 rounded-pill fw-semibold">
                            <i class="fas fa-calendar-day me-1"></i> <?php echo count($parsed_itinerary); ?> Days Schedule
                        </span>
                    </div>

                    <div class="itinerary-timeline">
                        <?php foreach ($parsed_itinerary as $index => $item): ?>
                        <div class="itinerary-timeline-item" data-aos="fade-up" data-aos-delay="<?php echo min($index * 50, 300); ?>">
                            <div class="itinerary-node">
                                <span class="itinerary-day-badge"><?php echo htmlspecialchars($item['day']); ?></span>
                            </div>
                            <div class="itinerary-card">
                                <h5 class="itinerary-title font-serif fw-bold mb-2 text-dark">
                                    <?php echo htmlspecialchars($item['title']); ?>
                                </h5>
                                <?php if (!empty($item['desc'])): ?>
                                <p class="itinerary-desc text-secondary mb-0">
                                    <?php echo htmlspecialchars($item['desc']); ?>
                                </p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Section: Tour Inclusions -->
                <?php if (!empty($package['inclusions'])): ?>
                <div class="pkg-content-block mb-5 p-4 rounded-4 bg-white border shadow-sm">
                    <h4 class="font-serif fw-bold text-dark mb-3">
                        <i class="fas fa-crown text-gold me-2"></i> Included in This Journey
                    </h4>
                    <div class="row g-3">
                        <?php 
                        $inclusions_list = array_filter(array_map('trim', explode(',', $package['inclusions'])));
                        foreach ($inclusions_list as $inc):
                        ?>
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; background: rgba(212, 175, 55, 0.15); color: #d4af37; flex-shrink: 0;">
                                <i class="fas fa-check small"></i>
                            </div>
                            <span class="text-dark fw-medium" style="font-size: 0.95rem;"><?php echo htmlspecialchars($inc); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>

            <!-- RIGHT COLUMN: STICKY BOOKING & INQUIRY SIDEBAR (4 Cols) -->
            <div class="col-lg-5" data-aos="fade-up" data-aos-delay="100">
                <div class="package-booking-sidebar-card shadow-lg p-4 rounded-4 sticky-top" style="top: 100px; background: #0b221e; color: #ffffff; border: 1px solid rgba(212, 175, 55, 0.35);">
                    
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary">
                        <div>
                            <span class="text-gold small text-uppercase fw-bold d-block">Private Tour</span>
                            <h4 class="font-serif fw-bold text-white mb-0">Bespoke Journey</h4>
                        </div>
                    </div>

                    <!-- Tour Route Map Integrated in Upper Section of Card -->
                    <div class="mb-5 pb-4 border-bottom border-secondary">
                        <h6 class="text-gold text-uppercase small fw-bold mb-3">
                            <i class="fas fa-map-location-dot me-2"></i> Tour Route Map
                        </h6>
                        <div id="routeMap" class="route-map-container" style="height: 270px;"></div>
                        <div class="route-pills-container mt-3">
                            <?php foreach ($jsStops as $index => $stop): ?>
                                <div class="route-pill-badge-dark">
                                    <span class="route-pill-number"><?php echo ($index + 1); ?></span>
                                    <?php echo htmlspecialchars($stop['name']); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <h5 class="font-serif fw-bold text-white mb-2">Reserve Your Experience</h5>
                    <p class="small text-light-50 mb-4">Contact our private travel concierge to customize this itinerary, check live availability, or request special arrangements.</p>

                    <!-- Direct Book / Inquire Button (Opens Contact Page) -->
                    <a href="contact.php?package=<?php echo urlencode($package['title']); ?>" class="pkg-sidebar-btn pkg-sidebar-btn-gold open-inquiry-modal-btn">
                        <i class="fas fa-calendar-check me-2"></i> Book / Inquire Tour
                    </a>

                    <!-- Instant WhatsApp Concierge Action -->
                    <?php 
                    $waText = urlencode("Hello Elite Ceylon Travels! I would like to inquire about booking the \"{$package['title']}\" ({$package['duration']}). Please assist me.");
                    ?>
                    <a href="https://wa.me/94776313528?text=<?php echo $waText; ?>" target="_blank" class="pkg-sidebar-btn pkg-sidebar-btn-wa">
                        <i class="fab fa-whatsapp me-2"></i> Chat on WhatsApp
                    </a>

                    <!-- Luxury Perks Included in Every Tour -->
                    <div class="p-3 rounded-3 mb-3" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.08);">
                        <h6 class="text-gold text-uppercase small fw-bold mb-3"><i class="fas fa-crown me-2"></i> The Elite Guarantee</h6>
                        <ul class="list-unstyled mb-0 d-flex flex-column gap-2 small text-light-50">
                            <li class="d-flex align-items-center"><i class="fas fa-check text-gold me-2"></i> 100% Tailored Private Itinerary</li>
                            <li class="d-flex align-items-center"><i class="fas fa-check text-gold me-2"></i> Luxury Air-Conditioned Vehicle</li>
                            <li class="d-flex align-items-center"><i class="fas fa-check text-gold me-2"></i> Professional English Guide / Chauffeur</li>
                            <li class="d-flex align-items-center"><i class="fas fa-check text-gold me-2"></i> Handpicked 4/5-Star Boutique Hotels</li>
                            <li class="d-flex align-items-center"><i class="fas fa-check text-gold me-2"></i> 24/7 Dedicated Concierge Support</li>
                        </ul>
                    </div>
                    </div>
            </div>

        </div>

    </div>
</section>

<!-- ==========================================
     OTHER RECOMMENDED TOURS SECTION
     ========================================== -->
<?php if (!empty($related_packages)): ?>
<section class="packages-grid-container py-5" style="background-color: var(--cream-light);">
    <div class="container py-3">
        <div class="section-title-wrapper text-center mb-5" data-aos="fade-up">
            <span class="section-subtitle"><i class="fas fa-compass text-gold me-2"></i>Explore Further</span>
            <h2 class="section-title" style="color: var(--dark-navy);">Other Recommended Journeys</h2>
        </div>

        <div class="premium-package-grid" data-aos="fade-up">
            <?php foreach ($related_packages as $r_pkg): ?>
            <div class="premium-card" onclick="window.location.href='package_details.php?id=<?php echo $r_pkg['id']; ?>'">
                <img src="<?php echo htmlspecialchars($r_pkg['image']); ?>" alt="<?php echo htmlspecialchars($r_pkg['title']); ?>" loading="lazy">
                <div class="premium-card-content">
                    <span class="premium-card-tag"><?php echo htmlspecialchars($r_pkg['duration']); ?></span>
                    <h3 class="premium-card-title"><?php echo htmlspecialchars($r_pkg['title']); ?></h3>
                    <p class="premium-card-desc"><?php echo htmlspecialchars($r_pkg['description']); ?></p>
                    <div class="premium-card-footer">
                        <a href="package_details.php?id=<?php echo $r_pkg['id']; ?>" class="premium-card-btn">View Details</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ==========================================
     CTA
     ========================================== -->
<section class="cta-section" style="background-image: url('assets/images/home%202.jpg');">
    <div class="cta-overlay"></div>
    <div class="container text-center cta-content">
        <div class="row justify-content-center" data-aos="zoom-in">
            <div class="col-lg-8">
                <span class="cta-subtitle">Begin Your Private Expedition</span>
                <h2 class="cta-title">Looking for a Completely Customized Journey?</h2>
                <p class="lead mb-4 text-light-50">Speak to our concierge experts and design your custom-made itinerary today.</p>
                <a href="contact.php" class="btn btn-gold">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================
     TRAVEL INQUIRY MODAL OVERLAY
     ========================================== -->
<div class="travel-inquiry-modal" id="travelInquiryModal">
    <div class="travel-inquiry-box">
        <button class="inquiry-modal-close" id="closeInquiryModal" aria-label="Close Modal"><i class="fas fa-times"></i></button>
        
        <div class="inquiry-modal-header text-center">
            <h3 class="font-serif fw-bold text-dark mb-1">Travel Inquiry</h3>
            <p class="text-muted small">Customize your luxury itinerary for <strong class="text-gold"><?php echo htmlspecialchars($package['title']); ?></strong></p>
        </div>

        <form id="travelInquiryForm" novalidate autocomplete="off">
            <input type="hidden" id="inquiryPackageTitle" name="package_title" value="<?php echo htmlspecialchars($package['title']); ?>">
            <input type="hidden" id="inquiryPackageDuration" name="package_duration" value="<?php echo htmlspecialchars($package['duration']); ?>">
            
            <div id="inquiryFeedback" class="alert alert-danger d-none mb-3 py-2 small" role="alert"></div>

            <!-- SECTION: Personal Information -->
            <div class="inquiry-form-section mb-4">
                <h5 class="section-title-inquiry mb-3">
                    <span class="icon-circle-inquiry bg-blue"><i class="fas fa-user"></i></span>
                    Personal Information
                </h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="inquiryName" class="form-label small fw-semibold">Name <span class="text-danger">*</span></label>
                        <div class="input-icon-group">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" class="form-control" id="inquiryName" name="name" placeholder="Enter your full name" required>
                        </div>
                        <div class="invalid-feedback-inline" id="inquiryNameError"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="inquiryCountry" class="form-label small fw-semibold">Country <span class="text-danger">*</span></label>
                        <div class="input-icon-group">
                            <i class="fas fa-globe input-icon"></i>
                            <select class="form-select form-control" id="inquiryCountry" name="country" required>
                                <option value="" disabled selected>Select your country</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="United States">United States</option>
                                <option value="Australia">Australia</option>
                                <option value="Germany">Germany</option>
                                <option value="France">France</option>
                                <option value="Canada">Canada</option>
                                <option value="Russia">Russia</option>
                                <option value="Japan">Japan</option>
                                <option value="India">India</option>
                                <option value="Maldives">Maldives</option>
                                <option value="Singapore">Singapore</option>
                                <option value="Spain">Spain</option>
                                <option value="Italy">Italy</option>
                                <option value="New Zealand">New Zealand</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="invalid-feedback-inline" id="inquiryCountryError"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="inquiryEmail" class="form-label small fw-semibold">Email Address <span class="text-danger">*</span></label>
                        <div class="input-icon-group">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email" class="form-control" id="inquiryEmail" name="email" placeholder="Enter your email address" required>
                        </div>
                        <div class="invalid-feedback-inline" id="inquiryEmailError"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="inquiryWhatsApp" class="form-label small fw-semibold">WhatsApp Number <span class="text-danger">*</span></label>
                        <div class="input-icon-group">
                            <i class="fab fa-whatsapp input-icon"></i>
                            <input type="tel" class="form-control" id="inquiryWhatsApp" name="whatsapp" placeholder="Enter your WhatsApp number" required>
                        </div>
                        <div class="invalid-feedback-inline" id="inquiryWhatsAppError"></div>
                    </div>
                </div>
            </div>

            <!-- SECTION: Travel Details -->
            <div class="inquiry-form-section mb-4">
                <h5 class="section-title-inquiry mb-3">
                    <span class="icon-circle-inquiry bg-blue"><i class="fas fa-suitcase"></i></span>
                    Travel Details
                </h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="inquiryArrivalDate" class="form-label small fw-semibold">Date of Arrival <span class="text-danger">*</span></label>
                        <div class="input-icon-group">
                            <i class="fas fa-calendar-alt input-icon"></i>
                            <input type="date" class="form-control" id="inquiryArrivalDate" name="arrival_date" required>
                        </div>
                        <div class="invalid-feedback-inline" id="inquiryArrivalDateError"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="inquiryDepartureDate" class="form-label small fw-semibold">Date of Departure <span class="text-danger">*</span></label>
                        <div class="input-icon-group">
                            <i class="fas fa-calendar-alt input-icon"></i>
                            <input type="date" class="form-control" id="inquiryDepartureDate" name="departure_date" required>
                        </div>
                        <div class="invalid-feedback-inline" id="inquiryDepartureDateError"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="inquiryPax" class="form-label small fw-semibold">No. of Pax (Travelers) <span class="text-danger">*</span></label>
                        <div class="input-icon-group">
                            <i class="fas fa-users input-icon"></i>
                            <input type="number" class="form-control" id="inquiryPax" name="pax" min="1" placeholder="Enter number of travelers" required>
                        </div>
                        <div class="invalid-feedback-inline" id="inquiryPaxError"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="inquiryRooms" class="form-label small fw-semibold">No. of Rooms</label>
                        <div class="input-icon-group">
                            <i class="fas fa-bed input-icon"></i>
                            <input type="number" class="form-control" id="inquiryRooms" name="rooms" min="1" placeholder="Enter number of rooms">
                        </div>
                        <div class="invalid-feedback-inline" id="inquiryRoomsError"></div>
                    </div>
                </div>

                <!-- Hotel Category Buttons -->
                <div class="mt-3">
                    <label class="form-label small fw-semibold mb-2">Hotel Category</label>
                    <input type="hidden" id="inquiryHotelCategory" name="hotel_category" value="Boutique">
                    <div class="hotel-selection-group d-flex flex-wrap gap-2">
                        <button type="button" class="hotel-btn" data-value="Budget">
                            <i class="fas fa-wallet me-1"></i> Budget
                        </button>
                        <button type="button" class="hotel-btn active" data-value="Boutique">
                            <i class="fas fa-gem me-1 text-primary-custom"></i> Boutique
                        </button>
                        <button type="button" class="hotel-btn" data-value="3 Star">
                            <i class="fas fa-star me-1"></i> 3 Star
                        </button>
                        <button type="button" class="hotel-btn" data-value="4 Star">
                            <i class="fas fa-star me-1"></i> 4 Star
                        </button>
                        <button type="button" class="hotel-btn" data-value="5 Star">
                            <i class="fas fa-star me-1"></i> 5 Star
                        </button>
                    </div>
                </div>

                <!-- Meal Plan Selection -->
                <div class="mt-3">
                    <label class="form-label small fw-semibold mb-2">Meal Plan</label>
                    <input type="hidden" id="inquiryMealPlan" name="meal_plan" value="BB (Bed & Breakfast)">
                    <div class="meal-selection-group d-flex flex-wrap gap-2">
                        <button type="button" class="meal-btn active bb-tag" data-value="BB (Bed & Breakfast)">
                            <i class="fas fa-utensils me-1"></i> BB (Bed & Breakfast)
                        </button>
                        <button type="button" class="meal-btn hb-tag" data-value="HB (Half Board)">
                            <i class="fas fa-utensils me-1"></i> HB (Half Board)
                        </button>
                        <button type="button" class="meal-btn fb-tag" data-value="FB (Full Board)">
                            <i class="fas fa-utensils me-1"></i> FB (Full Board)
                        </button>
                        <button type="button" class="meal-btn ai-tag" data-value="AI (All Inclusive)">
                            <i class="fas fa-utensils me-1"></i> AI (All Inclusive)
                        </button>
                    </div>
                </div>
            </div>

            <!-- SECTION: Other Requirements / Special Requests -->
            <div class="inquiry-form-section mb-4">
                <label for="inquiryRequests" class="form-label small fw-semibold">Other Requirements / Special Requests</label>
                <div class="input-icon-group align-items-start">
                    <i class="fas fa-list-ul input-icon mt-3"></i>
                    <textarea class="form-control" id="inquiryRequests" name="special_requests" rows="2" placeholder="Please tell us about any other requirements, preferences or special requests."></textarea>
                </div>
            </div>

            <!-- Trust / Guarantee banners -->
            <div class="inquiry-guarantee-grid mb-4">
                <div class="guarantee-box-inquiry blue-box">
                    <div class="d-flex align-items-center gap-2">
                        <span class="guarantee-icon"><i class="fas fa-headset text-primary-custom"></i></span>
                        <div>
                            <h6 class="mb-0 fw-semibold text-blue-dark">We will contact you soon!</h6>
                            <p class="mb-0 text-muted small-text">Our travel experts are ready to help you plan your perfect trip.</p>
                        </div>
                    </div>
                </div>
                <div class="guarantee-box-inquiry green-box">
                    <div class="d-flex align-items-center gap-2">
                        <span class="guarantee-icon text-success-custom"><i class="fas fa-shield-halved"></i></span>
                        <div>
                            <h6 class="mb-0 fw-semibold text-success-dark">Safe travels! <i class="far fa-heart text-danger"></i></h6>
                            <p class="mb-0 text-muted small-text">Thank you for choosing us.</p>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-gold w-100 py-3 rounded-3 fw-bold" id="submitInquiryBtn">
                <i class="fab fa-whatsapp me-2"></i> Send Inquiry via WhatsApp
            </button>
        </form>
    </div>
</div>

<!-- Leaflet Map Javascript CDN -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Check if the container exists
    var mapContainer = document.getElementById('routeMap');
    if (!mapContainer) return;

    // Get the PHP stops array serialized to JS
    var stops = <?php echo json_encode($jsStops); ?>;
    if (!stops || stops.length === 0) return;

    // Initialize Leaflet Map centered on Sri Lanka
    var map = L.map('routeMap', {
        zoomControl: true,
        scrollWheelZoom: false // disable scrolling zoom for better UX in sidebars
    }).setView([7.8731, 80.7718], 7);

    // Use CartoDB Positron base tile layer (exactly matches the screenshot)
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 20
    }).addTo(map);

    // Extract lat/lngs for polyline
    var latlngs = stops.map(function(stop) {
        return [stop.lat, stop.lng];
    });

    // Draw dashed polyline
    var polyline = L.polyline(latlngs, {
        color: '#dc3545', // Crimson red
        weight: 3,
        dashArray: '6, 6',
        opacity: 0.85
    }).addTo(map);

    // Fit map bounds to the route polyline with some padding
    map.fitBounds(polyline.getBounds(), {
        padding: [25, 25]
    });

    // Add custom markers for each stop
    stops.forEach(function(stop, index) {
        var customIcon = L.divIcon({
            className: 'custom-map-marker',
            html: '<span>' + (index + 1) + '</span>',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        var marker = L.marker([stop.lat, stop.lng], { icon: customIcon }).addTo(map);
        marker.bindPopup('<strong style="font-family: sans-serif; font-size: 13px; color: #0b221e;">' + (index + 1) + '. ' + stop.fullName + '</strong>');
    });

    // Add custom blue vehicle SVG marker animated from source to destination
    if (stops.length >= 2) {
        // Custom SVG of a blue safari jeep/van facing right
        var jeepSvg = `
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="32" height="32" style="transition: transform 0.2s ease;">
                <!-- Roof rack -->
                <rect x="18" y="14" width="28" height="4" rx="1" fill="#495057" />
                <line x1="22" y1="18" x2="22" y2="14" stroke="#495057" stroke-width="2" />
                <line x1="32" y1="18" x2="32" y2="14" stroke="#495057" stroke-width="2" />
                <line x1="42" y1="18" x2="42" y2="14" stroke="#495057" stroke-width="2" />
                
                <!-- Upper body -->
                <path d="M12 28 L16 18 Q18 18 20 18 L48 18 Q50 18 52 22 L55 28 Z" fill="#339af0" />
                <!-- Windows -->
                <path d="M18 20 L28 20 L28 26 L16 26 Z" fill="#e7f5ff" />
                <path d="M31 20 L41 20 L41 26 L31 26 Z" fill="#e7f5ff" />
                <path d="M44 20 L50 22 L48 26 L44 26 Z" fill="#e7f5ff" />
                
                <!-- Lower body -->
                <path d="M8 28 H56 C58 28 59 29 59 31 V42 C59 44 58 45 56 45 H8 C6 45 5 44 5 42 V31 C5 29 6 28 8 28 Z" fill="#1c7ed6" />
                
                <!-- Bumpers/details -->
                <rect x="3" y="38" width="4" height="4" rx="1" fill="#adb5bd" />
                <rect x="57" y="38" width="4" height="4" rx="1" fill="#adb5bd" />
                <!-- Head light -->
                <circle cx="56" cy="34" r="3" fill="#ffe066" />
                <!-- Tail light -->
                <rect x="6" y="32" width="2" height="4" fill="#fa5252" />
                
                <!-- Wheels -->
                <circle cx="18" cy="45" r="7" fill="#343a40" />
                <circle cx="18" cy="45" r="3" fill="#adb5bd" />
                <circle cx="46" cy="45" r="7" fill="#343a40" />
                <circle cx="46" cy="45" r="3" fill="#adb5bd" />
            </svg>
        `;

        // Function to interpolate points between two coordinates
        function interpolatePoints(p1, p2, steps) {
            var points = [];
            var isLeft = p2.lng < p1.lng; // check direction
            for (var i = 0; i <= steps; i++) {
                var ratio = i / steps;
                var lat = p1.lat + (p2.lat - p1.lat) * ratio;
                var lng = p1.lng + (p2.lng - p1.lng) * ratio;
                points.push({
                    coords: [lat, lng],
                    isLeft: isLeft
                });
            }
            return points;
        }

        // Generate the full animation path
        var animPath = [];
        var stepsPerSegment = 60; // 60 steps per segment for a good balance of speed and smoothness

        for (var i = 0; i < stops.length - 1; i++) {
            var segmentPoints = interpolatePoints(stops[i], stops[i+1], stepsPerSegment);
            if (i > 0) {
                segmentPoints.shift(); // avoid duplicating boundary points
            }
            animPath = animPath.concat(segmentPoints);
        }

        var vehicleIcon = L.divIcon({
            className: 'map-vehicle-icon-div',
            html: jeepSvg,
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });

        // Start marker at the first coordinate
        var vehicleMarker = L.marker(animPath[0].coords, { icon: vehicleIcon }).addTo(map);

        var currentStep = 0;
        var isPaused = false;

        function animateVehicle() {
            if (animPath.length === 0 || isPaused) return;

            var stepData = animPath[currentStep];
            vehicleMarker.setLatLng(stepData.coords);

            // Flip the SVG if traveling left
            var iconElement = vehicleMarker.getElement();
            if (iconElement) {
                var svg = iconElement.querySelector('svg');
                if (svg) {
                    svg.style.transform = stepData.isLeft ? 'scaleX(-1)' : 'scaleX(1)';
                }
            }

            currentStep++;

            // Reset if reached end
            if (currentStep >= animPath.length) {
                currentStep = 0;
                isPaused = true;
                // Pause for 1.5 seconds at the destination before restarting
                setTimeout(function() {
                    isPaused = false;
                    animateVehicle();
                }, 1500);
            } else {
                // Adjust delay to control speed of movement (45ms per step)
                setTimeout(function() {
                    requestAnimationFrame(animateVehicle);
                }, 45);
            }
        }

        // Start the animation with a small initial delay of 1 second
        setTimeout(function() {
            animateVehicle();
        }, 1000);
    }
});
</script>

<?php include 'includes/footer.php'; ?>
