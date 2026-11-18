<?php
require_once 'includes/db_connect.php';
try {
    $stmt_all_pkg = $pdo->query("SELECT id, title, duration FROM packages ORDER BY sort_order ASC, id ASC");
    $all_packages = $stmt_all_pkg->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $all_packages = [];
}

$selectedPackageTitle = isset($_GET['package']) ? trim($_GET['package']) : '';

$pageTitle = "Contact Us - Elite Ceylon Travels | Luxury Tour Inquiries & Reservations";
$metaDescription = "Connect directly with Elite Ceylon Travels via WhatsApp, phone, or email. We are available 24/7 to assist with bespoke Sri Lanka tour planning.";
$activePage = "contact";
include 'includes/header.php';
?>

<!-- ==========================================
     CONTACT HERO SECTION
     ========================================== -->
<section class="about-hero" style="background-image: url('assets/images/yala.jpg'); padding-bottom: 8rem;">
    <div class="about-hero-overlay"></div>
    <div class="about-hero-title" data-aos="fade-up">
        <span class="section-subtitle text-gold mb-2 d-inline-block" style="letter-spacing: 0.25em; text-transform: uppercase; font-size: 0.85rem; font-weight: 700;">24/7 Dedicated Support</span>
        <h1 class="display-3 font-serif fw-bold text-light">Contact <span class="text-gold" style="font-style: italic;">Us</span></h1>
        <p class="lead text-light-50 mt-3 mx-auto" style="max-width: 750px; font-size: 1.15rem; opacity: 0.85;">
            Ready to embark on your dream Sri Lankan journey? Reach out to our private travel specialists directly through your preferred channel.
        </p>
    </div>
</section>

<!-- ==========================================
     CONTACT CARD CONTAINER (OVERLAPPING)
     ========================================== -->
