<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Explanation 

# 🍽️ Food Ordering System — Complete Explanation (From Zero)

---

## 🔷 PART 1: What is Laravel?

Think of building a website like building a house. You could build it brick by brick from scratch — or you could use a ready-made construction kit that already has walls, doors, and plumbing built in.

**Laravel is that construction kit for websites.** It is a **PHP framework** — meaning it is a collection of pre-written code that helps you build websites faster and more securely.

**PHP** is a programming language that runs on the server (the computer that powers the website). When you visit a website and it shows your name after you log in — that's PHP talking to a database and sending back your data.

**Laravel specifically gives you:**

- A system to handle URLs (so `/menu` shows the menu page, `/cart` shows the cart)
- A system to talk to the database without writing raw SQL
- A login/logout system out of the box
- A template engine to build HTML pages
- Built-in security (protection from hacks)

This project uses **Laravel 11** — the latest version.

---

## 🔷 PART 2: What Technologies Are Used in This Project?

Think of it as different tools for different jobs:

| Tool | What It Does | Real-Life Analogy |
|------|-------------|-------------------|
| **Laravel 11 (PHP)** | The brain — handles all logic | Chef in the kitchen |
| **MySQL** | The database — stores all data | The filing cabinet |
| **phpMyAdmin** | A visual tool to see/manage the database | A window into the filing cabinet |
| **Blade Templates** | HTML pages with dynamic PHP data | Printed forms with fill-in blanks |
| **Bootstrap 5** | Makes the website look nice on all screen sizes | Ready-made clothing style |
| **JavaScript + Vite** | Makes things interactive without page reloads | The live updates you see without refreshing |
| **Google Maps API** | Shows maps, detects location | Google Maps inside your app |
| **Browser Geolocation API** | Gets your GPS coordinates from your device | The "Share my location" popup |
| **Razorpay / PayU** | Online payment processing | Like a card swipe machine online |
| **Laravel Breeze** | Pre-built login/register system | A ready-made door lock system |
| **Eloquent ORM** | Talks to database using PHP code instead of SQL | A translator between PHP and MySQL |
| **Laravel Migrations** | Creates database tables using code | Blueprint for the filing cabinet |

---

## 🔷 PART 3: The 3 Types of Users (Roles)

This system has **3 different types of users**, and each sees a completely different part of the website:

**1. Customer** — The person who orders food. They browse restaurants, add to cart, pay, and track their order.

**2. Hotelier** — The restaurant owner. They manage their menu, accept orders, and update delivery status.

**3. Admin** — The platform owner (like the person who runs Zomato itself). They approve new restaurants, block users, and see all revenue reports.

When you register, the system saves your `role` in the database as either `customer`, `hotelier`, or `admin`. Every page you visit checks your role before letting you in.

---

## 🔷 PART 4: The Folder Structure — Where Does Each File Live?

Imagine a large office building. Each floor has a different department. In Laravel, the folder structure organizes code the same way:

```
food-ordering/
│
├── app/                        ← The brain of the application
│   ├── Http/
│   │   ├── Controllers/        ← The managers (handle requests)
│   │   │   ├── Auth/           ← Login & Register logic
│   │   │   ├── Customer/       ← Browse, Cart, Order, Profile
│   │   │   ├── Hotelier/       ← Dashboard, Menu, Orders
│   │   │   ├── Admin/          ← User management, Reports
│   │   │   └── Api/            ← GPS, Nearby Hotels, Delivery Price
│   │   └── Middleware/         ← Security guards on each route
│   ├── Models/                 ← One file per database table
│   └── Services/               ← Helper logic (GPS math, price calc)
│
├── database/
│   └── migrations/             ← Code that creates DB tables
│
├── resources/
│   └── views/                  ← All HTML pages (Blade templates)
│       ├── customer/           ← Customer-facing pages
│       ├── hotelier/           ← Restaurant pages
│       └── admin/              ← Admin pages
│
├── routes/
│   ├── web.php                 ← All page routes
│   └── api.php                 ← GPS/Location API routes
│
└── .env                        ← Secret config (DB password, API keys)
```

### Detailed Folder Breakdown from Project Plan

