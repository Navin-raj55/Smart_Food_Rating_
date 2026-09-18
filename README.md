# 🥗 Smart Food Rating (SFR)

A web application designed to help consumers make healthier grocery choices by analyzing packaged food products. Users can search for food products, scan product barcodes using their device camera in real-time, view detailed ingredient breakdowns and health impact ratings, and manage their personal favorite items.

---

## 🌟 Key Features

- 🔍 **Live Product Search**: Search through packaged products instantly using an interactive search bar linked to JSON product metadata.
- 📷 **Real-Time Barcode Scanner**: Integrated camera scanner using **QuaggaJS** supporting EAN, UPC, Code 128, and Code 39 barcode standards.
- 📊 **Comprehensive Health & Taste Ratings**: Detailed rating matrix evaluating:
  - 🥗 Nutritional Value
  - ❤️ Health Impact
  - 😋 Taste Score
  - 🔍 Ingredients Transparency
  - ⭐ Overall Health Rating
- 🔐 **User Authentication**: Secure signup and login system using PHP session management and bcrypt password hashing.
- ❤️ **Personalized Favorites**: Save favorite products to a personalized dashboard backed by MySQL database storage.
- 📱 **Responsive & Animated UI**: Modern, glassmorphism-inspired user interface featuring ambient background glow and smooth CSS animations.

---

## 🏗️ Tech Stack

- **Frontend**: HTML5, CSS3 (Custom styling, Flexbox/Grid layouts, CSS keyframe animations), JavaScript (ES6, Fetch API, AJAX)
- **Barcode Scanning Library**: [QuaggaJS](https://github.com/serratus/quaggaJS) (Camera stream decoder)
- **Backend**: PHP 8.x (Session management, PDO & MySQLi database integrations)
- **Database**: MySQL / MariaDB
- **Data Format**: JSON (`products.json` dataset)

---

## 📁 Repository Structure

```
ININI/
├── assert/                  # Product images, brand logos, icons, and UI media assets
├── products.json            # Centralized dataset containing barcode-indexed product details & ratings
├── db_connection.php        # MySQL database connection configuration
├── register.php             # User registration handler (password hashing & DB insertion)
├── create.html              # User registration interface
├── login.php                # User login verification (PDO & PHP sessions)
├── loginacc.html            # User login interface
├── landingpage.php          # Main interactive application dashboard & search view
├── qr2.html                 # Live camera barcode scanning interface powered by QuaggaJS
├── maggi.html               # Individual product detail page with rating metrics
├── add_favorite.php         # AJAX endpoint to handle adding items to user favorites
├── favorites.php            # User dashboard displaying saved favorite products
├── profile.html             # User profile overview page
└── README.md                # Project documentation
```

---

## 🗄️ Database Setup (`sfr`)

To enable user accounts and favorites functionality, configure a MySQL/MariaDB server (such as via XAMPP or WAMP) with the following setup:

### 1. Create Database
```sql
CREATE DATABASE IF NOT EXISTS sfr;
USE sfr;
```

### 2. Create Tables
```sql
-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Favorites Table
CREATE TABLE IF NOT EXISTS favorite (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### 3. Database Connection Config
Modify `db_connection.php` and `login.php` if your local database uses a custom username, password, or host:
```php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "sfr";
```

---

## 🚀 How to Run the Project

### Option A: Using PHP Built-in Server (Quick Start)

1. Open your terminal in the project directory:
   ```bash
   cd path/to/ININI
   ```
2. Start the built-in PHP development server:
   ```bash
   php -S localhost:8000
   ```
3. Open your browser and navigate to:
   - Dashboard: `http://localhost:8000/landingpage.php`
   - Login: `http://localhost:8000/loginacc.html`
   - Barcode Scanner: `http://localhost:8000/qr2.html`

### Option B: Using XAMPP / WAMP

1. Move or clone the project folder into your web server root directory:
   - For XAMPP: `C:\xampp\htdocs\ININI`
   - For WAMP: `C:\wamp64\www\ININI`
2. Start **Apache** and **MySQL** from the XAMPP/WAMP Control Panel.
3. Import the SQL table definitions into **phpMyAdmin** (`http://localhost/phpmyadmin`) under database name `sfr`.
4. Access the web app at `http://localhost/ININI/landingpage.php`.

---

## 📷 Using the Barcode Scanner

1. Navigate to the barcode scanner page (`qr2.html` or via the slide control on `landingpage.php`).
2. Allow camera access permissions when prompted by your browser.
3. Point your camera at a food product barcode (e.g. EAN / UPC).
4. The scanner automatically decodes the barcode and redirects to the detailed nutritional breakdown for that item.

---

## 📜 License

This project is open-source and available under the [MIT License](LICENSE).
