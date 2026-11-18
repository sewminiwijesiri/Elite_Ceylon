    <!-- ==========================================
         FOOTER SECTION
         ========================================== -->
    <footer class="footer-section">
        <div class="container">
            <div class="row g-5">
                
                <!-- About Widget -->
                <div class="col-lg-4 col-md-6 footer-about" data-aos="fade-up" data-aos-delay="100">
                    <a href="index.php" class="d-inline-block">
                        <div class="footer-logo-wrap">
                            <img src="assets/logo/logo.png" alt="Elite Ceylon Travels Logo">
                        </div>
                    </a>
                    <p class="mt-3">
                        Elite Ceylon Travels is a premier luxury travel agency based in Sri Lanka, dedicated to delivering bespoke, adventure-rich, and deeply immersive travel experiences. We show you the island like no one else.
                    </p>
                    <div class="social-icons-footer">
                        <a href="https://wa.me/94776313528?text=Hello%20Elite%20Ceylon%20Travels!%20I%20would%20like%20to%20inquire%20about%20a%20tour%20package." target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                
                <!-- Quick Links Widget -->
                <div class="col-lg-3 col-md-6 footer-widget" data-aos="fade-up" data-aos-delay="200">
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="index.php"><i class="fas fa-chevron-right me-2 text-gold"></i>Home</a></li>
                        <li><a href="packages.php"><i class="fas fa-chevron-right me-2 text-gold"></i>Packages</a></li>
                        <li><a href="gallery.php"><i class="fas fa-chevron-right me-2 text-gold"></i>Gallery</a></li>
                        <li><a href="about.php"><i class="fas fa-chevron-right me-2 text-gold"></i>About Us</a></li>
                        <li><a href="reviews.php"><i class="fas fa-chevron-right me-2 text-gold"></i>Reviews</a></li>
                        <li><a href="contact.php"><i class="fas fa-chevron-right me-2 text-gold"></i>Contact</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info Widget -->
                <div class="col-lg-5 col-md-6 footer-widget" data-aos="fade-up" data-aos-delay="300">
                    <h4>Contact Information</h4>
                    <ul class="footer-contact-info">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Colombo</span>
                        </li>
                        <li>
                            <i class="fas fa-phone-alt"></i>
                            <a href="tel:+94776313528">+94 77 631 3528</a>
                        </li>
                        <li>
                            <i class="fab fa-whatsapp"></i>
                            <a href="https://wa.me/94776313528?text=Hello%20Elite%20Ceylon%20Travels!" target="_blank">Chat on WhatsApp (+94 77 631 3528)</a>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:info@eliteceylon.com">info@eliteceylon.com</a>
                        </li>
                    </ul>

                </div>

            </div>
            
            <!-- Footer Bottom / Copyright -->
            <div class="footer-bottom text-center">
                <p class="mb-0">
                    &copy; <?php echo date("Y"); ?> <strong>Elite Ceylon Travels</strong>. All Rights Reserved. Designed for Luxury & Adventure.<a href="#login" id="admin-login-trigger" class="text-gold ms-2" style="font-size: 0.95rem; opacity: 0.85; transition: opacity 0.3s;" aria-label="Admin Login"><i class="fas fa-lock"></i></a>
                </p>
            </div>
        </div>
    </footer>

    <!-- ==========================================
         CUSTOM LIGHTBOX MODAL OVERLAY
         ========================================== -->
    <div class="lightbox-modal">
        <button class="lightbox-close" aria-label="Close Lightbox"><i class="fas fa-times"></i></button>
        <div class="lightbox-content">
            <img src="" alt="Zoomed view" class="lightbox-img">
        </div>
    </div>

    <!-- ==========================================
         ADMIN LOGIN MODAL OVERLAY
         ========================================== -->
    <?php 
    $autoOpenLogin = (isset($_GET['admin_login']) && $_GET['admin_login'] == '1') || (isset($_GET['msg']) && $_GET['msg'] === 'password_reset');
    $isPasswordReset = (isset($_GET['msg']) && $_GET['msg'] === 'password_reset');
    ?>
    <div class="admin-login-modal <?php echo $autoOpenLogin ? 'active' : ''; ?>" id="adminLoginModal" data-bs-theme="dark">
        <div class="admin-login-box">
            <button class="admin-login-close" id="closeAdminLogin" aria-label="Close Login"><i class="fas fa-times"></i></button>
            <div class="admin-login-header">
                <i class="fas fa-shield-halved text-gold fs-2 mb-3"></i>
                <h3>Admin Portal</h3>
                <p>Enter your credentials to access the console</p>
            </div>
            <form id="adminLoginForm" novalidate>
                <div class="mb-3 text-start">
                    <label for="adminUsername" class="form-label">Username</label>
                    <input type="text" class="form-control" id="adminUsername" name="username" required placeholder="Enter username">
                </div>
                <div class="mb-4 text-start">
                    <label for="adminPassword" class="form-label">Password</label>
                    <div class="position-relative">
                        <input type="password" class="form-control pe-5" id="adminPassword" name="password" required placeholder="Enter password">
                        <button type="button" id="toggleAdminPassword" class="position-absolute end-0 top-50 translate-middle-y btn btn-link text-secondary p-0 me-3" style="border: none; background: none; text-decoration: none; outline: none; box-shadow: none; z-index: 10;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div id="loginFeedback" class="alert <?php echo $isPasswordReset ? 'alert-success' : 'alert-danger d-none'; ?> mb-3" role="alert">
                    <?php if ($isPasswordReset): ?>
                        <i class="fas fa-check-circle me-1"></i> Your password has been changed successfully. Please sign in with your new credentials.
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-gold w-100">Sign In</button>
            </form>
        </div>
    </div>

    <!-- ==========================================
         SUBMIT REVIEW MODAL OVERLAY
         ========================================== -->
    <div class="admin-login-modal" id="writeReviewModal" data-bs-theme="dark">
        <div class="admin-login-box">
            <button class="admin-login-close" id="closeWriteReview" aria-label="Close Form"><i class="fas fa-times"></i></button>
            <div class="admin-login-header">
                <i class="fas fa-edit text-gold fs-2 mb-2"></i>
                <h3>Submit Review</h3>
                <p>Share your Ceylon experience with us</p>
            </div>
            <form id="writeReviewForm" novalidate>
                <div class="mb-2 text-start">
                    <label for="reviewAuthorName" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="reviewAuthorName" name="author_name" required placeholder="John Doe">
                </div>
                <div class="mb-2 text-start">
                    <label for="reviewAuthorInfo" class="form-label">Description / Country</label>
                    <input type="text" class="form-control" id="reviewAuthorInfo" name="author_info" required placeholder="Solo Traveler · UK">
                </div>
                <div class="mb-2 text-start">
                    <label for="reviewTourPackage" class="form-label">Tour Package Taken (Optional)</label>
                    <select class="form-control form-select" id="reviewTourPackage" name="tour_package" style="background-image: url('data:image/svg+xml,%3csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 16 16\'%3e%3cpath fill=\'none\' stroke=\'%23ffffff\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'m2 5 6 6 6-6\'/%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 16px 12px; color-scheme: dark;">
                        <option value="">-- Select a Package (Optional) --</option>
                        <option value="Sigiriya Fortress">Sigiriya Fortress (Cultural & Historical Heritage)</option>
                        <option value="Ella Highlands">Ella Highlands (Scenic Train & Tea Estates)</option>
                        <option value="Yala Wildlife Safari">Yala Wildlife Safari (Leopard & Wildlife)</option>
                        <option value="Pasikuda Bay Sanctuary">Pasikuda Bay Sanctuary (Luxury Beach & Spa)</option>
                        <option value="Kandy Sacred Heritage">Kandy Sacred Heritage (Temple & Gardens)</option>
                        <option value="Weligama Surf Retreat">Weligama Surf Retreat (Luxury Coastal & Surf)</option>
                        <option value="Custom Luxury Itinerary">Custom Luxury Itinerary</option>
                    </select>
                </div>
                <div class="mb-2 text-start">
                    <label class="form-label d-block">Rating</label>
                    <div class="star-rating-select">
                        <input type="radio" id="star5" name="rating" value="5" checked /><label for="star5" title="5 stars"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 stars"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 stars"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 stars"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 star"><i class="fas fa-star"></i></label>
                    </div>
                </div>
                <div class="mb-3 text-start">
                    <label for="reviewText" class="form-label">Your Review</label>
                    <textarea class="form-control" id="reviewText" name="review_text" rows="3" required placeholder="Write your travel experience here..."></textarea>
                </div>
                <div id="reviewFeedback" class="alert alert-danger d-none mb-3" role="alert"></div>
                <button type="submit" class="btn btn-gold w-100">Submit Review</button>
            </form>
        </div>
    </div>

    <!-- ==========================================
         PACKAGE DETAILS POPUP MODAL
         ========================================== -->
    <div class="package-details-modal" id="packageDetailsModal" data-bs-theme="dark">
        <div class="package-details-dialog">
            <button class="package-modal-close" id="closePackageModal" aria-label="Close Modal"><i class="fas fa-times"></i></button>
            
            <div class="package-modal-hero">
                <img id="pkgModalImg" src="assets/images/home 3.jpg" alt="Tour Package Hero">
                <div class="package-modal-hero-overlay"></div>
                <div class="package-modal-hero-badges">
                    <span id="pkgModalTag" class="badge-pill-tag">Best Seller</span>
                    <span id="pkgModalDuration" class="badge-pill-duration"><i class="fas fa-clock me-1"></i> 7 Days / 6 Nights</span>
                </div>
            </div>

            <div class="package-modal-body">
                <div class="package-modal-header">
                    <h2 id="pkgModalTitle" class="package-modal-title">Tour Package Details</h2>
                </div>

                <div class="package-modal-section">
                    <h4><i class="fas fa-compass text-gold me-2"></i>Tour Overview</h4>
                    <p id="pkgModalDesc" class="package-modal-desc"></p>
                </div>

                <div class="package-modal-section" id="pkgModalItinerarySection">
                    <h4><i class="fas fa-map-location-dot text-gold me-2"></i>Full Itinerary Highlights</h4>
                    <ul id="pkgModalItineraryList" class="package-itinerary-list"></ul>
                </div>

                <div class="package-modal-section" id="pkgModalInclusionsSection">
                    <h4><i class="fas fa-circle-check text-gold me-2"></i>Package Inclusions</h4>
                    <div id="pkgModalInclusionsTags" class="package-inclusions-wrap"></div>
                </div>

                <div class="package-modal-actions">
                    <a id="pkgModalBookBtn" href="contact.php" class="btn btn-gold">
                        <i class="fas fa-paper-plane me-2"></i> Book This Tour
                    </a>
                    <a id="pkgModalWhatsappBtn" href="#" target="_blank" class="btn btn-outline-gold">
                        <i class="fab fa-whatsapp me-2"></i> Inquire on WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================
         FLOATING ACTION WIDGETS (REVIEW & WHATSAPP)
         ========================================== -->
    <div class="floating-widgets-stack">
        <!-- Floating Review Widget -->
        <div class="floating-widget-item review-widget">
            <span class="floating-label">Write a Review</span>
            <button type="button" class="floating-action-btn review-btn open-review-modal" id="floatingReviewBtn" aria-label="Write a Review" title="Write a Review">
                <i class="fas fa-pen-to-square"></i>
            </button>
        </div>

        <!-- Floating WhatsApp Widget -->
        <div class="floating-widget-item whatsapp-widget">
            <span class="floating-label">Chat with Us</span>
            <a href="https://wa.me/94776313528?text=Hello%20Elite%20Ceylon%20Travels!%20I%20am%20interested%20in%20booking%20a%20tour%20package." class="floating-action-btn whatsapp-btn" target="_blank" rel="noopener" aria-label="Chat on WhatsApp" title="Chat on WhatsApp">
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>
    </div>

    <!-- JavaScript / CDNs -->
    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6Rzsn9gE4g1WleQ59oRL9r3OIV5G1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j" crossorigin="anonymous"></script>
    <!-- AOS Animations JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Swiper.js JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <!-- Main Custom JS Script -->
    <script src="assets/js/main.js?v=6.0"></script>

</body>
</html>
