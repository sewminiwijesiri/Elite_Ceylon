# Elite Ceylon Travels 🇱🇰

> **A modern, full-stack luxury tourism web platform and booking management system built for bespoke Sri Lankan travel itineraries.**

[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=flat-square&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![Leaflet](https://img.shields.io/badge/Leaflet.js-GIS%20Maps-199900?style=flat-square&logo=leaflet&logoColor=white)](https://leafletjs.com/)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=flat-square&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

---

## 🌟 Key Highlights

* **Dynamic Tour Packages & Day-by-Day Itineraries:** Fully database-driven package showcases with duration, inclusions, pricing, and parsed daily schedules.
* **Interactive GIS Route Mapping (Leaflet.js):** Visualizes multi-destination tour circuits on an interactive map using geographic coordinates (Sigiriya, Kandy, Nuwara Eliya, Ella, Yala, Mirissa, etc.).
* **Automated WhatsApp Lead Generator:** Multi-step booking inquiry form with client-side validation that compiles travel preferences into structured, pre-formatted WhatsApp chat payloads.
* **Secure Admin Management Portal:** Role-protected admin dashboard featuring real-time KPI metrics, review moderation workflows (approve, unapprove, delete, bulk actions), and credential management.
* **Asynchronous Review & Testimonial Engine:** Allows travelers to submit feedback via AJAX with pending moderation queues and Swiper.js carousel display.
* **Enterprise Security Standards:** Built-in protection against SQL Injection (PDO Prepared Statements), CSRF attacks (cryptographic session tokens), and XSS (`htmlspecialchars`), alongside Bcrypt password hashing.

---

## 🛠️ Tech Stack

| Layer | Technologies |
| :--- | :--- |
| **Backend** | PHP 8+ (OOP & PDO Architecture) |
| **Database** | MySQL / MariaDB (InnoDB, UTF-8 Unicode) |
| **Frontend** | HTML5, CSS3, JavaScript (ES6+), Bootstrap 5 |
| **Mapping & UI** | Leaflet.js, Swiper.js, AOS (Animate On Scroll), Font Awesome 6 |
| **Security** | Bcrypt / Argon2 Password Hashing, CSRF Tokens, Input Sanitization |

---

## 📂 Project Architecture

```text
Elite_Ceylon/
├── assets/
│   ├── css/              # Custom design system & animations (style.css)
│   ├── js/               # Client-side scripts & handlers (main.js)
│   ├── images/           # Curated high-resolution destination media
│   └── logo/             # Brand identity assets
├── includes/
│   ├── db_connect.php    # PDO database configuration & error handling
│   ├── header.php        # Global header, metadata & navigation
│   └── footer.php        # Global footer & script imports
├── index.php             # Landing page with hero, packages & testimonials
├── package_details.php   # Dynamic package view with Leaflet route map
├── packages.php          # Tour packages listing directory
├── contact.php           # Inquiry generator & WhatsApp booking form
├── gallery.php           # Visual travel gallery with lazy loading
├── reviews.php           # Client testimonials & AJAX review submission
├── admin_dashboard.php   # Protected management dashboard & analytics
├── login_handler.php     # Secure authentication endpoint
├── moderate_review.php   # Review moderation actions (AJAX)
├── update_admin_credentials.php # Profile & password update handler
├── database.sql          # Database schema & sample seed data
└── README.md             # Project documentation
```

---

## 🚀 Getting Started (Local Setup)

### Prerequisites
* [XAMPP](https://www.apachefriends.org/) (Apache & MySQL) or any PHP 8.0+ / MySQL stack.
* Git.

### 1. Clone the Repository
```bash
git clone https://github.com/sewminiwijesiri/Elite_Ceylon.git
cd Elite_Ceylon
```

### 2. Set Up the Database
1. Start **Apache** and **MySQL** in your XAMPP Control Panel.
2. Open `http://localhost/phpmyadmin/`.
3. Create a new database named `elite_ceylon_db`.
4. Import `database.sql` into `elite_ceylon_db`.

### 3. Configure Database Connection (Optional)
If your MySQL credentials differ from the standard XAMPP default (`root` with no password), update `includes/db_connect.php`:
```php
$db_host = 'localhost';
$db_name = 'elite_ceylon_db';
$db_user = 'your_username';
$db_pass = 'your_password';
```

### 4. Launch the Application
Move the project to `c:/xampp/htdocs/Elite_Ceylon` and access it in your browser:
```text
http://localhost/Elite_Ceylon/
```

---

## 🛡️ Security Features
* **SQL Injection Prevention:** 100% of database queries use PDO prepared statements with bound parameters.
* **Cross-Site Request Forgery (CSRF):** Admin actions require a cryptographically generated 32-byte session token validated via `hash_equals()`.
* **Password Encryption:** Admin passwords are encrypted using PHP's native `password_hash()` (Bcrypt).
* **Cross-Site Scripting (XSS):** All dynamic user inputs are sanitized using `htmlspecialchars()`.

---

## 👤 Author
* **Sewmini Wijesiri** - [GitHub Profile](https://github.com/sewminiwijesiri)