```
📁 food-ordering/  →  Laravel project root
 📂 app/  →  Core application logic
   📂 Http/Controllers/  →  All controllers by role
     📄 Auth/  →  LoginController, RegisterController
     📄 Customer/  →  BrowseController, CartController, OrderController, ProfileController
     📄 Hotelier/  →  DashboardController, MenuController, OrderManageController, ProfileController
     📄 Admin/  →  UserController, HotelierController, ReportController
     📄 Api/  →  LocationController, NearbyHotelController, DeliveryPriceController
   📂 Http/Middleware/  →  Route protection by role
     📄 RoleMiddleware.php  →  Checks session role → redirects if unauthorized
     📄 HotelierApproved.php  →  Blocks hotelier if not yet admin-approved
   📂 Models/  →  Eloquent models (one per DB table)
     📄 User.php  →  users table — all roles
     📄 CustomerProfile.php  →  customer_profiles table
     📄 HotelierProfile.php  →  hotelier_profiles table
     📄 FoodItem.php  →  food_items table
     📄 Order.php  →  orders table
     📄 OrderItem.php  →  order_items table
     📄 Cart.php  →  cart table
     📄 DeliveryPricingSlab.php  →  delivery_pricing_slabs table
     📄 Payment.php  →  payments table
     📄 Review.php  →  reviews table
   📂 Services/  →  Business logic (not in controllers)
     📄 LocationService.php  →  Haversine formula, distance calculation
     📄 DeliveryPriceService.php  →  Slab lookup, dynamic charge calculator
     📄 PaymentService.php  →  Razorpay / PayU gateway integration
     📄 NearbyHotelService.php  →  SQL query for restaurants within radius
 📂 database/  →  DB migrations, seeders, factories
   📂 migrations/  →  One file per table — version controlled
     📄 create_users_table.php  →  users table schema
     📄 create_hotelier_profiles_table.php  →  hotelier_profiles schema + lat/lng
     📄 create_delivery_pricing_slabs_table.php  →  slab-based pricing table
     📄 create_orders_table.php  →  orders with distance & delivery charge
     📄 ...etc  →  One migration per DB table
   📂 seeders/  →  Seed test data for development
     📄 HotelierSeeder.php  →  Sample restaurants with locations
     📄 FoodItemSeeder.php  →  Sample menu items per restaurant
 📂 resources/  →  Blade views + CSS + JS assets
   📂 views/  →  Blade template files
     📄 layouts/  →  app.blade.php, hotelier.blade.php, admin.blade.php
     📄 auth/  →  login.blade.php, register-customer.blade.php, register-hotelier.blade.php
     📄 customer/  →  browse.blade.php, menu.blade.php, cart.blade.php, checkout.blade.php, orders.blade.php, profile.blade.php
     📄 hotelier/  →  dashboard.blade.php, menu.blade.php, orders.blade.php, profile.blade.php, pricing.blade.php
     📄 admin/  →  dashboard.blade.php, hoteliers.blade.php, users.blade.php, reports.blade.php
   📂 js/  →  app.js, location.js, cart.js (Vite bundled)
   📂 css/  →  app.css, custom styles per role
 📂 routes/  →  All application routes
   📄 web.php  →  Customer & Hotelier routes with role middleware
   📄 api.php  →  Location API, nearby hotels, delivery price endpoints
   📄 admin.php  →  Admin-only routes (separate file)
 📂 public/  →  Publicly accessible files
   📄 uploads/hotels/  →  Hotel logos and banners
   📄 uploads/food/  →  Food item images
   📄 build/  →  Vite compiled CSS + JS assets
 📂 config/  →  Laravel config files
   📄 services.php  →  Google Maps API key, Payment gateway keys
   📄 database.php  →  MySQL connection (reads from .env)
   📄 .env  →  DB creds, APP_KEY, Maps key, Payment keys
   📄 artisan  →  php artisan commands (migrate, serve, etc.)
```

---

## 🔷 PART 5: The MVC Pattern — How Laravel Thinks

Laravel uses **MVC** — Model, View, Controller. This is how every request flows:

```
User clicks "View Menu"
        ↓
   ROUTE (web.php)
   "This URL → send to MenuController"
        ↓
   CONTROLLER (MenuController.php)
   "Get the menu items from DB"
        ↓
   MODEL (FoodItem.php)
   "Here are the items from the database"
        ↓
   VIEW (menu.blade.php)
   "Here is the HTML page shown to user"
```

**Model** = Represents database data. `FoodItem.php` talks to the `food_items` table.

