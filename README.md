# Iswarya Silks – Peelamedu, Coimbatore (ஐஸ்வர்யா சில்க்ஸ்)
### Luxury Handloom Silk Saree Brand Showcase & WhatsApp Concierge Website

A production-grade, bespoke brand showcase and one-to-one WhatsApp enquiry platform built for **Iswarya Silks**, an authentic handloom silk saree manufacturer headquartered in Peelamedu, Coimbatore, Tamil Nadu.

---

## 🏛️ Project Philosophy & Scope Boundaries

- **Brand Showcase & Catalog, NOT E-Commerce:**  
  There is **no shopping cart, no checkout, no orders table, and no payment gateway (no Razorpay)**.
- **Conversion Action:**  
  Every saree card, collection page, and detail page features a prominent **"Enquire on WhatsApp"** conversion action. Tapping this triggers a server-side snapshot of the saree's name, SKU, and effective price, logs an enquiry record, and opens `https://wa.me/{whatsapp_number}?text=...` with a personalized pre-composed message. Subsequent consultation and sales happen one-to-one via WhatsApp.
- **Built for Rapid Daily Updates:**  
  As fresh loom cuts arrive daily at the Peelamedu showroom, the administrative portal provides a **Quick Add** workflow that publishes a new saree in **under 30 seconds** with auto-generated race-safe SKUs (`IC-XXXX`), multi-photo drag-and-drop, and inline one-tap status toggles.

---

## 💎 Tech Stack & Architecture

- **Backend Framework:** Laravel 11.x / 12.x on PHP 8.2+
- **Database:** MySQL 8.0+ with Eloquent ORM, soft deletes, and indexed query optimizations
- **Frontend Reactive UI:** Blade + Livewire 3/4 + Alpine.js (zero React / Inertia overhead)
- **Styling & Design System:** Tailwind CSS with custom luxury tokens:
  - **Primary Maroon:** `#b1474b` (Hover: `#8f1f23`, Tint: `#fbebee`)
  - **Heritage Gold:** `#c59b27` (Light: `#fef9e7`)
  - **Warm Backgrounds:** `#ffffff`, `#fdf7f3`, `#f8f6f4`
  - **Typography:** Google Font **Jost** (300/400/500/600/700)
  - **Buttons & Cards:** Pill buttons (`rounded-full`), `shadow-sm` → `shadow-md` on hover, image hover-swap to secondary photo
  - **Indian Currency Grouping:** Custom helper formatting prices as `₹42,500`
- **Image Pipeline:** Intervention Image v3 with PHP GD driver (validates MIME/size, EXIF auto-orient, 4:5 cover crop, WebP output at 400px thumb, 900px medium, 1600px large, plus automatic cascade deletion)
- **Authentication & Security:**
  - Laravel Fortify (headless) for password flows
  - Swappable OTP service behind `OtpSenderInterface` (`EmailOtpSender` active, ready for SMS/WhatsApp)
  - Unified `users` table with backed enum `UserRole` (`customer`, `admin`)
  - Dedicated `/admin` guard and session
  - Account lockout after 5 consecutive failed attempts for 15 minutes (`failed_login_attempts`, `locked_until`)
  - Forced password change on first admin login (`must_change_password`)

---

## 🗄️ Database Schema & Enums

- **`users`**: id, name, email, phone, password, role (`customer`|`admin`), email_verified_at, phone_verified_at, must_change_password, failed_login_attempts, locked_until, last_login_at, timestamps.
- **`otp_codes`**: id, identifier, purpose (`login`|`register`|`forgot_password`), otp_hash, attempts, expires_at, verified_at, created_at. *Raw OTPs are never persisted.*
- **`categories`**: id, parent_id, name, slug, group (`weave`|`fabric`|`occasion`|`kanjeevaram`|`other`), description, cover_image, banner_image, show_on_homepage, sort_order, is_active, soft deletes, timestamps. *Mega-menu category tree is cached and automatically busted on model mutations.*
- **`products`**: id, sku (`IC-0001` with race-safe atomic generator), name, slug, short_description, description, colour, price, discount_price, price_note, availability (`available`|`sold_out`|`on_request`), stock_quantity, status (`draft`|`published`|`archived`), is_featured, published_at, view_count, specs (`zari_type`, `blouse_piece`, `care`, `weave_technique`), soft deletes, timestamps.
- **`category_product`**: Pivot table with composite primary key (`category_id`, `product_id`).
- **`product_images`**: id, product_id, path (1600px WebP), medium_path (900px), thumb_path (400px), is_primary, sort_order, timestamps.
- **`wishlists`**: id, user_id, product_id, timestamps.
- **`enquiries`**: id, user_id, product_id, product_name, product_sku, product_price, customer_name, customer_phone, source (`product_page`|`card`|`wishlist`|`web_form`), page_url, status (`new`|`contacted`|`closed`), admin_note, timestamps.
- **`banners`**: id, title, eyebrow, subtitle, desktop_image, mobile_image, link_url, button_text, sort_order, is_active, timestamps.
- **`newsletter_subscribers`**: id, email, subscribed_at, unsubscribed_at.
- **`video_appointments`**: id, user_id, name, phone, preferred_date, preferred_time, note, status (`pending`|`confirmed`|`completed`|`cancelled`), timestamps.
- **`settings`**: key-value store for `whatsapp_number`, `store_address`, `instagram_url`, `announcement_text`.

