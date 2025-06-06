# Laravel Product Manager

A Laravel-based web application for managing products with AJAX form submission, Bootstrap styling, and modal-based editing. The application stores data in a JSON file and displays submitted products in a table with total calculations.

---

## 🚀 Features

- Laravel 12 with Bootstrap 5
- AJAX-based product creation and editing (no page reload)
- Data saved to `storage/app/private/products.json`
- Table displays:
  - Sr No
  - Product Name
  - Quantity in stock
  - Price per item
  - Date submitted
  - Total value (qty × price)
- Table auto-refreshes after submit or update
- Modal pop-up for editing product entries
- Delete product only after user confirmation ✅
- Auto-refresh product table after actions
- Total value sum displayed at the bottom
- Success/error notification with auto-hide after 2 seconds

---

## 🧱 Requirements

- PHP > 8.2
- Composer
- Laravel CLI
- No database needed
---

## 📦 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/laravel-product-manager.git
   cd laravel-product-manager

2. **Install PHP dependencies**
    ```bash
    composer install
    php artisan key:generate

3. **Create .env file**

    cp .env.example .env

    Set session and cache to file in .env 

    SESSION_DRIVER=file
    CACHE_DRIVER=file

4. **Start the Laravel server**
    ```bash
    php artisan serve 

    visit to browser