**View** = The HTML page the user sees. Written in Blade (Laravel's template language).

**Controller** = The middleman. Gets data from Model, sends it to View.

---

## 🔷 PART 6: The Database — All Tables Explained

The database is stored in **MySQL**. Think of it as a big Excel spreadsheet — each table is a separate sheet with rows and columns.

### `users` Table — Shared Login for All Roles

| Field | Type | Description |
|-------|------|-------------|
| user_id | INT PK AI | Unique user ID |
| role | ENUM | 'customer' / 'hotelier' / 'admin' |
| name | VARCHAR(100) | Full name |
| email | VARCHAR(100) UNIQUE | Login email |
| password | VARCHAR(255) | Bcrypt hashed password |
| phone | VARCHAR(15) | Contact number |
| profile_image | VARCHAR(255) | Profile photo path |
| status | ENUM | 'active' / 'blocked' |
| created_at | TIMESTAMP | Registration timestamp |

Every person who registers (customer, hotelier, admin) gets one row here. The `role` column says what type they are. Password is stored encrypted (hashed) — not as plain text.

---

### `customer_profiles` Table

| Field | Type | Description |
|-------|------|-------------|
| profile_id | INT PK AI | Unique ID |
| user_id | INT FK | Links to users table |
| default_address | TEXT | Primary delivery address |
| city | VARCHAR(100) | Customer city |
| pincode | VARCHAR(10) | Area pincode |
| latitude | DECIMAL(10,8) | Customer live/saved latitude |
| longitude | DECIMAL(11,8) | Customer live/saved longitude |
| loyalty_points | INT | Reward points earned |
| preferred_payment | ENUM | 'cod' / 'online' / 'wallet' |
| date_of_birth | DATE | For birthday offers |

Extra info about the customer: their address, city, GPS coordinates (latitude/longitude), loyalty points. The `user_id` column links this back to the `users` table.

---

### `hotelier_profiles` Table

| Field | Type | Description |
|-------|------|-------------|
| hotelier_id | INT PK AI | Unique hotel ID |
| user_id | INT FK | Links to users table |
| hotel_name | VARCHAR(150) | Restaurant name |
| hotel_logo | VARCHAR(255) | Logo image path |
| description | TEXT | About the restaurant |
| cuisine_type | VARCHAR(100) | e.g. Indian, Chinese, Italian |
| address | TEXT | Physical address |
| city | VARCHAR(100) | City |
| latitude | DECIMAL(10,8) | Hotel latitude (for distance calc) |
| longitude | DECIMAL(11,8) | Hotel longitude (for distance calc) |
| opening_time | TIME | Opens at (e.g. 09:00) |
| closing_time | TIME | Closes at (e.g. 22:00) |
| is_open | TINYINT(1) | Live open/closed toggle |
| delivery_radius_km | DECIMAL(5,2) | Maximum delivery range in km |
| base_delivery_charge | DECIMAL(10,2) | Charge for nearest zone |
| per_km_charge | DECIMAL(10,2) | Extra cost per km |
| free_delivery_above | DECIMAL(10,2) | Order amount for free delivery |
| max_delivery_charge | DECIMAL(10,2) | Cap on delivery fee |
| avg_delivery_time | INT | Minutes (e.g. 30) |
| minimum_order | DECIMAL(10,2) | Minimum cart value |
| gstin | VARCHAR(20) | Tax registration number |
| is_verified | TINYINT(1) | Admin approved flag |
| status | ENUM | 'pending' / 'approved' / 'suspended' |

Extra info about restaurants: name, logo, address, GPS location, delivery radius, opening/closing times, whether they're approved by admin (`is_verified`), and status.

---

### `delivery_pricing_slabs` Table — Per Hotelier

| Field | Type | Description |
|-------|------|-------------|
| slab_id | INT PK AI | Unique slab ID |
| hotelier_id | INT FK | Links to hotelier_profiles |
| min_km | DECIMAL(5,2) | Range start (e.g. 0) |
| max_km | DECIMAL(5,2) | Range end (e.g. 3) |
| delivery_charge | DECIMAL(10,2) | Charge for this range |
| estimated_time_min | INT | ETA in minutes |

Each restaurant sets different delivery prices based on distance. For example: 0–2km = ₹20, 2–5km = ₹40, 5–10km = ₹70. Each row is one price band.

---

### `customer_saved_addresses` Table

| Field | Type | Description |
|-------|------|-------------|
| address_id | INT PK AI | Unique address ID |
| user_id | INT FK | Links to users |
| label | ENUM | 'home' / 'work' / 'other' |
| address_line | TEXT | Full street address |
| city | VARCHAR(100) | City name |
| pincode | VARCHAR(10) | Pincode |
| latitude | DECIMAL(10,8) | Saved latitude |
| longitude | DECIMAL(11,8) | Saved longitude |
| is_default | TINYINT(1) | Default flag (1 = default) |

---

### `categories` Table

| Field | Type | Description |
|-------|------|-------------|
| category_id | INT PK AI | Unique category ID |
| hotelier_id | INT FK | Links to hotelier (each hotel has own categories) |
| name | VARCHAR(100) | e.g. Pizza, Starters, Drinks |
| image | VARCHAR(255) | Category icon/image |
| status | TINYINT(1) | Active=1, Hidden=0 |

---

### `food_items` Table

| Field | Type | Description |
|-------|------|-------------|
| item_id | INT PK AI | Unique item ID |
| hotelier_id | INT FK | Linked restaurant |
| category_id | INT FK | Linked category |
| name | VARCHAR(150) | Food item name |
| description | TEXT | Description / ingredients |
| price | DECIMAL(10,2) | Item price |
| image | VARCHAR(255) | Food image path |
| is_available | TINYINT(1) | Show/hide on menu |
| is_veg | TINYINT(1) | 1=Veg, 0=Non-veg |
| created_at | TIMESTAMP | Date added |

Every dish the restaurant sells. Has name, price, image, veg/non-veg flag, and whether it's currently available.

---

### `cart` Table

| Field | Type | Description |
|-------|------|-------------|
| cart_id | INT PK AI | Unique cart entry |
| user_id | INT FK | Customer |
| item_id | INT FK | Food item |
| quantity | INT | Number of items |
| added_at | TIMESTAMP | Time added to cart |

When you add food to cart, it's stored here with your `user_id`, the `item_id`, and quantity. It's temporary — cleared after order is placed.

---

### `orders` Table — The Most Important Table

| Field | Type | Description |
|-------|------|-------------|
| order_id | INT PK AI | Unique order ID |
| user_id | INT FK | Customer who placed order |
| hotelier_id | INT FK | Restaurant |
| total_amount | DECIMAL(10,2) | Food subtotal |
| delivery_charge | DECIMAL(10,2) | Calculated delivery fee |
| grand_total | DECIMAL(10,2) | total + delivery |
| delivery_lat | DECIMAL(10,8) | Delivery GPS latitude |
| delivery_lng | DECIMAL(11,8) | Delivery GPS longitude |
| distance_km | DECIMAL(6,2) | Calculated distance (Haversine) |
| estimated_delivery_time | INT | Minutes at time of order |
| delivery_address | TEXT | Full delivery address text |
| status | ENUM | 'pending' / 'confirmed' / 'preparing' / 'out_for_delivery' / 'delivered' / 'cancelled' |
| payment_method | ENUM | 'cod' / 'online' |
| payment_status | ENUM | 'pending' / 'paid' / 'failed' |
| created_at | TIMESTAMP | Order placed time |

When you place an order, one row is created here containing: which customer, which restaurant, total amount, delivery charge, delivery GPS location, distance in km, estimated time, and current status.

---

### `order_items` Table

| Field | Type | Description |
|-------|------|-------------|
| order_item_id | INT PK AI | Unique ID |
| order_id | INT FK | Links to orders |
| item_id | INT FK | Links to food_items |
| quantity | INT | Quantity ordered |
| unit_price | DECIMAL(10,2) | Price at time of order |
| subtotal | DECIMAL(10,2) | quantity × unit_price |

The individual food items inside an order. One row per dish per order. Stores price at the time of ordering (so even if the restaurant changes price later, your receipt stays correct).

---

### `reviews` Table

| Field | Type | Description |
|-------|------|-------------|
| review_id | INT PK AI | Unique review |
| user_id | INT FK | Customer reviewer |
| hotelier_id | INT FK | Restaurant being reviewed |
| item_id | INT FK | Specific food item (optional) |
| rating | TINYINT | 1–5 stars |
| comment | TEXT | Review text |
| created_at | TIMESTAMP | Review date |

After delivery, customers can leave a star rating (1–5) and comment for the restaurant or specific dish.

---

### `payments` Table

| Field | Type | Description |
|-------|------|-------------|
| payment_id | INT PK AI | Unique payment ID |
| order_id | INT FK | Links to orders |
| transaction_id | VARCHAR(100) | Gateway transaction reference |
| amount | DECIMAL(10,2) | Amount paid |
| method | ENUM | 'cod' / 'online' |
| status | ENUM | 'pending' / 'success' / 'failed' |
| paid_at | TIMESTAMP | Payment timestamp |

Every payment transaction is logged here with the transaction ID from the payment gateway, amount, method (COD or online), and status.

---

## 🔷 PART 7: Authentication — How Login Works

This is handled by **Laravel Breeze** — a pre-built login system.

**Setup Command:**
```bash
composer require laravel/breeze && php artisan breeze:install
```

### Registration Flow

**Customer Registration Fields:**
- Name, Email, Password, Confirm Password
- Phone Number, City, Pincode
- Auto-detect location OR manual address entry
- Role auto-set to `'customer'` in DB

**Hotelier Registration Fields:**
- Owner Name, Email, Password, Phone
- Restaurant Name, Cuisine Type, Description
- Address, City, Pincode
- Google Maps pin for exact latitude/longitude
- GSTIN number, Bank account details
- Logo upload, Banner image upload (stored via Laravel Storage)
- Status set to `'pending'` — Admin must approve before login

### Login & Session Management

- Laravel's `Auth::attempt()` function checks email + password
- If correct, it starts a **session** (like a temporary ID badge for your browser)
- A **Middleware** called `RoleMiddleware` checks your role and sends you to the right dashboard
- Route groups protected by `auth` + `RoleMiddleware`
- Logout via `POST /logout` (Laravel Breeze default)
- Password reset via Laravel built-in email notification

**What is Middleware?** It's a security guard that sits between the URL and the page. Before you reach `/hotelier/dashboard`, the middleware checks: "Are you logged in? Are you a hotelier? Are you approved?" If any check fails, it redirects you away.

---

## 🔷 PART 8: GPS Tracking — How Location Works

This is one of the most interesting parts. Here's exactly what happens step by step:

### Step 1 — Browser Asks for Permission

When the customer opens the website, JavaScript runs this code:

```javascript
navigator.geolocation.getCurrentPosition(function(position) {
    let lat = position.coords.latitude;
    let lng = position.coords.longitude;
    // Send to server
});
```

This is the **Browser Geolocation API** — it's built into every modern browser. It asks your device (phone or laptop) for your current GPS coordinates.

### Step 2 — Coordinates Sent to Server

If the user clicks "Allow", the latitude and longitude (e.g., `23.0225, 72.5714` for Ahmedabad) are sent to a Laravel API route (`/api/nearby-hotels`).

### Step 3 — Finding Nearby Restaurants Using Haversine Formula

The server uses the **Haversine Formula** to calculate the distance between the customer's location and every restaurant's location stored in the database.

**What is Haversine?** It's a math formula from navigation science that calculates the distance (in km) between two GPS points on Earth. Since Earth is a sphere, you can't just subtract coordinates — you need this formula to get accurate distance.

Here's the actual code from the project:

```php
// app/Services/LocationService.php
namespace App\Services;

class LocationService {
    public function getDistanceKm($lat1, $lon1, $lat2, $lon2): float {
        $R = 6371; // Earth's radius in km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $R * $c; // distance in km
    }
}

// Inject in Controller:
// public function __construct(private LocationService $location) {}
// $distance = $this->location->getDistanceKm($lat1,$lon1,$lat2,$lon2);
```

It takes two pairs of coordinates (customer's lat/lng and restaurant's lat/lng) and returns distance in km.

### Step 4 — Filter Restaurants by Radius

Only restaurants whose distance is less than their `delivery_radius_km` are shown to the customer.

### Step 5 — Calculate Delivery Charge Using Slab Logic

```php
// app/Services/DeliveryPriceService.php
use App\Models\DeliveryPricingSlab;

$slab = DeliveryPricingSlab::where('hotelier_id', $hotelierId)
    ->where('min_km', '<=', $distance)
    ->where('max_km', '>', $distance)
    ->first();

if (!$slab) {
    return ['deliverable' => false];
}

return [
    'deliverable'    => true,
    'charge'         => $slab->delivery_charge,
    'estimated_time' => $slab->estimated_time_min,
];
```

This finds the right price band and returns the delivery charge. For example, if you're 3.5km away, it finds the "2–5km" slab and charges ₹40.

### Example Delivery Pricing Slabs

| Distance Range | Delivery Charge | Est. Time | Notes |
|---------------|----------------|-----------|-------|
| 0 – 2 km | ₹20 | 15–20 min | Nearby zone |
| 2 – 5 km | ₹40 | 30–35 min | Standard zone |
| 5 – 10 km | ₹70 | 45–50 min | Extended zone |
| 10+ km | N/A | — | Outside radius |

### How GPS is Stored in the Database

- **Restaurant's location:** stored in `hotelier_profiles.latitude` and `hotelier_profiles.longitude` when they register (they drop a pin on a Google Map)
- **Customer's location:** stored in `customer_profiles.latitude` and `customer_profiles.longitude` and also in the `orders` table (`delivery_lat`, `delivery_lng`) at the time of ordering
- The `orders` table also stores `distance_km` — the calculated Haversine result

### Complete GPS Flow Summary

```
1. Customer opens website → Browser requests GPS permission
2. If allowed: lat/lng captured and stored in session
3. PHP sends coordinates to nearby_hotels API
4. SQL Haversine query finds all hotels within their radius
5. Results sorted by distance (nearest first)
6. Each hotel card shows calculated delivery charge for that customer
```

---

## 🔷 PART 9: How the Hotelier Panel Works

Once approved by admin, the hotelier logs in and gets their own dashboard:

### 4.1 Dashboard
- Total orders today / this week / this month
- Revenue summary with charts
- Pending orders count (highlighted)
- Quick toggle: Open / Closed for today

### 4.2 Restaurant Profile Management
- Edit hotel name, description, cuisine, timings
- Logo and banner images stored via Laravel Storage (public disk)
- Set delivery radius (km)
- Configure delivery pricing slabs (0–2km, 2–5km, 5–10km etc.)
- Set minimum order amount, free delivery threshold
- Update GPS coordinates via embedded Google Map

### 4.3 Menu Management
- Add / Edit / Delete food categories via Eloquent Model (Category)
- Add / Edit / Delete food items via FoodItem Model (name, price, image, veg flag)
- Toggle item availability instantly (AJAX + Laravel route)
- Bulk upload menu via CSV using Laravel Import

**What is AJAX?** AJAX means JavaScript sends a request to the server in the background and updates only that button, without reloading the whole page. So when a hotelier toggles an item on/off, only that button updates — not the entire page.

### 4.4 Order Management
- Live order feed (auto-refresh every 30 seconds)
- Accept or Reject incoming orders
- Update status: Confirmed → Preparing → Out for Delivery → Delivered
- View order details: items, customer address, distance, delivery charge
- Print invoice/receipt

---

## 🔷 PART 10: How the Customer Panel Works

### 5.1 Location Detection (On First Load)
- Browser prompts: "Allow location access?"
- If YES: Auto-capture latitude/longitude via Geolocation API
- If NO: Show manual city/address input form
- Location saved in session + `customer_profiles` table
- Option to switch location anytime

### 5.2 Browse Restaurants
- Show only restaurants within delivery radius of customer
- Sort by: Distance (nearest first) / Rating / Delivery Time
- Filter by: Cuisine type / Veg only / Open now / Price range
- Each card shows: Name, cuisine, distance, delivery charge, ETA, rating
- Search bar to find specific restaurant or dish

### 5.3 Restaurant Menu Page
- Show hotel banner, info, timing, rating
- Categories listed as tabs (Starters, Main, Drinks...)
- Each item shows: image, name, price, veg/non-veg badge
- Add to Cart button with quantity selector
- Floating cart summary at bottom

### 5.4 Cart & Checkout
- View all cart items with quantities and subtotals
- Auto-calculate: subtotal + delivery charge = grand total
- Change delivery address (use saved address or enter new one)
- Delivery charge updates dynamically when address changes (JavaScript calls server and updates price without reloading)
- Choose payment: COD or Online
- Place Order → order saved to DB → hotelier notified

**When you click "Place Order":**
1. Cart items are moved to `order_items` table
2. An `orders` row is created with all details
3. Cart is cleared
4. Hotelier is notified (via page auto-refresh on their end)

### 5.5 Order Tracking
- Live status page: Pending → Confirmed → Preparing → Out for Delivery → Delivered
- Estimated delivery time countdown
- Order summary with all items and charges
- Cancel order (only when status is `'pending'`)

### 5.6 Reviews & Profile
- Rate & review delivered orders (1–5 stars + comment)
- View past orders and reorder
- Manage saved addresses (Home, Work, Other)
- View and redeem loyalty points

---

## 🔷 PART 11: Real-Time Notifications — How the Hotelier Gets Notified

The project uses a simple but effective method: **auto-refresh polling**.

Every 30 seconds, the hotelier's order page automatically asks the server "Are there any new orders?" and refreshes the list. This is called **polling**.

This is done with JavaScript:

```javascript
setInterval(function() {
    // Reload the orders section every 30 seconds
    location.reload();
    // OR use AJAX to fetch only new orders
}, 30000);
```

When a customer places an order, the `orders` table gets a new row with `status = 'pending'`. On the next poll (within 30 seconds), the hotelier's dashboard shows it highlighted.

This is not true real-time (like WhatsApp messages), but it's simple, reliable, and sufficient for a food ordering system. True real-time would require **WebSockets** (a permanent connection between browser and server) — that's a more advanced addition not in this version.

---

## 🔷 PART 12: Admin Panel — The Control Center

The admin has a separate login (`/admin/login`).

### 7.1 Admin Dashboard
- Platform stats: total users, hoteliers, orders, revenue
- Pending hotelier approvals (highlighted)
- Recent activity feed

### 7.2 Hotelier Management
- View all pending hoteliers → Approve or Reject with reason
- View active/suspended hoteliers
- Suspend or reactivate hotelier accounts
- View hotelier's menu, orders, and revenue

**Approve Flow:** New restaurants sit with `status = 'pending'`. Admin reviews and clicks Approve (changes to `approved`) or Reject (with a reason). Only after approval can the hotelier log in.

### 7.3 Customer Management
- View all registered customers
- Block/unblock customer accounts (changes `users.status` to `blocked`)
- View customer order history

### 7.4 Order & Revenue Reports
- All orders with filters: date, status, hotelier, customer
- Revenue charts: daily, weekly, monthly
- Export reports as CSV / PDF

---

## 🔷 PART 13: Payment Integration

### 8.1 Cash on Delivery (COD)
- Order placed immediately, `payment_status = 'pending'`
- Hotelier marks as paid on delivery

### 8.2 Online Payment (Razorpay / PayU)
- Customer clicks "Pay Now" → redirected to Razorpay's payment page
- Customer enters card/UPI details on Razorpay's secure servers (not yours)
- On success: Razorpay sends back a `transaction_id`, which is saved in the `payments` table and `payment_status = 'paid'`
- On failure: order is marked failed, customer is notified
- **Webhook**: Razorpay also sends a silent server-to-server confirmation in the background, just in case the browser closed mid-payment. Laravel has a route to handle this.

### 8.3 Payment Table Tracking
- Every transaction logged with `transaction_id`, `amount`, `method`, `status`
- Admin can view all payment records and reconcile

---

## 🔷 PART 14: Security — How the App Is Protected

### CSRF Protection
Every form on the website has a hidden secret token. If a hacker tries to submit a form from outside your website, the token won't match and Laravel rejects it.

- Laravel automatically uses prepared statements via Eloquent ORM
- CSRF protection enabled by default on all POST forms (`VerifyCsrfToken` middleware)

### Password Hashing
Passwords are never stored as plain text. Laravel uses `bcrypt` — a one-way scrambling algorithm. So even if someone hacks the database, they can't read passwords.

```php
// Password is stored like this:
Hash::make('mypassword123')
// Becomes something like: $2y$10$92IXUNpkj...
// Can never be reversed back to original
```

### SQL Injection Protection
Eloquent ORM automatically uses "prepared statements" — meaning user input is always treated as data, never as SQL code. A hacker can't type `'; DROP TABLE users;` in a form and destroy your database.

### XSS Protection
In Blade templates, `{{ $variable }}` automatically escapes dangerous HTML characters. So a hacker can't inject JavaScript into your pages through form fields.

### Role Middleware
Every route is protected. If a customer tries to visit `/hotelier/dashboard`, middleware catches them and redirects them away.

### Security Checklist (from Project)
- Laravel automatically uses prepared statements via Eloquent ORM
- CSRF protection enabled by default on all POST forms
- Passwords hashed with `Laravel Hash::make()` (bcrypt)
- Route model binding and authorization via Laravel Policies
- File upload validation using Laravel Request rules (mimes, max size)
- Files stored via Laravel Storage (not directly in public folder)
- Role-based route protection via custom `RoleMiddleware`
- XSS protection via Blade's `{{ }}` auto-escaping

---

## 🔷 PART 15: How All the Pieces Connect — The Big Picture

```
Customer's Browser
       ↓ (HTTP Request)
   web.php (Routes)
       ↓
   RoleMiddleware (Is this the right user type?)
       ↓
   Controller (e.g. CartController)
       ↓
   Eloquent Model (e.g. Cart, FoodItem)
       ↓
   MySQL Database (read/write data)
       ↓
   Controller sends data to View
       ↓
   Blade Template (HTML + data mixed together)
       ↓
   Browser shows the page to the user
```

### For GPS:
```
Browser Geolocation API
       ↓
   JavaScript captures lat/lng
       ↓
   Laravel API route (/api/nearby-hotels)
       ↓
   LocationService (Haversine formula calculates distance)
       ↓
   DeliveryPriceService (slab lookup → delivery charge)
       ↓
   JSON response back to JavaScript
       ↓
   Page updates dynamically (no reload)
```

### For Orders:
```
Customer places order
       ↓
   orders table gets new row (status = 'pending')
       ↓
   Hotelier's page polls every 30 seconds
       ↓
   Hotelier sees new order → Accepts → Updates status
       ↓
   Customer refreshes tracking page → Sees updated status
```

### For Payments:
```
Customer clicks "Pay Now"
       ↓
   Redirect to Razorpay payment page
       ↓
   Customer pays (card/UPI)
       ↓
   Razorpay redirects back to site with transaction_id
       ↓
   Laravel saves transaction_id in payments table
       ↓
   payment_status = 'paid'
       ↓
   (Also: Razorpay sends webhook to server as backup confirmation)
```

---

## 🔷 PART 16: Deployment — How It Goes Live

### 10.1 Hosting Setup
- Purchase VPS or shared hosting with PHP 8.2+ support
- Upload project via Git (recommended) or FTP
- Run: `composer install --optimize-autoloader --no-dev`
- Run: `php artisan migrate --force` on production
- Run: `php artisan storage:link` for public file access
- Set correct `APP_ENV=production` and `APP_DEBUG=false` in `.env`
- Set document root to `/public` folder in Apache/Nginx config

### 10.2 Google Maps API Setup
- Create project in Google Cloud Console
- Enable: Maps JavaScript API, Geocoding API, Places API
- Generate API key and restrict to your domain
- Add key to `config/constants.php`

### 10.3 Final Checks
- Test all features on live server
- Run: `php artisan config:cache && php artisan route:cache`
- Set `APP_DEBUG=false` and `APP_ENV=production` in `.env`
- Enable HTTPS (SSL certificate via Let's Encrypt)
- Set up daily database backups (mysqldump or Laravel Backup package)
- Monitor Laravel logs at `storage/logs/laravel.log` after launch
- Add Laravel Telescope (dev) or Sentry (prod) for error tracking

---


## 🔷 PART 17: Testing Checklist

- Registration & login for all three roles
- GPS detection and manual address entry
- Distance calculation accuracy (compare with Google Maps)
- Delivery charge slab logic for different distances
- Add to cart, update quantity, remove item
- Checkout with dynamic charge update on address change
- Full order lifecycle: place → confirm → prepare → deliver
- Payment flow (COD and online)
- Review submission and display
- Admin hotelier approval workflow
- Mobile responsiveness on all pages
- Cross-browser testing (Chrome, Firefox, Edge, Safari)

---

## 🔷 PART 18: Summary — Everything in One Line Each

| Component | What It Does |
|-----------|-------------|
| **Laravel** | The PHP framework that powers the entire backend |
| **MySQL** | Database that stores all users, orders, food, payments |
| **Blade** | Template engine that creates the HTML pages |
| **Bootstrap** | Makes it look nice on phone and desktop |
| **Migrations** | Code files that create database tables |
| **Eloquent ORM** | PHP classes that talk to database without raw SQL |
| **RoleMiddleware** | Security guard that checks your role on every page |
| **Google Maps API** | Shows maps and converts addresses to coordinates |
| **Browser Geolocation API** | Gets your GPS from your device |
| **Haversine Formula** | Math that calculates distance between two GPS points |
| **Delivery Pricing Slabs** | Distance-based price bands per restaurant |
| **Laravel Breeze** | Pre-built login/register system |
| **Razorpay/PayU** | Online payment gateway |
| **Polling (every 30 sec)** | How hotelier gets notified of new orders |
| **CSRF / bcrypt / Eloquent** | Security layers that protect the system |
| **Vite** | Bundles and compiles JavaScript and CSS assets |
| **Laravel Storage** | Handles file uploads (images) safely off the public folder |
| **Services (LocationService, DeliveryPriceService)** | Reusable business logic classes separate from controllers |
| **Seeders** | Fake test data inserted into the database during development |
| **Artisan** | Laravel's command-line tool (`php artisan migrate`, `php artisan serve`) |

---

> This project is essentially a **mini Zomato** — built on Laravel 11, taking about 10–11 weeks from planning to deployment. Every feature — from GPS detection to payment to admin approval — is handled by its own controller, model, and view, all connected through routes and protected by middleware.