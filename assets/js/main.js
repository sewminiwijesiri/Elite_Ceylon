document.addEventListener('DOMContentLoaded', function() {
  
  // ==========================================
  // STICKY & GLASS NAVBAR ON SCROLL
  // ==========================================
  const navbar = document.querySelector('.navbar-custom');
  
  function handleScroll() {
    if (navbar) {
      if (window.scrollY > 50) {
        navbar.classList.add('navbar-scrolled');
      } else {
        navbar.classList.remove('navbar-scrolled');
      }
    }
  }
  
  window.addEventListener('scroll', handleScroll);
  handleScroll();

  // ==========================================
  // MOBILE NAVBAR TOGGLE & INTERACTION CONTROLLER
  // ==========================================
  const navbarToggler = document.querySelector('.navbar-toggler');
  const mainNavbarCollapse = document.getElementById('mainNavbar');

  if (navbarToggler && mainNavbarCollapse) {
    navbarToggler.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      const isExpanded = navbarToggler.getAttribute('aria-expanded') === 'true';
      if (isExpanded || mainNavbarCollapse.classList.contains('show')) {
        mainNavbarCollapse.classList.remove('show');
        navbarToggler.setAttribute('aria-expanded', 'false');
        navbarToggler.classList.remove('is-active');
      } else {
        mainNavbarCollapse.classList.add('show');
        navbarToggler.setAttribute('aria-expanded', 'true');
        navbarToggler.classList.add('is-active');
      }
    });

    // Close mobile menu when clicking any nav link
    document.querySelectorAll('#mainNavbar .nav-link, #mainNavbar .btn-gold').forEach(link => {
      link.addEventListener('click', function() {
        if (window.innerWidth < 992) {
          mainNavbarCollapse.classList.remove('show');
          navbarToggler.setAttribute('aria-expanded', 'false');
          navbarToggler.classList.remove('is-active');
        }
      });
    });

    // Close when clicking outside navbar
    document.addEventListener('click', function(e) {
      if (window.innerWidth < 992 && mainNavbarCollapse.classList.contains('show')) {
        if (navbar && !navbar.contains(e.target)) {
          mainNavbarCollapse.classList.remove('show');
          navbarToggler.setAttribute('aria-expanded', 'false');
          navbarToggler.classList.remove('is-active');
        }
      }
    });
  }

  // ==========================================
  // CUSTOM IMMERSIVE HERO SLIDER CONTROLLER
  // ==========================================
  const slides = document.querySelectorAll('.hero-slide-item');
  const thumbnails = document.querySelectorAll('.thumbnail-card');
  const prevBtn = document.querySelector('.prev-btn');
  const nextBtn = document.querySelector('.next-btn');
  
  if (slides.length > 0 && thumbnails.length > 0) {
    let currentIndex = 0;
    let autoplayTimer = null;
    const autoplayDelay = 7000;

    function goToSlide(index) {
      // Index wrapping
      if (index >= slides.length) index = 0;
      if (index < 0) index = slides.length - 1;

      // Remove active classes
      slides[currentIndex].classList.remove('active');
      thumbnails[currentIndex].classList.remove('active');

      // Update index
      currentIndex = index;

      // Add active classes
      slides[currentIndex].classList.add('active');
      thumbnails[currentIndex].classList.add('active');

      // Scroll active thumbnail into view smoothly
      const container = document.querySelector('.hero-thumbnails-container');
      const activeThumb = thumbnails[currentIndex];
      if (container && activeThumb) {
        const targetScroll = activeThumb.offsetLeft - (container.offsetWidth / 2) + (activeThumb.offsetWidth / 2);
        container.scrollTo({
          left: targetScroll,
          behavior: 'smooth'
        });
      }

      // Reset autoplay timer
      resetAutoplay();
    }

    function resetAutoplay() {
      if (autoplayTimer) clearInterval(autoplayTimer);
      autoplayTimer = setInterval(function() {
        goToSlide(currentIndex + 1);
      }, autoplayDelay);
    }

    // Prev / Next button listeners
    if (nextBtn) {
      nextBtn.addEventListener('click', function() {
        goToSlide(currentIndex + 1);
      });
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', function() {
        goToSlide(currentIndex - 1);
      });
    }

    // Thumbnail cards click listeners
    thumbnails.forEach(thumb => {
      thumb.addEventListener('click', function() {
        const slideIndex = parseInt(this.getAttribute('data-index'), 10);
        if (!isNaN(slideIndex)) {
          goToSlide(slideIndex);
        }
      });
    });

    // Start first slide timer
    resetAutoplay();
  }

  // ==========================================
  // TESTIMONIAL SWIPER SLIDER
  // ==========================================
  const testimonialSlider = document.querySelector('.testimonials-slider');
  if (testimonialSlider && typeof Swiper !== 'undefined') {
    new Swiper('.testimonials-slider', {
      loop: true,
      autoplay: {
        delay: 6000,
        disableOnInteraction: false,
      },
      speed: 800,
      slidesPerView: 1,
      spaceBetween: 30,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      breakpoints: {
        768: {
          slidesPerView: 2,
        },
        1200: {
          slidesPerView: 3,
        }
      }
    });
  }

  // ==========================================
  // HOMEPAGE GALLERY SWIPER SLIDER
  // ==========================================
  const homeGallerySlider = document.querySelector('.home-gallery-slider');
  if (homeGallerySlider && typeof Swiper !== 'undefined') {
    new Swiper('.home-gallery-slider', {
      loop: true,
      autoplay: {
        delay: 3500,
        disableOnInteraction: false,
      },
      speed: 700,
      slidesPerView: 1.2,
      spaceBetween: 18,
      grabCursor: true,
      pagination: {
        el: '.home-gallery-slider .swiper-pagination',
        clickable: true,
      },
      breakpoints: {
        576: {
          slidesPerView: 2.2,
          spaceBetween: 20,
        },
        992: {
          slidesPerView: 3.2,
          spaceBetween: 24,
        },
        1200: {
          slidesPerView: 4,
          spaceBetween: 24,
        }
      }
    });
  }

  // ==========================================
  // AOS (ANIMATE ON SCROLL) INITIALIZATION
  // ==========================================
  if (typeof AOS !== 'undefined') {
    AOS.init({
      duration: 1000,
      easing: 'ease-out-cubic',
      once: true,
      offset: 120
    });
  }

  // ==========================================
  // LIGHTBOX MULTIMEDIA GALLERY SYSTEM
  // ==========================================
  const lightboxModal = document.querySelector('.lightbox-modal');
  const lightboxImg = document.querySelector('.lightbox-img');
  const lightboxTitle = document.querySelector('.lightbox-title-text');
  const lightboxLoc = document.querySelector('.lightbox-loc-text');
  const lightboxClose = document.querySelector('.lightbox-close');
  const galleryItems = document.querySelectorAll('.gallery-item');

  if (lightboxModal && galleryItems.length > 0) {
    galleryItems.forEach(item => {
      item.addEventListener('click', function() {
        const img = this.querySelector('img');
        const title = this.dataset.title || '';
        const location = this.dataset.location || '';
        const imgSrc = img.src;

        if (lightboxImg) lightboxImg.src = imgSrc;
        if (lightboxTitle) lightboxTitle.textContent = title;
        if (lightboxLoc) lightboxLoc.textContent = location;

        lightboxModal.classList.add('active');
        document.body.style.overflow = 'hidden';
      });
    });

    const closeLightbox = function() {
      lightboxModal.classList.remove('active');
      document.body.style.overflow = '';
      setTimeout(() => {
        if (lightboxImg) lightboxImg.src = '';
      }, 400);
    };

    if (lightboxClose) {
      lightboxClose.addEventListener('click', closeLightbox);
    }
    
    lightboxModal.addEventListener('click', function(e) {
      if (e.target === lightboxModal) {
        closeLightbox();
      }
    });

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && lightboxModal.classList.contains('active')) {
        closeLightbox();
      }
    });
  }

  // ==========================================
  // SMOOTH ANCHOR SCROLLING
  // ==========================================
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const targetId = this.getAttribute('href');
      if (targetId === '#') return;
      
      const targetElement = document.querySelector(targetId);
      if (targetElement) {
        e.preventDefault();
        
        const offset = navbar ? navbar.offsetHeight : 80;
        const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - offset;

        window.scrollTo({
          top: targetPosition,
          behavior: 'smooth'
        });

        const navbarCollapse = document.querySelector('.navbar-collapse');
        if (navbarCollapse && navbarCollapse.classList.contains('show')) {
          const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
          if (bsCollapse) {
            bsCollapse.hide();
          }
        }
      }
    });
  });

  // ==========================================
  // ADMIN LOGIN MODAL CONTROLLER & AJAX
  // ==========================================
  const adminLoginTrigger = document.getElementById('admin-login-trigger');
  const adminLoginModal = document.getElementById('adminLoginModal');
  const closeAdminLogin = document.getElementById('closeAdminLogin');
  const adminLoginForm = document.getElementById('adminLoginForm');
  const loginFeedback = document.getElementById('loginFeedback');

  if (adminLoginTrigger && adminLoginModal) {
    adminLoginTrigger.addEventListener('click', function(e) {
      e.preventDefault();
      adminLoginModal.classList.add('active');
      document.body.style.overflow = 'hidden';
    });

    const closeLogin = function() {
      adminLoginModal.classList.remove('active');
      document.body.style.overflow = '';
      if (adminLoginForm) adminLoginForm.reset();
      if (loginFeedback) {
        loginFeedback.classList.add('d-none');
        loginFeedback.textContent = '';
      }
    };

    if (closeAdminLogin) {
      closeAdminLogin.addEventListener('click', closeLogin);
    }

    let loginMouseDownBackdrop = false;
    adminLoginModal.addEventListener('mousedown', function(e) {
      loginMouseDownBackdrop = (e.target === adminLoginModal);
    });

    adminLoginModal.addEventListener('mouseup', function(e) {
      if (e.target === adminLoginModal && loginMouseDownBackdrop) {
        closeLogin();
      }
      loginMouseDownBackdrop = false;
    });

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && adminLoginModal.classList.contains('active')) {
        closeLogin();
      }
    });

    // Auto-open login modal if arriving from password reset or login redirect
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('admin_login') === '1' || window.location.hash === '#admin-login') {
      adminLoginModal.classList.add('active');
      document.body.style.overflow = 'hidden';
      if (urlParams.get('msg') === 'password_reset' && loginFeedback) {
        loginFeedback.classList.remove('d-none', 'alert-danger');
        loginFeedback.classList.add('alert-success');
        loginFeedback.textContent = 'Your password has been changed successfully. Please sign in with your new credentials.';
      }
    }

    if (adminLoginForm) {
      adminLoginForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(adminLoginForm);

        if (loginFeedback) {
          loginFeedback.classList.add('d-none');
          loginFeedback.textContent = '';
        }

        fetch('login_handler.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            if (loginFeedback) {
              loginFeedback.classList.remove('d-none', 'alert-danger');
              loginFeedback.classList.add('alert-success');
              loginFeedback.innerHTML = `<i class="fas fa-check-circle me-2"></i> ${data.message || 'Login successful! Redirecting...'}`;
            }
            showToastAlert(data.message || 'Login successful! Redirecting...', 'success');
            setTimeout(() => {
              window.location.href = data.redirect || 'admin_dashboard.php';
            }, 1200);
          } else {
            if (loginFeedback) {
              loginFeedback.classList.remove('d-none', 'alert-success');
              loginFeedback.classList.add('alert-danger');
              loginFeedback.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i> ${data.message || 'Invalid credentials.'}`;
            }
            showToastAlert(data.message || 'Invalid credentials. Please try again.', 'error');
          }
        })
        .catch(err => {
          if (loginFeedback) {
            loginFeedback.classList.remove('d-none', 'alert-success');
            loginFeedback.classList.add('alert-danger');
            loginFeedback.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i> Connection error. Please try again.';
          }
          showToastAlert('Connection error. Please try again.', 'error');
        });
      });
    }
  }

  // ==========================================
  // WRITE REVIEW MODAL CONTROLLER & AJAX
  // ==========================================
  const reviewTriggers = document.querySelectorAll('#write-review-btn, #floatingReviewBtn, .open-review-modal');
  const writeReviewModal = document.getElementById('writeReviewModal');
  const closeWriteReview = document.getElementById('closeWriteReview');
  const writeReviewForm = document.getElementById('writeReviewForm');
  const reviewFeedback = document.getElementById('reviewFeedback');

  if (writeReviewModal) {
    reviewTriggers.forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        writeReviewModal.classList.add('active');
        document.body.style.overflow = 'hidden';
      });
    });

    const closeReview = function() {
      writeReviewModal.classList.remove('active');
      document.body.style.overflow = '';
      if (writeReviewForm) writeReviewForm.reset();
      if (reviewFeedback) {
        reviewFeedback.classList.add('d-none');
        reviewFeedback.textContent = '';
      }
    };

    if (closeWriteReview) {
      closeWriteReview.addEventListener('click', closeReview);
    }

    let reviewMouseDownBackdrop = false;
    writeReviewModal.addEventListener('mousedown', function(e) {
      reviewMouseDownBackdrop = (e.target === writeReviewModal);
    });

    writeReviewModal.addEventListener('mouseup', function(e) {
      if (e.target === writeReviewModal && reviewMouseDownBackdrop) {
        closeReview();
      }
      reviewMouseDownBackdrop = false;
    });

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && writeReviewModal.classList.contains('active')) {
        closeReview();
      }
    });

    if (writeReviewForm) {
      writeReviewForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(writeReviewForm);

        if (reviewFeedback) {
          reviewFeedback.classList.add('d-none');
          reviewFeedback.textContent = '';
        }

        fetch('submit_review.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            if (reviewFeedback) {
              reviewFeedback.classList.remove('d-none', 'alert-danger');
              reviewFeedback.classList.add('alert-success');
              reviewFeedback.innerHTML = `<i class="fas fa-check-circle me-2"></i> ${data.message}`;
            }
            showToastAlert(data.message, 'success');
            setTimeout(() => {
              closeReview();
            }, 2500);
          } else {
            if (reviewFeedback) {
              reviewFeedback.classList.remove('d-none', 'alert-success');
              reviewFeedback.classList.add('alert-danger');
              reviewFeedback.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i> ${data.message || 'Submission failed.'}`;
            }
            showToastAlert(data.message || 'Submission failed. Please check your input.', 'error');
          }
        })
        .catch(err => {
          if (reviewFeedback) {
            reviewFeedback.classList.remove('d-none', 'alert-success');
            reviewFeedback.classList.add('alert-danger');
            reviewFeedback.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i> Connection error. Please try again.';
          }
          showToastAlert('Connection error. Please try again.', 'error');
        });
      });
    }
  }

  // ==========================================
  // REVIEW MODERATION ACTION HANDLERS (ADMIN)
  // ==========================================
  document.querySelectorAll('.btn-mod-approve, .btn-mod-delete').forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      
      const id = this.getAttribute('data-id');
      const action = this.classList.contains('btn-mod-approve') ? 'approve' : 'delete';
      
      if (!confirm(`Are you sure you want to ${action} this review?`)) {
        return;
      }
      
      const formData = new FormData();
      formData.append('id', id);
      formData.append('action', action);
      
      fetch('moderate_review.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showToastAlert(data.message, 'success');
          setTimeout(() => window.location.reload(), 600);
        } else {
          showToastAlert(data.message || 'Operation failed.', 'error');
        }
      })
      .catch(err => {
        showToastAlert('Connection error occurred.', 'error');
      });
    });
  });

  // ==========================================
  // GLOBAL FLOATING NOTIFICATION ALERT HELPER
  // ==========================================
  window.showToastAlert = window.showToast = function(message, type = 'success') {
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

  // ==========================================
  // PACKAGE DETAILS POPUP MODAL CONTROLLER
  // ==========================================
  const packageModal = document.getElementById('packageDetailsModal');
  const closePackageModal = document.getElementById('closePackageModal');
  const pkgModalImg = document.getElementById('pkgModalImg');
  const pkgModalTag = document.getElementById('pkgModalTag');
  const pkgModalDuration = document.getElementById('pkgModalDuration');
  const pkgModalTitle = document.getElementById('pkgModalTitle');
  const pkgModalPrice = document.getElementById('pkgModalPrice');
  const pkgModalMinPersons = document.getElementById('pkgModalMinPersons');
  const pkgModalDesc = document.getElementById('pkgModalDesc');
  const pkgModalItinerarySection = document.getElementById('pkgModalItinerarySection');
  const pkgModalItineraryList = document.getElementById('pkgModalItineraryList');
  const pkgModalInclusionsSection = document.getElementById('pkgModalInclusionsSection');
  const pkgModalInclusionsTags = document.getElementById('pkgModalInclusionsTags');
  const pkgModalBookBtn = document.getElementById('pkgModalBookBtn');
  const pkgModalWhatsappBtn = document.getElementById('pkgModalWhatsappBtn');

  if (packageModal) {
    document.querySelectorAll('.open-pkg-modal-btn').forEach(card => {
      card.addEventListener('click', function(e) {
        // Allow user to click anywhere on the card or the button
        e.preventDefault();

        const title = this.getAttribute('data-title') || '';
        const tag = this.getAttribute('data-tag') || 'Best Seller';
        const duration = this.getAttribute('data-duration') || '';
        const price = this.getAttribute('data-price') || '';
        const minPersons = this.getAttribute('data-min') || 'Min 2 Persons';
        const image = this.getAttribute('data-image') || '';
        const desc = this.getAttribute('data-desc') || '';
        const itinerary = this.getAttribute('data-itinerary') || '';
        const inclusions = this.getAttribute('data-inclusions') || '';

        if (pkgModalImg) pkgModalImg.src = image;
        if (pkgModalTag) pkgModalTag.textContent = tag;
        if (pkgModalDuration) pkgModalDuration.innerHTML = `<i class="fas fa-clock me-1"></i> ${duration}`;
        if (pkgModalTitle) pkgModalTitle.textContent = title;
        if (pkgModalPrice) pkgModalPrice.textContent = `$${price}`;
        if (pkgModalMinPersons) pkgModalMinPersons.textContent = minPersons;
        if (pkgModalDesc) pkgModalDesc.textContent = desc;

        // Populate itinerary list
        if (pkgModalItineraryList && pkgModalItinerarySection) {
          pkgModalItineraryList.innerHTML = '';
          if (itinerary && itinerary.trim()) {
            const lines = itinerary.split('\n');
            lines.forEach(line => {
              if (line.trim()) {
                const li = document.createElement('li');
                li.textContent = line.trim();
                pkgModalItineraryList.appendChild(li);
              }
            });
            pkgModalItinerarySection.style.display = 'block';
          } else {
            pkgModalItinerarySection.style.display = 'none';
          }
        }

        // Populate inclusions tags
        if (pkgModalInclusionsTags && pkgModalInclusionsSection) {
          pkgModalInclusionsTags.innerHTML = '';
          if (inclusions && inclusions.trim()) {
            const incs = inclusions.split(',');
            incs.forEach(inc => {
              if (inc.trim()) {
                const span = document.createElement('span');
                span.className = 'inclusion-pill';
                span.innerHTML = `<i class="fas fa-check text-gold"></i> ${inc.trim()}`;
                pkgModalInclusionsTags.appendChild(span);
              }
            });
            pkgModalInclusionsSection.style.display = 'block';
          } else {
            pkgModalInclusionsSection.style.display = 'none';
          }
        }

        // Setup action buttons
        if (pkgModalBookBtn) {
          pkgModalBookBtn.href = `contact.php?package=${encodeURIComponent(title)}`;
        }
        if (pkgModalWhatsappBtn) {
          const text = encodeURIComponent(`Hello Elite Ceylon Travels! I would like to inquire about the "${title}" (${duration}).`);
          pkgModalWhatsappBtn.href = `https://wa.me/94776313528?text=${text}`;
        }

        packageModal.classList.add('active');
        document.body.style.overflow = 'hidden';
      });
    });

    const closePkgModal = function() {
      packageModal.classList.remove('active');
      document.body.style.overflow = '';
    };

    if (closePackageModal) {
      closePackageModal.addEventListener('click', closePkgModal);
    }

    let pkgMouseDownBackdrop = false;
    packageModal.addEventListener('mousedown', function(e) {
      pkgMouseDownBackdrop = (e.target === packageModal);
    });

    packageModal.addEventListener('mouseup', function(e) {
      if (e.target === packageModal && pkgMouseDownBackdrop) {
        closePkgModal();
      }
      pkgMouseDownBackdrop = false;
    });

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && packageModal.classList.contains('active')) {
        closePkgModal();
      }
    });
  }

  // ==========================================
  // TRAVEL INQUIRY POPUP MODAL CONTROLLER
  // ==========================================
  const inquiryModal = document.getElementById('travelInquiryModal');
  const closeInquiryModal = document.getElementById('closeInquiryModal');
  const inquiryForm = document.getElementById('travelInquiryForm');
  const inquiryFeedback = document.getElementById('inquiryFeedback');

  if (inquiryModal) {
    // Open Modal
    document.querySelectorAll('.open-inquiry-modal-btn').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        inquiryModal.classList.add('active');
        document.body.style.overflow = 'hidden';
      });
    });

    // Close Modal Function
    const closeInquiryModalFn = function() {
      inquiryModal.classList.remove('active');
      document.body.style.overflow = '';
      if (inquiryForm) {
        inquiryForm.reset();
        // Clear inline validation highlights and messages
        inquiryForm.querySelectorAll('.input-icon-group').forEach(group => {
          group.classList.remove('is-valid', 'is-invalid');
        });
        inquiryForm.querySelectorAll('.invalid-feedback-inline').forEach(div => {
          div.textContent = '';
        });
      }
      
      // Reset active states for Hotel selection and Meal plan
      document.querySelectorAll('.hotel-btn').forEach(btn => btn.classList.remove('active'));
      const defaultHotelBtn = document.querySelector('.hotel-btn[data-value="Boutique"]');
      if (defaultHotelBtn) defaultHotelBtn.classList.add('active');
      const hiddenHotel = document.getElementById('inquiryHotelCategory');
      if (hiddenHotel) hiddenHotel.value = 'Boutique';

      document.querySelectorAll('.meal-btn').forEach(btn => btn.classList.remove('active'));
      const defaultMealBtn = document.querySelector('.meal-btn[data-value="BB (Bed & Breakfast)"]');
      if (defaultMealBtn) defaultMealBtn.classList.add('active');
      const hiddenMeal = document.getElementById('inquiryMealPlan');
      if (hiddenMeal) hiddenMeal.value = 'BB (Bed & Breakfast)';

      if (inquiryFeedback) {
        inquiryFeedback.className = 'alert alert-danger d-none mb-3 py-2 small';
        inquiryFeedback.textContent = '';
      }
    };

    if (closeInquiryModal) {
      closeInquiryModal.addEventListener('click', closeInquiryModalFn);
    }

    // Close Modal on backdrop click
    let inquiryMouseDownBackdrop = false;
    inquiryModal.addEventListener('mousedown', function(e) {
      inquiryMouseDownBackdrop = (e.target === inquiryModal);
    });
    inquiryModal.addEventListener('mouseup', function(e) {
      if (e.target === inquiryModal && inquiryMouseDownBackdrop) {
        closeInquiryModalFn();
      }
      inquiryMouseDownBackdrop = false;
    });

    // Close Modal on ESC keypress
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && inquiryModal.classList.contains('active')) {
        closeInquiryModalFn();
      }
    });

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

    // Specific input validations
    function validateName() {
      const el = document.getElementById('inquiryName');
      const val = el ? el.value.trim() : '';
      if (!val) {
        setFieldState(el, false, 'Name is required.');
        return false;
      }
      setFieldState(el, true);
      return true;
    }

    function validateCountry() {
      const el = document.getElementById('inquiryCountry');
      const val = el ? el.value : '';
      if (!val) {
        setFieldState(el, false, 'Please select your country.');
        return false;
      }
      setFieldState(el, true);
      return true;
    }

    function validateEmail() {
      const el = document.getElementById('inquiryEmail');
      const val = el ? el.value.trim() : '';
      if (!val) {
        setFieldState(el, false, 'Email is required.');
        return false;
      }
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(val)) {
        setFieldState(el, false, 'Please enter a valid email address.');
        return false;
      }
      setFieldState(el, true);
      return true;
    }

    // Validate WhatsApp number format (checks basic length of 7-15 digits after removing formatting characters)
    function validateWhatsApp() {
      const el = document.getElementById('inquiryWhatsApp');
      const val = el ? el.value.trim() : '';
      if (!val) {
        setFieldState(el, false, 'WhatsApp number is required.');
        return false;
      }
      
      const digitsOnly = val.replace(/\D/g, '');
      if (digitsOnly.length < 7 || digitsOnly.length > 15) {
        setFieldState(el, false, 'Enter a valid WhatsApp number (7-15 digits).');
        return false;
      }
      
      setFieldState(el, true);
      return true;
    }

    function validateArrivalDate() {
      const el = document.getElementById('inquiryArrivalDate');
      const val = el ? el.value : '';
      if (!val) {
        setFieldState(el, false, 'Arrival date is required.');
        return false;
      }
      
      const depEl = document.getElementById('inquiryDepartureDate');
      const depVal = depEl ? depEl.value : '';
      if (depVal && new Date(val) > new Date(depVal)) {
        setFieldState(el, false, 'Arrival date cannot be after departure date.');
        return false;
      }
      
      setFieldState(el, true);
      if (depEl && depVal && new Date(val) <= new Date(depVal)) {
        setFieldState(depEl, true);
      }
      return true;
    }

    function validateDepartureDate() {
      const el = document.getElementById('inquiryDepartureDate');
      const val = el ? el.value : '';
      if (!val) {
        setFieldState(el, false, 'Departure date is required.');
        return false;
      }
      
      const arrEl = document.getElementById('inquiryArrivalDate');
      const arrVal = arrEl ? arrEl.value : '';
      if (arrVal && new Date(arrVal) > new Date(val)) {
        setFieldState(el, false, 'Departure date cannot be before arrival date.');
        return false;
      }
      
      setFieldState(el, true);
      if (arrEl && arrVal && new Date(arrVal) <= new Date(val)) {
        setFieldState(arrEl, true);
      }
      return true;
    }

    function validatePax() {
      const el = document.getElementById('inquiryPax');
      const val = el ? parseInt(el.value, 10) : NaN;
      if (isNaN(val) || val < 1) {
        setFieldState(el, false, 'Must be 1 or more travelers.');
        return false;
      }
      setFieldState(el, true);
      return true;
    }

    // Bind real-time verification listeners
    const fieldsToValidate = [
      { id: 'inquiryName', validator: validateName, event: 'input' },
      { id: 'inquiryCountry', validator: validateCountry, event: 'change' },
      { id: 'inquiryEmail', validator: validateEmail, event: 'input' },
      { id: 'inquiryWhatsApp', validator: validateWhatsApp, event: 'input' },
      { id: 'inquiryArrivalDate', validator: validateArrivalDate, event: 'change' },
      { id: 'inquiryDepartureDate', validator: validateDepartureDate, event: 'change' },
      { id: 'inquiryPax', validator: validatePax, event: 'input' }
    ];

    fieldsToValidate.forEach(field => {
      const inputEl = document.getElementById(field.id);
      if (inputEl) {
        inputEl.addEventListener(field.event, field.validator);
        inputEl.addEventListener('blur', field.validator);
      }
    });

    // Form Submission & WhatsApp formatting
    if (inquiryForm) {
      inquiryForm.addEventListener('submit', function(e) {
        e.preventDefault();

        try {
          if (inquiryFeedback) {
            inquiryFeedback.className = 'alert alert-danger d-none mb-3 py-2 small';
            inquiryFeedback.textContent = '';
          }

          // Run all validations
          const isNameValid = validateName();
          const isCountryValid = validateCountry();
          const isEmailValid = validateEmail();
          const isWhatsAppValid = validateWhatsApp();
          const isArrivalValid = validateArrivalDate();
          const isDepartureValid = validateDepartureDate();
          const isPaxValid = validatePax();

          if (!isNameValid || !isCountryValid || !isEmailValid || !isWhatsAppValid || !isArrivalValid || !isDepartureValid || !isPaxValid) {
            if (inquiryFeedback) {
              inquiryFeedback.className = 'alert alert-danger mb-3 py-2 small';
              inquiryFeedback.textContent = 'Please correct the errors in the form before submitting.';
            }
            
            // Focus the first invalid field
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
          const packageTitle = document.getElementById('inquiryPackageTitle').value;
          const packageDuration = document.getElementById('inquiryPackageDuration').value;
          const name = document.getElementById('inquiryName').value.trim();
          const country = document.getElementById('inquiryCountry').value;
          const email = document.getElementById('inquiryEmail').value.trim();
          const whatsapp = document.getElementById('inquiryWhatsApp').value.trim();
          const arrivalDate = document.getElementById('inquiryArrivalDate').value;
          const departureDate = document.getElementById('inquiryDepartureDate').value;
          const pax = document.getElementById('inquiryPax').value;
          const rooms = document.getElementById('inquiryRooms').value;
          const hotelCategory = document.getElementById('inquiryHotelCategory').value;
          const mealPlan = document.getElementById('inquiryMealPlan').value;
          const specialRequests = document.getElementById('inquiryRequests').value.trim();

          // Construct beautiful WhatsApp prefilled message
          let waMessage = `Hello Elite Ceylon Travels!\n\n`;
          waMessage += `I would like to inquire about booking the *${packageTitle}* (${packageDuration}).\n\n`;
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

          // Attempt window.open, fallback to location.href if blocked
          let newWindow = null;
          try {
            newWindow = window.open(whatsappUrl, '_blank');
          } catch (err) {
            // Log block error
            console.warn("window.open failed, trying window.location fallback:", err);
          }

          if (!newWindow || newWindow.closed || typeof newWindow.closed === 'undefined') {
            // Popup blocker blocked window.open, redirect current tab instead
            window.location.href = whatsappUrl;
          }

          // Close modal and reset form
          closeInquiryModalFn();
          
          // Show success confirmation toast if function exists
          if (typeof showToastAlert === 'function') {
            showToastAlert('Redirecting to WhatsApp with your inquiry details...', 'success');
          }
        } catch (error) {
          console.error("Error in inquiry submission:", error);
          if (inquiryFeedback) {
            inquiryFeedback.className = 'alert alert-danger mb-3 py-2 small';
            inquiryFeedback.textContent = 'An unexpected script error occurred: ' + error.message;
          }
        }
      });
    }
  }

  // ==========================================
  // ADMIN LOGIN PASSWORD FIELD EYE TOGGLE
  // ==========================================
  const toggleAdminPasswordBtn = document.getElementById('toggleAdminPassword');
  const adminPasswordInput = document.getElementById('adminPassword');
  if (toggleAdminPasswordBtn && adminPasswordInput) {
    toggleAdminPasswordBtn.addEventListener('click', function() {
      const type = adminPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      adminPasswordInput.setAttribute('type', type);
      
      // Toggle eye icon class
      const icon = this.querySelector('i');
      if (icon) {
        if (type === 'password') {
          icon.classList.remove('fa-eye-slash');
          icon.classList.add('fa-eye');
        } else {
          icon.classList.remove('fa-eye');
          icon.classList.add('fa-eye-slash');
        }
      }
    });
  }

});

// ==========================================
// PREMIUM PRELOADER CONTROLLER
// ==========================================
(function() {
  var preloaderFailsafe = setTimeout(hidePreloader, 4500);

  function hidePreloader() {
    clearTimeout(preloaderFailsafe);
    var preloader = document.getElementById('preloader');
    if (preloader && !preloader.classList.contains('preloader-fade')) {
      preloader.classList.add('preloader-fade');
      setTimeout(function() {
        preloader.style.display = 'none';
        document.body.classList.remove('preloader-active');
        
        // Refresh AOS animations if AOS is loaded
        if (typeof AOS !== 'undefined') {
          AOS.refresh();
        }
      }, 800); // Wait for the 0.8s CSS transition to complete
    }
  }

  window.addEventListener('load', function() {
    // Add a small extra delay of 300ms so the user can enjoy the entry animation
    setTimeout(hidePreloader, 300);
  });
})();