<style>
/* Custom styled Left column */
.contact-info-column {
    background: linear-gradient(135deg, #0b221e 0%, #153a33 100%) !important;
    padding: 3rem 2.5rem !important;
}
.contact-info-column h2 {
    color: #ffffff !important;
    font-size: 2.2rem !important;
    font-family: var(--font-serif);
    font-weight: 700;
}
.contact-info-column p {
    color: #a3b8b5 !important;
    font-size: 0.95rem;
    line-height: 1.6;
}
.contact-info-item-flex {
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    padding-bottom: 1.2rem;
    margin-bottom: 1.2rem;
}
.contact-info-item-flex:last-child {
    border-bottom: none;
    padding-bottom: 0;
    margin-bottom: 0;
}
.contact-info-text h4 {
    color: #ffffff !important;
}
.contact-info-text a, .contact-info-text span {
    color: #cbd5e1 !important;
}
.contact-info-text a:hover {
    color: var(--primary-gold) !important;
}
/* WhatsApp Custom Circle */
.whatsapp-circle {
    background-color: rgba(37, 211, 102, 0.15) !important;
    color: #25D366 !important;
    border: 1px solid rgba(37, 211, 102, 0.25) !important;
}
.contact-info-item-flex:hover .whatsapp-circle {
    background-color: #25D366 !important;
    color: #ffffff !important;
}
/* Email Custom Circle */
.email-circle {
    background-color: rgba(255, 255, 255, 0.1) !important;
    color: #ffffff !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
}
.contact-info-item-flex:hover .email-circle {
    background-color: #ffffff !important;
    color: #0c221e !important;
}
/* Default Gold Circle overrides */
.gold-circle {
    background-color: rgba(212, 175, 55, 0.15) !important;
    color: var(--primary-gold) !important;
    border: 1px solid rgba(212, 175, 55, 0.25) !important;
}
.contact-info-item-flex:hover .gold-circle {
    background-color: var(--primary-gold) !important;
    color: #ffffff !important;
}
</style>

<div class="contact-card-container container" style="margin-bottom: 5rem;">
    <div class="row contact-premium-card g-0" data-aos="fade-up" data-aos-delay="100">
        
        <!-- Left: Info Column -->
        <div class="col-lg-5 contact-info-column d-flex flex-column justify-content-between text-white">
            <div>
                <span class="text-gold fw-bold text-uppercase small mb-2 d-block" style="letter-spacing: 0.15em;">Get in Touch</span>
                <h2>Reach Out To Us</h2>
                <p>
                    Speak to our private travel specialists to design your custom-made luxury itinerary. We are available 24/7 to make your dream trip a reality.
                </p>
                
                <ul class="contact-info-items">
                    
                    <!-- WhatsApp Item -->
                    <li class="contact-info-item-flex">
                        <div class="contact-info-icon-circle whatsapp-circle">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div class="contact-info-text">
                            <h4>WhatsApp Chat</h4>
                            <a href="https://wa.me/94776313528" target="_blank">+94 77 631 3528</a>
                        </div>
                    </li>
                    
                    <!-- Phone Item -->
                    <li class="contact-info-item-flex">
                        <div class="contact-info-icon-circle gold-circle">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-info-text">
                            <h4>Direct Call</h4>
                            <a href="tel:+94776313528">+94 77 631 3528</a>
                        </div>
                    </li>
                    
                    <!-- Email Item -->
                    <li class="contact-info-item-flex">
                        <div class="contact-info-icon-circle email-circle">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-info-text">
                            <h4>Email Inquiry</h4>
                            <a href="mailto:info@eliteceylon.com">info@eliteceylon.com</a>
                        </div>
                    </li>

                    <!-- Location Item -->
                    <li class="contact-info-item-flex">
                        <div class="contact-info-icon-circle gold-circle">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-info-text">
                            <h4>Head Office</h4>
                            <span>Galle Road, Colombo, Sri Lanka</span>
                        </div>
                    </li>
                    
                    <!-- Support Item -->
                    <li class="contact-info-item-flex">
                        <div class="contact-info-icon-circle gold-circle">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div class="contact-info-text">
                            <h4>Business Hours</h4>
                            <span>24/7 Dedicated Concierge Support</span>
                        </div>
                    </li>
                    
                </ul>
            </div>

            <!-- Social Media Handles -->
            <div class="mt-4">
                <h5 class="text-white-50 small text-uppercase mb-3" style="letter-spacing: 0.1em; font-weight: 600;">Follow Our Journey</h5>
                <div class="contact-social-row">
                    <a href="https://wa.me/94776313528" target="_blank" class="contact-social-circle" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" target="_blank" class="contact-social-circle" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" target="_blank" class="contact-social-circle" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>

        <!-- Right: Form Column -->
        <div class="col-lg-7 contact-form-column">
            <h2>Plan Your Journey</h2>
            <p class="text-muted small mb-4" id="inquiryHeaderDesc">
                <?php if ($selectedPackageTitle !== ''): ?>
                    Customize your luxury itinerary for <strong class="text-gold"><?php echo htmlspecialchars($selectedPackageTitle); ?></strong>.
                <?php else: ?>
                    Customize your bespoke luxury itinerary for Sri Lanka.
                <?php endif; ?>
            </p>
            
            <div class="travel-inquiry-box" style="padding: 0; background: transparent; border: none; box-shadow: none;">
                <form id="contactInquiryForm" novalidate autocomplete="off">
                    
                    <div id="inquiryFeedback" class="alert alert-danger d-none mb-3 py-2 small" role="alert"></div>

                    <!-- Package Selection Field -->
                    <div class="inquiry-form-section mb-4">
                        <h5 class="section-title-inquiry mb-3">
                            <span class="icon-circle-inquiry"><i class="fas fa-suitcase"></i></span>
                            Selected Package / Tour Type
                        </h5>
                        <div class="col-12">
                            <label for="inquiryPackage" class="form-label small fw-semibold">Package Interest <span class="text-danger">*</span></label>
                            <div class="input-icon-group">
                                <i class="fas fa-umbrella-beach input-icon"></i>
                                <select class="form-select form-control" id="inquiryPackage" name="package_title" required>
                                    <option value="Bespoke Custom Tour" <?php echo ($selectedPackageTitle === '' || $selectedPackageTitle === 'Bespoke Custom Tour') ? 'selected' : ''; ?>>Bespoke Custom Tour (Create My Own)</option>
                                    <?php foreach ($all_packages as $pkg): ?>
                                        <option value="<?php echo htmlspecialchars($pkg['title']); ?>" 
                                            data-duration="<?php echo htmlspecialchars($pkg['duration']); ?>"
                                            <?php echo (strcasecmp($selectedPackageTitle, $pkg['title']) === 0) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($pkg['title']); ?> (<?php echo htmlspecialchars($pkg['duration']); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="invalid-feedback-inline" id="inquiryPackageError"></div>
                        </div>
                    </div>

                    <!-- SECTION: Personal Information -->
                    <div class="inquiry-form-section mb-4">
                        <h5 class="section-title-inquiry mb-3">
                            <span class="icon-circle-inquiry"><i class="fas fa-user"></i></span>
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
                            <span class="icon-circle-inquiry"><i class="fas fa-suitcase"></i></span>
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
                                    <i class="fas fa-gem me-1"></i> Boutique
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

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("contactInquiryForm");
    const inquiryName = document.getElementById("inquiryName");
    const inquiryCountry = document.getElementById("inquiryCountry");
    const inquiryEmail = document.getElementById("inquiryEmail");
    const inquiryWhatsApp = document.getElementById("inquiryWhatsApp");
    const inquiryArrivalDate = document.getElementById("inquiryArrivalDate");
    const inquiryDepartureDate = document.getElementById("inquiryDepartureDate");
    const inquiryPax = document.getElementById("inquiryPax");
    const inquiryRooms = document.getElementById("inquiryRooms");
    const inquiryPackage = document.getElementById("inquiryPackage");
    const inquiryRequests = document.getElementById("inquiryRequests");

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    if (inquiryArrivalDate) inquiryArrivalDate.setAttribute('min', today);
    if (inquiryDepartureDate) inquiryDepartureDate.setAttribute('min', today);

    // Toggle active state for Hotel Selection Buttons
    document.querySelectorAll('.hotel-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        document.querySelectorAll('.hotel-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const hiddenHotelInput = document.getElementById('inquiryHotelCategory');
        if (hiddenHotelInput) {
          hiddenHotelInput.value = this.getAttribute('data-value');
        }
      });
    });

    // Toggle active state for Meal Plan Buttons
    document.querySelectorAll('.meal-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        document.querySelectorAll('.meal-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const hiddenMealInput = document.getElementById('inquiryMealPlan');
        if (hiddenMealInput) {
          hiddenMealInput.value = this.getAttribute('data-value');
        }
      });
    });

    // Helper to toggle validation state classes
    function setFieldState(inputEl, isValid, errorMsg = '') {
      if (!inputEl) return;
      const group = inputEl.closest('.input-icon-group');
      const errorDiv = document.getElementById(inputEl.id + 'Error');
      
      if (isValid) {
        if (group) {
          group.classList.remove('is-invalid');
          group.classList.add('is-valid');
        }
        if (errorDiv) {
          errorDiv.textContent = '';
        }
      } else {
        if (group) {
          group.classList.remove('is-valid');
          group.classList.add('is-invalid');
        }
        if (errorDiv) {
          errorDiv.textContent = errorMsg;
        }
      }
    }

    // Validation functions
    function validateName() {
      const val = inquiryName ? inquiryName.value.trim() : '';
      if (!val) {
        setFieldState(inquiryName, false, 'Name is required.');
        return false;
      }
      setFieldState(inquiryName, true);
      return true;
    }

    function validateCountry() {
      const val = inquiryCountry ? inquiryCountry.value : '';
      if (!val) {
        setFieldState(inquiryCountry, false, 'Please select your country.');
        return false;
      }
      setFieldState(inquiryCountry, true);
      return true;
    }

    function validateEmail() {
      const val = inquiryEmail ? inquiryEmail.value.trim() : '';
      if (!val) {
        setFieldState(inquiryEmail, false, 'Email is required.');
        return false;
      }
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(val)) {
        setFieldState(inquiryEmail, false, 'Please enter a valid email address.');
        return false;
      }
      setFieldState(inquiryEmail, true);
      return true;
    }

    function validateWhatsApp() {
      const val = inquiryWhatsApp ? inquiryWhatsApp.value.trim() : '';
      if (!val) {
        setFieldState(inquiryWhatsApp, false, 'WhatsApp number is required.');
        return false;
      }
      const digitsOnly = val.replace(/\D/g, '');
      if (digitsOnly.length < 7 || digitsOnly.length > 15) {
        setFieldState(inquiryWhatsApp, false, 'Enter a valid WhatsApp number (7-15 digits).');
        return false;
      }
      setFieldState(inquiryWhatsApp, true);
      return true;
    }

    function validateArrivalDate() {
      const val = inquiryArrivalDate ? inquiryArrivalDate.value : '';
      if (!val) {
        setFieldState(inquiryArrivalDate, false, 'Arrival date is required.');
        return false;
      }
      const depVal = inquiryDepartureDate ? inquiryDepartureDate.value : '';
      if (depVal && new Date(val) > new Date(depVal)) {
        setFieldState(inquiryArrivalDate, false, 'Arrival date cannot be after departure date.');
        return false;
      }
      setFieldState(inquiryArrivalDate, true);
      if (inquiryDepartureDate && depVal && new Date(val) <= new Date(depVal)) {
        setFieldState(inquiryDepartureDate, true);
      }
      return true;
    }

    function validateDepartureDate() {
      const val = inquiryDepartureDate ? inquiryDepartureDate.value : '';
      if (!val) {
        setFieldState(inquiryDepartureDate, false, 'Departure date is required.');
        return false;
      }
      const arrVal = inquiryArrivalDate ? inquiryArrivalDate.value : '';
      if (arrVal && new Date(arrVal) > new Date(val)) {
        setFieldState(inquiryDepartureDate, false, 'Departure date cannot be before arrival date.');
        return false;
      }
      setFieldState(inquiryDepartureDate, true);
      if (inquiryArrivalDate && arrVal && new Date(arrVal) <= new Date(val)) {
        setFieldState(inquiryArrivalDate, true);
      }
      return true;
    }

    function validatePax() {
      const val = inquiryPax ? parseInt(inquiryPax.value, 10) : NaN;
      if (isNaN(val) || val < 1) {
        setFieldState(inquiryPax, false, 'Must be 1 or more travelers.');
        return false;
      }
      setFieldState(inquiryPax, true);
      return true;
    }

    // Event listeners
    if (inquiryName) {
      inquiryName.addEventListener('input', validateName);
      inquiryName.addEventListener('blur', validateName);
    }
    if (inquiryCountry) {
      inquiryCountry.addEventListener('change', validateCountry);
      inquiryCountry.addEventListener('blur', validateCountry);
    }
    if (inquiryEmail) {
      inquiryEmail.addEventListener('input', validateEmail);
      inquiryEmail.addEventListener('blur', validateEmail);
    }
    if (inquiryWhatsApp) {
      inquiryWhatsApp.addEventListener('input', validateWhatsApp);
      inquiryWhatsApp.addEventListener('blur', validateWhatsApp);
    }
    if (inquiryArrivalDate) {
      inquiryArrivalDate.addEventListener('change', validateArrivalDate);
      inquiryArrivalDate.addEventListener('blur', validateArrivalDate);
    }
    if (inquiryDepartureDate) {
      inquiryDepartureDate.addEventListener('change', validateDepartureDate);
      inquiryDepartureDate.addEventListener('blur', validateDepartureDate);
    }
    if (inquiryPax) {
      inquiryPax.addEventListener('input', validatePax);
      inquiryPax.addEventListener('blur', validatePax);
    }

    // Dynamic Header Update when package selection changes
    if (inquiryPackage) {
        inquiryPackage.addEventListener('change', function() {
            const selectedVal = this.value;
            const headerDesc = document.getElementById('inquiryHeaderDesc');
            if (headerDesc) {
                if (selectedVal === 'Bespoke Custom Tour') {
                    headerDesc.innerHTML = 'Customize your bespoke luxury itinerary for Sri Lanka.';
                } else {
                    headerDesc.innerHTML = `Customize your luxury itinerary for <strong class="text-gold">${selectedVal}</strong>.`;
                }
            }
        });
    }

    form.addEventListener("submit", function(e) {
        e.preventDefault();

        const feedback = document.getElementById('inquiryFeedback');
        if (feedback) {
            feedback.className = 'alert alert-danger d-none mb-3 py-2 small';
            feedback.textContent = '';
        }

        const isNameValid = validateName();
        const isCountryValid = validateCountry();
        const isEmailValid = validateEmail();
        const isWhatsAppValid = validateWhatsApp();
        const isArrivalValid = validateArrivalDate();
        const isDepartureValid = validateDepartureDate();
        const isPaxValid = validatePax();

        if (!isNameValid || !isCountryValid || !isEmailValid || !isWhatsAppValid || !isArrivalValid || !isDepartureValid || !isPaxValid) {
            if (feedback) {
                feedback.className = 'alert alert-danger mb-3 py-2 small';
                feedback.textContent = 'Please correct the errors in the form before submitting.';
            }

            // Focus first invalid field
            const firstInvalid = [
              { valid: isNameValid, id: 'inquiryName' },
              { valid: isCountryValid, id: 'inquiryCountry' },
              { valid: isEmailValid, id: 'inquiryEmail' },
              { valid: isWhatsAppValid, id: 'inquiryWhatsApp' },
              { valid: isArrivalValid, id: 'inquiryArrivalDate' },
              { valid: isDepartureValid, id: 'inquiryDepartureDate' },
              { valid: isPaxValid, id: 'inquiryPax' }
            ].find(f => !f.valid);

            if (firstInvalid) {
              const inputEl = document.getElementById(firstInvalid.id);
              if (inputEl) inputEl.focus();
            }
            return;
        }

        // Retrieve field values
        const packageTitle = inquiryPackage ? inquiryPackage.value : 'Bespoke Custom Tour';
        const packageDuration = inquiryPackage && inquiryPackage.selectedIndex > 0 ? inquiryPackage.options[inquiryPackage.selectedIndex].getAttribute('data-duration') : 'Custom Duration';
        
        const name = inquiryName.value.trim();
        const country = inquiryCountry.value;
        const email = inquiryEmail.value.trim();
        const whatsapp = inquiryWhatsApp.value.trim();
        const arrivalDate = inquiryArrivalDate.value;
        const departureDate = inquiryDepartureDate.value;
        const pax = inquiryPax.value;
        const rooms = inquiryRooms ? inquiryRooms.value : '';
        const hotelCategory = document.getElementById('inquiryHotelCategory').value;
        const mealPlan = document.getElementById('inquiryMealPlan').value;
        const specialRequests = inquiryRequests ? inquiryRequests.value.trim() : '';

        // Construct beautiful WhatsApp prefilled message
        let waMessage = `Hello Elite Ceylon Travels!\n\n`;
        if (packageTitle === 'Bespoke Custom Tour') {
            waMessage += `I would like to inquire about a custom bespoke travel itinerary for Sri Lanka.\n\n`;
        } else {
            waMessage += `I would like to inquire about booking the *${packageTitle}* (${packageDuration}).\n\n`;
        }
        waMessage += `*Personal Information:*\n`;
        waMessage += `• *Name:* ${name}\n`;
        waMessage += `• *Country:* ${country}\n`;
        waMessage += `• *Email:* ${email}\n`;
        waMessage += `• *WhatsApp Number:* ${whatsapp}\n\n`;
        waMessage += `*Travel Details:*\n`;
        waMessage += `• *Arrival Date:* ${arrivalDate}\n`;
        waMessage += `• *Departure Date:* ${departureDate}\n`;
        waMessage += `• *No. of Pax (Travelers):* ${pax}\n`;
        
        if (rooms) {
          waMessage += `• *No. of Rooms:* ${rooms}\n`;
        }
        
        waMessage += `• *Hotel Category:* ${hotelCategory}\n`;
        waMessage += `• *Meal Plan:* ${mealPlan}\n`;

        if (specialRequests) {
          waMessage += `\n*Other Requirements / Special Requests:*\n${specialRequests}`;
        }

        // Formats safe WhatsApp URL
        const whatsappNumber = "94776313528";
        const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(waMessage)}`;

        // Redirect to WhatsApp
        window.open(whatsappUrl, '_blank');
    });
});
</script>

<?php include 'includes/footer.php'; ?>