---

## 🚀 Setup & Local Installation

### 1. Prerequisites
- PHP 8.2 or 8.3/8.4 with `gd`, `pdo_mysql`, `curl`, `mbstring`, `openssl`, `bcmath` extensions
- Composer 2.x
- Node.js 18+ & NPM
- MySQL 8.0+

### 2. Clone & Environment Configuration
```bash
# Clone the repository
git clone <repository_url> iswarya_creations
cd iswarya_creations

# Install PHP dependencies
composer install

# Install NPM dependencies
npm install

# Copy environment file
cp .env.example .env
```

### 3. Configure Database (.env)
Edit your `.env` to match your local MySQL configuration:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=iswarya_silks
DB_USERNAME=root
DB_PASSWORD=root

FILESYSTEM_DISK=public
ISWARYA_WHATSAPP_NUMBER=918973948912
ISWARYA_SKU_PREFIX=IC-

ADMIN_DEFAULT_EMAIL=admin@iswaryasilks.com
ADMIN_DEFAULT_PASSWORD=Iswarya@Admin2026
```

### 4. Initialize Database & Seeders
```bash
# Generate application encryption key
php artisan key:generate

# Create public storage symlink for WebP images
php artisan storage:link

# Run migrations and seed sample luxury sarees, categories, banners & settings
php artisan migrate:fresh --seed

# Build frontend production assets
npm run build
```

---

## 🔐 Default Seeded Credentials

### 1. Admin Portal (`/admin`)
- **Login URL:** `/admin/login`
- **Email:** `admin@iswaryasilks.com`
- **Password:** `Iswarya@Admin2026`
- **Note:** Upon first login, the admin is redirected to the `/admin/forced-password-change` screen to set a permanent password.

### 2. Demo Customer Account (`/login`)
- **Login URL:** `/login`
- **Email:** `priya@example.com`
- **Password:** `Secret123!`
- **Phone:** `+919876543210`
- **Alternative:** Instant OTP login using any email or mobile number. In local development (`MAIL_MAILER=log`), the 6-digit OTP code is written to `storage/logs/laravel.log`.

---

## 🧪 Running Automated Tests

Run the full PHPUnit test suite covering WhatsApp enquiry logging, admin lockout, OTP hashing, image pipeline 4:5 WebP generation, and category auto-listing:

```bash
php artisan test
```

### Test Coverage Highlights
1. `tests/Feature/EnquiryTest.php`:
   - Server-side enquiry record snapshotting
   - WhatsApp redirect URL generation with Indian digit grouping (`₹42,500`)
   - Null price clause omission for "Price on request" items
   - Automatic customer name/phone autofill for authenticated users
2. `tests/Feature/AdminLockoutTest.php`:
   - 5 failed attempts trigger 15-minute brute-force lockout
   - Lockout rejection even if valid password is supplied while locked
   - Attempt counter reset upon successful sign-in
   - Forced password change on first admin login
   - Rejection of customer users accessing admin portal
3. `tests/Feature/OtpFlowTest.php`:
   - 6-digit OTP generation and secure hashing (never stored plain)
   - Successful verification and automatic customer auto-provisioning
   - Invalid OTP rejection with attempt tracking
4. `tests/Feature/ImageOptimizationServiceTest.php`:
   - Generation of 3 WebP variants (400px thumb, 900px medium, 1600px large) cropped to 4:5
   - Storage file cleanup upon product image deletion
5. `tests/Feature/CategoryAutoListingTest.php`:
   - Newly created published products automatically appear on category page (`/{group}/{slug}`)
   - Draft products are excluded from public catalog
   - Sarees linked to multiple category groups appear on all relevant pages

---

## 🛠️ Code Styling & Linting

Format all code to project conventions using Laravel Pint:
```bash
vendor/bin/pint
```

---

## 📦 Production Deployment Notes

1. **Queues:**  
   High-resolution saree photo processing is dispatched to `ProcessProductImage`. In production, run the queue worker daemon:
   ```bash
   php artisan queue:work --tries=3 --timeout=90
   ```
2. **Caching:**  
   Before going live, compile and cache routes, configuration, and views:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
3. **WhatsApp Number Updates:**  
   Store managers can update the brand's active WhatsApp phone number anytime in `/admin/settings` without code redeployment.
