<?php
// Start session and verify authentication status
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

require_once 'includes/db_connect.php';

// Generate CSRF token if not set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

$admin_id = isset($_SESSION['admin_id']) ? (int)$_SESSION['admin_id'] : 0;
$admin_username = isset($_SESSION['admin_username']) ? htmlspecialchars($_SESSION['admin_username']) : 'Administrator';
$admin_email = '';

// Retrieve latest admin account information (NEVER select or expose password to frontend)
try {
    if ($admin_id > 0) {
        $stmt_admin = $pdo->prepare("SELECT id, username, email FROM admins WHERE id = ? LIMIT 1");
        $stmt_admin->execute([$admin_id]);
    } else {
        $stmt_admin = $pdo->prepare("SELECT id, username, email FROM admins WHERE username = ? LIMIT 1");
        $stmt_admin->execute([$_SESSION['admin_username'] ?? '']);
    }
    $admin_data = $stmt_admin->fetch();
    if ($admin_data) {
        $admin_id = (int)$admin_data['id'];
        $admin_username = htmlspecialchars($admin_data['username']);
        $admin_email = htmlspecialchars($admin_data['email'] ?? '');
        $_SESSION['admin_id'] = $admin_id;
        $_SESSION['admin_username'] = $admin_data['username'];
    }
} catch (Exception $e) {
    // Keep session defaults
}

$pageTitle = "Admin Dashboard - Reviews | Elite Ceylon Travels";

// Fetch statistics
try {
    // Total Reviews
    $total_count = (int)$pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
    
    // Pending Reviews
    $pending_count = (int)$pdo->query("SELECT COUNT(*) FROM reviews WHERE approved = 0")->fetchColumn();
    
    // Approved Reviews
    $approved_count = (int)$pdo->query("SELECT COUNT(*) FROM reviews WHERE approved = 1")->fetchColumn();
    
    // Average Rating
    $avg_rating = $pdo->query("SELECT AVG(rating) FROM reviews WHERE approved = 1")->fetchColumn();
    $avg_rating = $avg_rating ? round((float)$avg_rating, 1) : 0.0;
} catch (Exception $e) {
    $total_count = $pending_count = $approved_count = 0;
    $avg_rating = 0.0;
}

// Fetch all reviews
try {
    $stmt_all = $pdo->query("SELECT * FROM reviews ORDER BY created_at DESC");
    $all_reviews = $stmt_all->fetchAll();
} catch (Exception $e) {
    $all_reviews = [];
}

// Get unique packages for filter dropdown
$packages_list = [];
foreach ($all_reviews as $r) {
    if (!empty($r['tour_package']) && !in_array($r['tour_package'], $packages_list)) {
        $packages_list[] = $r['tour_package'];
    }
}
sort($packages_list);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Custom Style Sheet -->
    <link rel="stylesheet" href="assets/css/style.css?v=10.8">
</head>
<body class="admin-wrapper">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div class="admin-sidebar-overlay" id="sidebarOverlay"></div>

    <!-- ==========================================
         LEFT SIDEBAR NAVIGATION
         ========================================== -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div>
            <!-- Brand / Logo -->
            <a href="admin_dashboard.php" class="admin-sidebar-brand">
                <div class="admin-sidebar-logo-box">
                    <img src="assets/logo/logo.png" alt="Elite Ceylon Logo" class="admin-sidebar-logo">
                </div>
                <div class="admin-sidebar-title">
                    <h5>Elite Ceylon</h5>
                    <span>Admin Portal</span>
                </div>
            </a>

            <!-- Navigation Links -->
            <div class="admin-sidebar-menu">
                <div class="admin-menu-heading">Reviews Navigation</div>
                <ul class="admin-nav-list">
                    <li class="admin-nav-item">
                        <a class="admin-nav-link active" data-filter-status="all" id="navFilterAll">
                            <div class="admin-nav-link-content">
                                <i class="fas fa-table-list"></i>
                                <span>All Reviews</span>
                            </div>
                            <span class="admin-nav-badge" id="badgeAllCount"><?php echo $total_count; ?></span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a class="admin-nav-link" data-filter-status="pending" id="navFilterPending">
                            <div class="admin-nav-link-content">
                                <i class="fas fa-clock"></i>
                                <span>Pending Approval</span>
                            </div>
                            <span class="admin-nav-badge <?php echo ($pending_count > 0) ? 'badge-amber' : ''; ?>" id="badgePendingCount">
                                <?php echo $pending_count; ?>
                            </span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a class="admin-nav-link" data-filter-status="approved" id="navFilterApproved">
                            <div class="admin-nav-link-content">
                                <i class="fas fa-check-circle"></i>
                                <span>Published Live</span>
                            </div>
                            <span class="admin-nav-badge" id="badgeApprovedCount"><?php echo $approved_count; ?></span>
                        </a>
                    </li>
                </ul>

                <div class="admin-menu-heading mt-3">Settings & Security</div>
                <ul class="admin-nav-list">
                    <li class="admin-nav-item">
                        <a class="admin-nav-link" id="navBtnAccountSettings" style="cursor: pointer;">
                            <div class="admin-nav-link-content">
                                <i class="fas fa-user-shield text-gold"></i>
                                <span>Account & Password</span>
                            </div>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="index.php" class="admin-nav-link">
                            <div class="admin-nav-link-content">
                                <i class="fas fa-globe"></i>
                                <span>Live Website</span>
                            </div>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="logout.php" class="admin-nav-link text-danger">
                            <div class="admin-nav-link-content">
                                <i class="fas fa-sign-out-alt text-danger"></i>
                                <span>Logout</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Sidebar User Profile Footer -->
        <div class="admin-sidebar-footer">
            <div class="admin-user-profile" id="sidebarProfileTrigger" style="cursor: pointer;" title="Click to manage account & password">
                <div class="admin-user-avatar" id="sidebarUserAvatar">
                    <?php echo strtoupper(substr($admin_username, 0, 1)); ?>
                </div>
                <div class="admin-user-info">
                    <h6 id="sidebarUserName"><?php echo $admin_username; ?></h6>
                    <span><i class="fas fa-circle" style="font-size: 0.5rem;"></i> Active Session</span>
                </div>
            </div>
        </div>
    </aside>

    <!-- ==========================================
         MAIN CONTENT AREA
         ========================================== -->
    <div class="admin-content-wrapper">
        
        <!-- Sticky Topbar -->
        <header class="admin-topbar">
            <div class="admin-topbar-left">
                <button type="button" class="admin-menu-toggle-btn" id="sidebarToggleBtn" aria-label="Toggle Sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="admin-topbar-title">
                    <h4>Review Management</h4>
                    <span>Dashboard &bull; Customer Testimonials</span>
                </div>
            </div>

            <div class="admin-topbar-right">
                <a href="logout.php" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center" title="Sign out of Admin Session">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </a>
            </div>
        </header>

        <!-- Main Dashboard Container -->
        <main class="admin-main-container">
            
            <!-- 4 Clean Stats Metric Cards -->
            <div class="row g-3 mb-4">
                <!-- Stat 1: Total -->
                <div class="col-xl-3 col-sm-6">
                    <div class="admin-stat-card">
                        <div class="admin-stat-icon-wrap">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div>
                            <div class="admin-stat-number"><?php echo $total_count; ?></div>
                            <div class="admin-stat-label">Total Submissions</div>
                        </div>
                    </div>
                </div>

                <!-- Stat 2: Pending -->
                <div class="col-xl-3 col-sm-6">
                    <div class="admin-stat-card">
                        <div class="admin-stat-icon-wrap icon-amber">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <div>
                            <div class="admin-stat-number text-warning"><?php echo $pending_count; ?></div>
                            <div class="admin-stat-label">Pending Approval</div>
                        </div>
                    </div>
                </div>

                <!-- Stat 3: Approved -->
                <div class="col-xl-3 col-sm-6">
                    <div class="admin-stat-card">
                        <div class="admin-stat-icon-wrap icon-emerald">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <div class="admin-stat-number text-success"><?php echo $approved_count; ?></div>
                            <div class="admin-stat-label">Published Public</div>
                        </div>
                    </div>
                </div>

                <!-- Stat 4: Rating -->
                <div class="col-xl-3 col-sm-6">
                    <div class="admin-stat-card">
                        <div class="admin-stat-icon-wrap icon-gold">
                            <i class="fas fa-star"></i>
                        </div>
                        <div>
                            <div class="admin-stat-number" style="color: #b45309;">
                                <?php echo $avg_rating; ?> <span style="font-size: 1rem; font-weight: normal; color: #64748b;">/ 5</span>
                            </div>
                            <div class="admin-stat-label">Avg Rating Score</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Reviews Table Card -->
            <div class="admin-card-section">
                <!-- Header with Filters -->
                <div class="admin-card-header">
                    <h5 id="tableCurrentTitle">All Reviews (<?php echo $total_count; ?>)</h5>

                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <!-- Live Search Input -->
                        <div class="admin-search-input-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" id="reviewSearchInput" class="admin-search-input" placeholder="Search author, package, text..." autocomplete="off">
                        </div>

                        <!-- Rating Filter -->
                        <select id="ratingFilterSelect" class="admin-filter-select">
                            <option value="">All Ratings</option>
                            <option value="5">5 Stars ★★★★★</option>
                            <option value="4">4 Stars ★★★★</option>
                            <option value="3">3 Stars ★★★</option>
                            <option value="2">2 Stars ★★</option>
                            <option value="1">1 Star ★</option>
                        </select>

                        <!-- Package Filter -->
                        <select id="packageFilterSelect" class="admin-filter-select">
                            <option value="">All Packages</option>
                            <?php foreach ($packages_list as $pkg): ?>
                                <option value="<?php echo htmlspecialchars($pkg); ?>"><?php echo htmlspecialchars($pkg); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Reviews Data Table -->
                <div class="admin-table-responsive">
                    <table class="admin-table" id="reviewsTable">
                        <thead>
                            <tr>
                                <th>Author / Explorer</th>
                                <th>Rating</th>
                                <th>Tour Package</th>
                                <th>Review Excerpt</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="reviewsTableBody">
                            <?php if (count($all_reviews) === 0): ?>
                            <tr>
                                <td colspan="7">
                                    <div class="admin-empty-state">
                                        <i class="fas fa-comments"></i>
                                        <h6>No Reviews Found</h6>
                                        <p>No client reviews are currently available in the database.</p>
                                    </div>
                                </td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($all_reviews as $review): 
                                    $id = (int)$review['id'];
                                    $name = htmlspecialchars($review['author_name']);
                                    $info = htmlspecialchars($review['author_info']);
                                    $text = htmlspecialchars($review['review_text']);
                                    $package = !empty($review['tour_package']) ? htmlspecialchars($review['tour_package']) : 'Verified Guest';
                                    $has_package = !empty($review['tour_package']);
                                    $rating = (int)$review['rating'];
                                    $is_approved = ((int)$review['approved'] === 1);
                                    $avatar = !empty($review['author_avatar']) ? htmlspecialchars($review['author_avatar']) : '';
                                    $initial = strtoupper(substr($review['author_name'], 0, 1));
                                    $date_formatted = date("M d, Y", strtotime($review['created_at']));
                                    $status_class = $is_approved ? 'is-approved' : 'is-pending';
                                ?>
                                <tr class="review-row <?php echo $status_class; ?>"
                                    data-id="<?php echo $id; ?>"
                                    data-status="<?php echo $is_approved ? 'approved' : 'pending'; ?>"
                                    data-rating="<?php echo $rating; ?>"
                                    data-package="<?php echo strtolower($package); ?>"
                                    data-name="<?php echo strtolower($name); ?>"
                                    data-info="<?php echo strtolower($info); ?>"
                                    data-text="<?php echo strtolower($text); ?>"
                                    data-raw-name="<?php echo $name; ?>"
                                    data-raw-info="<?php echo $info; ?>"
                                    data-raw-text="<?php echo $text; ?>"
                                    data-raw-package="<?php echo $has_package ? $package : ''; ?>"
                                    data-raw-avatar="<?php echo $avatar; ?>"
                                    data-raw-date="<?php echo $date_formatted; ?>">
                                    
                                    <!-- Author -->
                                    <td>
                                        <div class="admin-table-author">
                                            <?php if (!empty($avatar)): ?>
                                                <img src="<?php echo $avatar; ?>" alt="<?php echo $name; ?>" class="admin-table-avatar">
                                            <?php else: ?>
                                                <div class="admin-table-monogram"><?php echo $initial; ?></div>
                                            <?php endif; ?>
                                            <div>
                                                <p class="admin-table-name"><?php echo $name; ?></p>
                                                <span class="admin-table-sub"><?php echo $info; ?></span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Rating Stars -->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="admin-table-stars">
                                                <?php
                                                for ($s = 1; $s <= 5; $s++) {
                                                    echo ($s <= $rating) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                                }
                                                ?>
                                            </div>
                                            <span class="admin-table-score"><?php echo $rating; ?>.0</span>
                                        </div>
                                    </td>

                                    <!-- Tour Package -->
                                    <td>
                                        <?php if ($has_package): ?>
                                            <span class="admin-badge-package"><?php echo $package; ?></span>
                                        <?php else: ?>
                                            <span class="text-muted small">&mdash;</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Review Excerpt -->
                                    <td>
                                        <div class="admin-table-quote" title="<?php echo $text; ?>">
                                            "<?php echo $text; ?>"
                                        </div>
                                    </td>

                                    <!-- Status -->
                                    <td>
                                        <?php if ($is_approved): ?>
                                            <span class="admin-badge-status status-approved"><i class="fas fa-check-circle"></i> Live</span>
                                        <?php else: ?>
                                            <span class="admin-badge-status status-pending"><i class="fas fa-clock"></i> Pending</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Date -->
                                    <td>
                                        <span class="text-muted small"><?php echo $date_formatted; ?></span>
                                    </td>

                                    <!-- Actions -->
                                    <td>
                                        <div class="admin-table-actions">
                                            <button type="button" class="btn-table-action btn-table-view btn-action-trigger" data-action="view" data-id="<?php echo $id; ?>" title="View as Card">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            <?php if (!$is_approved): ?>
                                                <button type="button" class="btn-table-action btn-table-approve btn-action-trigger" data-action="approve" data-id="<?php echo $id; ?>" title="Approve & Publish">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="btn-table-action btn-table-unapprove btn-action-trigger" data-action="unapprove" data-id="<?php echo $id; ?>" title="Unpublish / Move to Pending">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            <?php endif; ?>
                                            
                                            <button type="button" class="btn-table-action btn-table-delete btn-action-trigger" data-action="delete" data-id="<?php echo $id; ?>" title="Delete permanently">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Zero search result state -->
                <div id="tableNoResults" class="admin-empty-state d-none">
                    <i class="fas fa-search"></i>
                    <h6>No Matching Reviews</h6>
                    <p>Try adjusting your search query or filter options.</p>
                </div>
            </div>

        </main>
    </div>

    <!-- ==========================================
         MODAL: VIEW REVIEW CARD
         ========================================== -->
    <div class="luxury-modal-backdrop" id="modalViewReview">
        <div class="luxury-modal-dialog" style="max-width: 480px; padding: 2.25rem;">
            <button type="button" class="luxury-modal-close" id="btnCloseViewReview" aria-label="Close modal">
                <i class="fas fa-times"></i>
            </button>
            
            <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                <div class="admin-stat-icon-wrap icon-gold" style="width: 42px; height: 42px; font-size: 1.1rem; flex-shrink: 0; background: rgba(212, 175, 55, 0.1); border-color: rgba(212, 175, 55, 0.3); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-eye text-gold"></i>
                </div>
                <div>
                    <h4 class="font-serif text-dark fw-bold mb-0" style="font-size: 1.25rem;">Review Card Preview</h4>
                    <p class="text-muted mb-0 small" style="font-size: 0.8rem;">This is how the review appears on the live website.</p>
                </div>
            </div>

            <!-- The Review Card container -->
            <div id="previewCardContainer">
                <!-- Card contents will be dynamically injected here via JS -->
            </div>
            
            <div class="d-flex justify-content-end gap-2 pt-3 mt-4 border-top">
                <button type="button" class="btn btn-sm btn-outline-secondary px-4 rounded-pill py-2" id="btnCloseViewReviewBtn">Close</button>
            </div>
        </div>
    </div>

    <!-- ==========================================
         MODAL: ACCOUNT SECURITY & CHANGE PASSWORD
         ========================================== -->
    <div class="luxury-modal-backdrop" id="modalAccountSettings">
        <div class="luxury-modal-dialog" style="max-width: 680px; padding: 2rem;">
            <button type="button" class="luxury-modal-close" id="btnCloseAccountSettings" aria-label="Close modal">
                <i class="fas fa-times"></i>
            </button>
            
            <div class="d-flex align-items-center gap-3 mb-3 pb-2 border-bottom">
                <div class="admin-stat-icon-wrap icon-gold" style="width: 42px; height: 42px; font-size: 1.1rem; flex-shrink: 0;">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div>
                    <h4 class="font-serif text-dark fw-bold mb-0" style="font-size: 1.25rem;">Account Security & Password</h4>
                    <p class="text-muted mb-0 small">Update your admin credentials and manage your account security.</p>
                </div>
            </div>

            <form id="adminAccountSettingsForm" autocomplete="off" novalidate>
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" id="accCsrfToken" value="<?php echo htmlspecialchars($csrf_token); ?>">

                <!-- Username & Email (Row 1) -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="accUsername" class="form-label text-muted small mb-1 fw-semibold">Admin Username *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control admin-modal-input border-start-0 py-2 ps-2" name="username" id="accUsername" value="<?php echo $admin_username; ?>" required placeholder="Enter username" autocomplete="username">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="accEmail" class="form-label text-muted small mb-1 fw-semibold">Email Address (Optional)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control admin-modal-input border-start-0 py-2 ps-2" name="email" id="accEmail" value="<?php echo $admin_email; ?>" placeholder="admin@eliteceylon.com" autocomplete="email">
                        </div>
                    </div>
                </div>

                <!-- Password Section Header -->
                <div class="d-flex align-items-center justify-content-between mb-2 pt-2 border-top">
                    <span class="small fw-bold text-dark"><i class="fas fa-lock me-1 text-gold"></i> Password Management</span>
                    <span class="small text-muted" style="font-size: 0.72rem;">Leave new password blank to keep current</span>
                </div>

                <!-- Current Password (Row 2) - NO EYE BUTTON, ALWAYS EMPTY ON PAGE LOAD -->
                <div class="mb-3">
                    <label for="accCurrentPassword" class="form-label text-muted small mb-1 fw-semibold">
                        Current Password * <span class="text-muted fw-normal" style="font-size: 0.75rem;">(Required to authorize changes)</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-shield-alt"></i></span>
                        <input type="password" class="form-control admin-modal-input border-start-0 py-2 ps-2" name="current_password" id="accCurrentPassword" required placeholder="Enter your current password" autocomplete="current-password" value="">
                    </div>
                </div>

                <!-- New Password & Confirm Password (Row 3) -->
                <div class="row g-3 mb-2">
                    <!-- New Password -->
                    <div class="col-md-6">
                        <label for="accNewPassword" class="form-label text-muted small mb-1 fw-semibold">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-key"></i></span>
                            <input type="password" class="form-control admin-modal-input border-start-0 border-end-0 py-2 ps-2" name="new_password" id="accNewPassword" placeholder="Enter your new password" autocomplete="new-password" value="">
                            <button class="btn btn-outline-secondary border-start-0 toggle-password-visibility" type="button" data-target="accNewPassword" aria-label="Show password">
                                <i class="far fa-eye" aria-hidden="true"></i>
                            </button>
                        </div>

                        <!-- Password Strength Indicator -->
                        <div class="password-strength-wrap" id="passwordStrengthWrap">
                            <div class="password-strength-meter">
                                <div class="password-strength-bar" id="passwordStrengthBar"></div>
                            </div>
                            <div class="password-strength-text">
                                <span class="text-muted">Password Strength:</span>
                                <span id="passwordStrengthLabel" class="text-muted">None</span>
                            </div>
                        </div>
                    </div>

                    <!-- Confirm New Password -->
                    <div class="col-md-6">
                        <label for="accConfirmPassword" class="form-label text-muted small mb-1 fw-semibold">Confirm New Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-check-double"></i></span>
                            <input type="password" class="form-control admin-modal-input border-start-0 border-end-0 py-2 ps-2" name="confirm_password" id="accConfirmPassword" placeholder="Confirm your new password" autocomplete="new-password" value="">
                            <button class="btn btn-outline-secondary border-start-0 toggle-password-visibility" type="button" data-target="accConfirmPassword" aria-label="Show password">
                                <i class="far fa-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                        <!-- Match Validation Message -->
                        <div id="confirmMatchFeedback" class="small mt-1 text-muted" style="font-size: 0.75rem;"></div>
                    </div>
                </div>

                <!-- Password Requirements Checklist -->
                <div class="p-2 mb-3 rounded-2" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <span class="small fw-semibold text-dark d-block mb-1" style="font-size: 0.75rem;">Password Requirements:</span>
                    <ul class="password-req-list">
                        <li class="password-req-item" id="req-length"><i class="fas fa-circle text-muted"></i> At least 8 characters</li>
                        <li class="password-req-item" id="req-uppercase"><i class="fas fa-circle text-muted"></i> One uppercase letter (A-Z)</li>
                        <li class="password-req-item" id="req-lowercase"><i class="fas fa-circle text-muted"></i> One lowercase letter (a-z)</li>
                        <li class="password-req-item" id="req-number"><i class="fas fa-circle text-muted"></i> One number (0-9)</li>
                        <li class="password-req-item" id="req-special"><i class="fas fa-circle text-muted"></i> One special character (!@#$%^&*)</li>
                    </ul>
                </div>

                <!-- Feedback Alert Container -->
                <div id="accountSettingsFeedback" class="alert d-none mb-3 py-2 small" role="alert"></div>

                <!-- Modal Actions -->
                <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-4 rounded-pill py-2" id="btnCancelAccountSettings">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-gold px-4 rounded-pill py-2 fw-semibold" id="btnSubmitAccountSettings" disabled>
                        <i class="fas fa-save me-1"></i> Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==========================================
         MODAL: CONFIRM ACTION
         ========================================== -->
    <div class="luxury-modal-backdrop" id="modalConfirmAction">
        <div class="luxury-modal-dialog text-center" style="max-width: 420px;">
            <div class="admin-stat-icon-wrap icon-amber mx-auto mb-3" style="width: 52px; height: 52px;" id="confirmIcon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h5 class="font-serif text-dark fw-bold mb-2" id="confirmTitle">Confirm Action</h5>
            <p class="text-muted mb-4 small" id="confirmMessage">Are you sure you want to proceed?</p>
            <div class="d-flex justify-content-center gap-3">
                <button type="button" class="btn btn-sm btn-outline-secondary px-4 rounded-pill" id="btnCancelConfirm">Cancel</button>
                <button type="button" class="btn btn-sm btn-gold px-4 rounded-pill" id="btnExecuteConfirm">Proceed</button>
            </div>
        </div>
    </div>

    <!-- Custom Toast Notifications Container -->
    <div class="luxury-toast-container" id="toastContainer"></div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ==========================================
         CLIENT JAVASCRIPT CONTROLLER
         ========================================== -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {

        // --- Mobile Sidebar Toggle ---
        const sidebar = document.getElementById('adminSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');

        function toggleSidebar() {
            sidebar.classList.toggle('is-open');
            sidebarOverlay.classList.toggle('is-open');
        }

        if (sidebarToggleBtn) sidebarToggleBtn.addEventListener('click', toggleSidebar);
        if (sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);

        // --- Toast Notifications ---
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `luxury-toast toast-${type}`;
            
            let iconClass = 'fa-check-circle';
            if (type === 'error') iconClass = 'fa-circle-xmark';
            if (type === 'info') iconClass = 'fa-info-circle';

            toast.innerHTML = `
                <i class="fas ${iconClass} luxury-toast-icon"></i>
                <div class="luxury-toast-content">${message}</div>
                <button class="luxury-toast-close" aria-label="Close"><i class="fas fa-times"></i></button>
            `;

            container.appendChild(toast);

            toast.querySelector('.luxury-toast-close').addEventListener('click', () => {
                toast.remove();
            });

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(40px)';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        // --- Password Visibility Toggle Helper (for New & Confirm Password only) ---
        document.querySelectorAll('.toggle-password-visibility').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                if (input) {
                    if (input.type === 'password') {
                        input.type = 'text';
                        this.setAttribute('aria-label', 'Hide password');
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        this.setAttribute('aria-label', 'Show password');
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                }
            });
        });

        // --- Global Notification Alert / Toast Helper ---
        window.showToast = function(message, type = 'success') {
            let container = document.querySelector('.global-toast-container');
            if (!container) {
                container = document.createElement('div');
                container.className = 'global-toast-container';
                document.body.appendChild(container);
            }

            const toast = document.createElement('div');
            const isError = (type === 'error' || type === 'danger');
            toast.className = `global-toast toast-${isError ? 'error' : 'success'}`;
            const iconClass = isError ? 'fa-exclamation-circle' : 'fa-check-circle';

            toast.innerHTML = `
                <i class="fas ${iconClass} toast-icon"></i>
                <span class="toast-msg">${message}</span>
                <button type="button" class="toast-close" aria-label="Close">&times;</button>
            `;

            toast.querySelector('.toast-close').addEventListener('click', () => {
                toast.classList.add('toast-fade-out');
                setTimeout(() => toast.remove(), 300);
            });

            container.appendChild(toast);

            setTimeout(() => {
                if (toast.parentNode) {
                    toast.classList.add('toast-fade-out');
                    setTimeout(() => toast.remove(), 300);
                }
            }, 4500);
        };

        // --- Confirmation Modal Helper ---
        let pendingConfirmCallback = null;
        const confirmModal = document.getElementById('modalConfirmAction');
        const confirmTitle = document.getElementById('confirmTitle');
        const confirmMessage = document.getElementById('confirmMessage');
        const confirmIcon = document.getElementById('confirmIcon');
        const btnExecuteConfirm = document.getElementById('btnExecuteConfirm');
        const btnCancelConfirm = document.getElementById('btnCancelConfirm');

        function openConfirmModal(options, onConfirm) {
            confirmTitle.textContent = options.title || 'Confirm Action';
            confirmMessage.textContent = options.message || 'Are you sure you want to proceed?';
            btnExecuteConfirm.textContent = options.buttonText || 'Proceed';
            
            if (options.isDanger) {
                confirmIcon.className = 'admin-stat-icon-wrap mx-auto mb-3 text-danger';
                confirmIcon.style.background = '#fee2e2';
                confirmIcon.style.borderColor = '#fecaca';
                confirmIcon.innerHTML = '<i class="fas fa-trash-alt"></i>';
                btnExecuteConfirm.className = 'btn btn-sm btn-danger px-4 rounded-pill';
            } else {
                confirmIcon.className = 'admin-stat-icon-wrap icon-amber mx-auto mb-3';
                confirmIcon.style.background = '';
                confirmIcon.style.borderColor = '';
                confirmIcon.innerHTML = '<i class="fas fa-check-circle"></i>';
                btnExecuteConfirm.className = 'btn btn-sm btn-gold px-4 rounded-pill';
            }

            pendingConfirmCallback = onConfirm;
            confirmModal.classList.add('is-open');
        }

        function closeConfirmModal() {
            confirmModal.classList.remove('is-open');
            pendingConfirmCallback = null;
        }

        btnCancelConfirm.addEventListener('click', closeConfirmModal);
        btnExecuteConfirm.addEventListener('click', function() {
            if (typeof pendingConfirmCallback === 'function') {
                const cb = pendingConfirmCallback;
                closeConfirmModal();
                cb();
            }
        });

        // --- View Card Modal Helper ---
        const modalViewReview = document.getElementById('modalViewReview');
        const btnCloseViewReview = document.getElementById('btnCloseViewReview');
        const btnCloseViewReviewBtn = document.getElementById('btnCloseViewReviewBtn');
        const previewCardContainer = document.getElementById('previewCardContainer');

        function openViewModal(trigger) {
            const row = trigger.closest('.review-row');
            if (!row) return;

            const name = row.getAttribute('data-raw-name');
            const info = row.getAttribute('data-raw-info');
            const text = row.getAttribute('data-raw-text');
            const package = row.getAttribute('data-raw-package');
            const avatar = row.getAttribute('data-raw-avatar');
            const date = row.getAttribute('data-raw-date');
            const rating = parseInt(row.getAttribute('data-rating')) || 5;

            // Generate stars HTML
            let starsHtml = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= rating) {
                    starsHtml += '<i class="fas fa-star" style="margin-right: 2px;"></i>';
                } else {
                    starsHtml += '<i class="far fa-star" style="margin-right: 2px;"></i>';
                }
            }

            // Generate avatar or initials HTML
            let avatarHtml = '';
            if (avatar && avatar.trim() !== '') {
                avatarHtml = `<img src="${avatar}" alt="${name}" class="card-author-avatar">`;
            } else {
                const initial = name ? name.charAt(0).toUpperCase() : '';
                avatarHtml = `<div class="card-author-avatar-initials">${initial}</div>`;
            }

            // Generate package badge HTML
            let packageBadgeHtml = '';
            if (package && package.trim() !== '') {
                packageBadgeHtml = `<span class="card-review-badge">${package}</span>`;
            } else {
                packageBadgeHtml = `<span class="card-review-badge">Verified Guest</span>`;
            }

            // Build card HTML matching reviews-page-card style
            previewCardContainer.innerHTML = `
                <div class="reviews-page-card border-0 shadow-none p-0">
                    <div class="card-author-header">
                        ${avatarHtml}
                        <div class="card-author-info">
                            <h5>${name}</h5>
                            <span>${info}</span>
                        </div>
                    </div>
                    <div class="testi-stars">
                        ${starsHtml}
                    </div>
                    <p class="card-review-text">
                        "${text}"
                    </p>
                    <div class="card-review-footer">
                        <span class="card-review-date">${date}</span>
                        ${packageBadgeHtml}
                    </div>
                </div>
            `;

            modalViewReview.classList.add('is-open');
        }

        function closeViewModal() {
            modalViewReview.classList.remove('is-open');
        }

        if (btnCloseViewReview) btnCloseViewReview.addEventListener('click', closeViewModal);
        if (btnCloseViewReviewBtn) btnCloseViewReviewBtn.addEventListener('click', closeViewModal);

        let viewMouseDownBackdrop = false;
        if (modalViewReview) {
            modalViewReview.addEventListener('mousedown', (e) => {
                viewMouseDownBackdrop = (e.target === modalViewReview);
            });
            modalViewReview.addEventListener('mouseup', (e) => {
                if (e.target === modalViewReview && viewMouseDownBackdrop) {
                    closeViewModal();
                }
                viewMouseDownBackdrop = false;
            });
        }

        // --- Sidebar Filter & Tabs Controller ---
        let currentStatusFilter = 'all';
        const navLinks = document.querySelectorAll('.admin-nav-link[data-filter-status]');
        const tableTitle = document.getElementById('tableCurrentTitle');

        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                currentStatusFilter = this.getAttribute('data-filter-status');

                if (currentStatusFilter === 'pending') {
                    tableTitle.textContent = 'Pending Moderation (' + document.getElementById('badgePendingCount').textContent.trim() + ')';
                } else if (currentStatusFilter === 'approved') {
                    tableTitle.textContent = 'Published Live Reviews (' + document.getElementById('badgeApprovedCount').textContent.trim() + ')';
                } else {
                    tableTitle.textContent = 'All Reviews (' + document.getElementById('badgeAllCount').textContent.trim() + ')';
                }

                if (window.innerWidth < 992) {
                    sidebar.classList.remove('is-open');
                    sidebarOverlay.classList.remove('is-open');
                }

                applyFilters();
            });
        });

        // --- Instant Search & Filter Logic ---
        const searchInput = document.getElementById('reviewSearchInput');
        const ratingFilter = document.getElementById('ratingFilterSelect');
        const packageFilter = document.getElementById('packageFilterSelect');
        const tableRows = document.querySelectorAll('#reviewsTableBody .review-row');
        const noResults = document.getElementById('tableNoResults');

        function applyFilters() {
            const query = searchInput.value.toLowerCase().trim();
            const selectedRating = ratingFilter.value;
            const selectedPackage = packageFilter.value.toLowerCase().trim();

            let visibleCount = 0;

            tableRows.forEach(row => {
                const status = row.getAttribute('data-status');
                const rating = row.getAttribute('data-rating');
                const pkg = row.getAttribute('data-package') || '';
                const name = row.getAttribute('data-name') || '';
                const info = row.getAttribute('data-info') || '';
                const text = row.getAttribute('data-text') || '';

                const matchesStatus = (currentStatusFilter === 'all') || (status === currentStatusFilter);
                const matchesRating = !selectedRating || (rating === selectedRating);
                const matchesPackage = !selectedPackage || (pkg === selectedPackage);
                const matchesQuery = !query || name.includes(query) || info.includes(query) || text.includes(query) || pkg.includes(query);

                if (matchesStatus && matchesRating && matchesPackage && matchesQuery) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (tableRows.length > 0) {
                if (visibleCount === 0) {
                    noResults.classList.remove('d-none');
                } else {
                    noResults.classList.add('d-none');
                }
            }
        }

        if (searchInput) searchInput.addEventListener('input', applyFilters);
        if (ratingFilter) ratingFilter.addEventListener('change', applyFilters);
        if (packageFilter) packageFilter.addEventListener('change', applyFilters);

        // --- Action Triggers (Approve, Unapprove, Delete) ---
        document.addEventListener('click', function(e) {
            const trigger = e.target.closest('.btn-action-trigger');
            if (!trigger) return;

            e.preventDefault();
            const id = trigger.getAttribute('data-id');
            const action = trigger.getAttribute('data-action');

            if (action === 'view') {
                openViewModal(trigger);
            } else if (action === 'delete') {
                openConfirmModal({
                    title: 'Delete Review',
                    message: 'Are you sure you want to permanently delete this customer review? This cannot be undone.',
                    buttonText: 'Delete',
                    isDanger: true
                }, () => executeAction(id, action));
            } else if (action === 'unapprove') {
                openConfirmModal({
                    title: 'Unpublish Review',
                    message: 'Move this review back to pending moderation and remove it from public display?',
                    buttonText: 'Unpublish',
                    isDanger: false
                }, () => executeAction(id, action));
            } else {
                executeAction(id, action);
            }
        });

        function executeAction(id, action) {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('action', action);

            fetch('moderate_review.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => window.location.reload(), 600);
                } else {
                    showToast(data.message || 'Operation failed', 'error');
                }
            })
            .catch(() => {
                showToast('Network error occurred.', 'error');
            });
        }

        // --- Account & Password Settings Security Controller ---
        const modalAccountSettings = document.getElementById('modalAccountSettings');
        const btnCloseAccountSettings = document.getElementById('btnCloseAccountSettings');
        const btnCancelAccountSettings = document.getElementById('btnCancelAccountSettings');
        const navBtnAccountSettings = document.getElementById('navBtnAccountSettings');
        const sidebarProfileTrigger = document.getElementById('sidebarProfileTrigger');
        const accountSettingsForm = document.getElementById('adminAccountSettingsForm');
        const accountFeedback = document.getElementById('accountSettingsFeedback');
        const btnSubmitAccountSettings = document.getElementById('btnSubmitAccountSettings');
        const accCsrfToken = document.getElementById('accCsrfToken');

        const accCurrentPassword = document.getElementById('accCurrentPassword');
        const accNewPassword = document.getElementById('accNewPassword');
        const accConfirmPassword = document.getElementById('accConfirmPassword');
        const accUsername = document.getElementById('accUsername');
        const confirmMatchFeedback = document.getElementById('confirmMatchFeedback');

        const passwordStrengthBar = document.getElementById('passwordStrengthBar');
        const passwordStrengthLabel = document.getElementById('passwordStrengthLabel');

        const reqLength = document.getElementById('req-length');
        const reqUpper = document.getElementById('req-uppercase');
        const reqLower = document.getElementById('req-lowercase');
        const reqNumber = document.getElementById('req-number');
        const reqSpecial = document.getElementById('req-special');

        function updateRequirement(element, isMet) {
            if (!element) return;
            const icon = element.querySelector('i');
            if (isMet) {
                element.classList.add('met');
                if (icon) icon.className = 'fas fa-check-circle text-success';
            } else {
                element.classList.remove('met');
                if (icon) icon.className = 'fas fa-circle text-muted';
            }
        }

        function evaluatePasswordSecurity() {
            const currentPass = accCurrentPassword.value.trim();
            const newPass = accNewPassword.value;
            const confirmPass = accConfirmPassword.value;
            const userVal = accUsername.value.trim();

            const isLength = newPass.length >= 8;
            const isUpper = /[A-Z]/.test(newPass);
            const isLower = /[a-z]/.test(newPass);
            const isNumber = /[0-9]/.test(newPass);
            const isSpecial = /[^a-zA-Z0-9]/.test(newPass);

            updateRequirement(reqLength, isLength);
            updateRequirement(reqUpper, isUpper);
            updateRequirement(reqLower, isLower);
            updateRequirement(reqNumber, isNumber);
            updateRequirement(reqSpecial, isSpecial);

            let score = 0;
            if (isLength) score++;
            if (isUpper) score++;
            if (isLower) score++;
            if (isNumber) score++;
            if (isSpecial) score++;

            // Strength bar
            if (newPass.length === 0) {
                passwordStrengthBar.style.width = '0%';
                passwordStrengthBar.style.backgroundColor = '#e2e8f0';
                passwordStrengthLabel.textContent = 'None';
                passwordStrengthLabel.className = 'text-muted';
            } else if (score <= 2) {
                passwordStrengthBar.style.width = '30%';
                passwordStrengthBar.style.backgroundColor = '#ef4444';
                passwordStrengthLabel.textContent = 'Weak';
                passwordStrengthLabel.className = 'text-danger fw-bold';
            } else if (score === 3) {
                passwordStrengthBar.style.width = '60%';
                passwordStrengthBar.style.backgroundColor = '#f97316';
                passwordStrengthLabel.textContent = 'Fair';
                passwordStrengthLabel.className = 'text-warning fw-bold';
            } else if (score === 4) {
                passwordStrengthBar.style.width = '80%';
                passwordStrengthBar.style.backgroundColor = '#eab308';
                passwordStrengthLabel.textContent = 'Good';
                passwordStrengthLabel.className = 'text-warning fw-bold';
            } else if (score === 5) {
                passwordStrengthBar.style.width = '100%';
                passwordStrengthBar.style.backgroundColor = '#10b981';
                passwordStrengthLabel.textContent = 'Strong';
                passwordStrengthLabel.className = 'text-success fw-bold';
            }

            // Confirm Password matching check
            let isConfirmValid = true;
            if (confirmPass.length > 0 || newPass.length > 0) {
                if (confirmPass.length === 0) {
                    confirmMatchFeedback.innerHTML = '';
                    accConfirmPassword.classList.remove('input-is-invalid', 'input-is-valid');
                    isConfirmValid = false;
                } else if (confirmPass === newPass) {
                    confirmMatchFeedback.innerHTML = '<span class="text-success"><i class="fas fa-check-circle me-1"></i> Passwords match</span>';
                    accConfirmPassword.classList.remove('input-is-invalid');
                    accConfirmPassword.classList.add('input-is-valid');
                    isConfirmValid = true;
                } else {
                    confirmMatchFeedback.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle me-1"></i> New passwords do not match.</span>';
                    accConfirmPassword.classList.remove('input-is-valid');
                    accConfirmPassword.classList.add('input-is-invalid');
                    isConfirmValid = false;
                }
            } else {
                confirmMatchFeedback.innerHTML = '';
                accConfirmPassword.classList.remove('input-is-invalid', 'input-is-valid');
            }

            // Enable / Disable Change Password Button
            const hasCurrentPass = currentPass.length > 0;
            const hasValidUsername = userVal.length >= 3;
            
            if (newPass.length > 0) {
                // Changing password: must meet all complexity requirements, passwords match, and current password provided
                if (hasCurrentPass && hasValidUsername && score === 5 && isConfirmValid && confirmPass.length > 0) {
                    btnSubmitAccountSettings.disabled = false;
                } else {
                    btnSubmitAccountSettings.disabled = true;
                }
            } else {
                // Only updating username / email
                if (hasCurrentPass && hasValidUsername) {
                    btnSubmitAccountSettings.disabled = false;
                } else {
                    btnSubmitAccountSettings.disabled = true;
                }
            }
        }

        if (accCurrentPassword) accCurrentPassword.addEventListener('input', evaluatePasswordSecurity);
        if (accNewPassword) accNewPassword.addEventListener('input', evaluatePasswordSecurity);
        if (accConfirmPassword) accConfirmPassword.addEventListener('input', evaluatePasswordSecurity);
        if (accUsername) accUsername.addEventListener('input', evaluatePasswordSecurity);

        function openAccountModal() {
            if (accountFeedback) {
                accountFeedback.className = 'alert d-none mb-3 py-2 small';
                accountFeedback.textContent = '';
            }
            // Always ensure password fields are completely empty on open
            accCurrentPassword.value = '';
            accNewPassword.value = '';
            accConfirmPassword.value = '';
            evaluatePasswordSecurity();
            modalAccountSettings.classList.add('is-open');
        }

        function closeAccountModal() {
            modalAccountSettings.classList.remove('is-open');
            // Clean up password fields on close
            accCurrentPassword.value = '';
            accNewPassword.value = '';
            accConfirmPassword.value = '';
            evaluatePasswordSecurity();
        }

        if (navBtnAccountSettings) navBtnAccountSettings.addEventListener('click', openAccountModal);
        if (sidebarProfileTrigger) sidebarProfileTrigger.addEventListener('click', openAccountModal);
        if (btnCloseAccountSettings) btnCloseAccountSettings.addEventListener('click', closeAccountModal);
        if (btnCancelAccountSettings) btnCancelAccountSettings.addEventListener('click', closeAccountModal);

        let accMouseDownBackdrop = false;
        modalAccountSettings.addEventListener('mousedown', (e) => {
            accMouseDownBackdrop = (e.target === modalAccountSettings);
        });
        modalAccountSettings.addEventListener('mouseup', (e) => {
            if (e.target === modalAccountSettings && accMouseDownBackdrop) {
                closeAccountModal();
            }
            accMouseDownBackdrop = false;
        });

        if (accountSettingsForm) {
            accountSettingsForm.addEventListener('submit', function(e) {
                e.preventDefault();

                accountFeedback.className = 'alert d-none mb-3 py-2 small';
                accountFeedback.textContent = '';

                const newPass = accNewPassword.value;
                const confirmPass = accConfirmPassword.value;

                if (newPass.length > 0 && newPass !== confirmPass) {
                    accountFeedback.className = 'alert alert-danger mb-3 py-2 small';
                    accountFeedback.textContent = 'New passwords do not match.';
                    return;
                }

                btnSubmitAccountSettings.disabled = true;
                btnSubmitAccountSettings.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Updating...';

                const formData = new FormData(this);

                fetch('update_admin_credentials.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    btnSubmitAccountSettings.innerHTML = '<i class="fas fa-save me-1"></i> Change Password';
                    
                    if (data.success) {
                        // Clear all password fields
                        accCurrentPassword.value = '';
                        accNewPassword.value = '';
                        accConfirmPassword.value = '';
                        evaluatePasswordSecurity();

                        if (data.new_csrf_token && accCsrfToken) {
                            accCsrfToken.value = data.new_csrf_token;
                        }

                        // Update username display in sidebar
                        if (data.new_username) {
                            const updatedUser = data.new_username;
                            document.getElementById('sidebarUserName').textContent = updatedUser;
                            document.getElementById('sidebarUserAvatar').textContent = updatedUser.charAt(0).toUpperCase();
                        }

                        // Show success feedback
                        accountFeedback.className = 'alert alert-success mb-3 py-2 small';
                        accountFeedback.textContent = data.message;
                        showToast(data.message, 'success');

                        setTimeout(() => {
                            if (data.password_changed) {
                                window.location.href = data.redirect_url || 'index.php?admin_login=1&msg=password_reset';
                            } else {
                                closeAccountModal();
                            }
                        }, 1200);
                    } else {
                        btnSubmitAccountSettings.disabled = false;
                        accountFeedback.className = 'alert alert-danger mb-3 py-2 small';
                        accountFeedback.textContent = data.message;
                        showToast(data.message, 'error');
                    }
                })
                .catch(() => {
                    btnSubmitAccountSettings.disabled = false;
                    btnSubmitAccountSettings.innerHTML = '<i class="fas fa-save me-1"></i> Change Password';
                    accountFeedback.className = 'alert alert-danger mb-3 py-2 small';
                    accountFeedback.textContent = 'A connection error occurred. Please try again.';
                    showToast('Network error occurred.', 'error');
                });
            });
        }

    });
    </script>
</body>
</html>
