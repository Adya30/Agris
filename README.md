# AGRIS (Agroindustrial System)

AGRIS is a modern agroindustrial e-commerce and partnership platform designed to connect agricultural producers/administrators (Admin) with distribution partners (Agents). Built on the **Laravel 13** framework and **Tailwind CSS v4**, the platform integrates third-party APIs such as **Midtrans**, **Biteship**, **Wilayah.id**, and **Google OAuth** to deliver a secure, automated, and real-time shopping, logistics, and digital payment experience.

---

## 🚀 Key Features

AGRIS provides distinct dashboards and functional features tailored for two primary roles (*Admin* and *Agent*):

### 1. Security & Authentication
- **Registration & OTP Verification**: Account registration with dynamic One-Time Password (OTP) verification codes sent automatically via Email (Laravel Mailer) to validate user authenticity.
- **Google OAuth (Sign In with Google)**: Fast and seamless authentication using Google accounts (Laravel Socialite).
- **Self-Service Password Reset**: Secure password reset flow utilizing token verification links delivered via email.
- **Login Throttling**: Enhanced security to prevent brute-force attacks by limiting consecutive failed login attempts.

### 2. Agent Partnership Module (Kemitraan)
- **Partnership Application**: Agents can apply to become official AGRIS distribution partners.
- **Digital MOU Upload**: Applicants can upload signed Memorandum of Understanding (MOU) documents.
- **Partnership Verification**: Admins have a dedicated dashboard to review, approve/reject applications, and finalize agreements.

### 3. Product Catalog & Inventory Management
- **Categorized Catalog**: Products grouped by specific agricultural categories (e.g., fertilizers, premium rice, pesticides).
- **Admin CRUD & Inventory Control**: Dynamic product management with image uploads, pricing, detailed specifications, and bag weight parameters.
- **Soft Deletes (Trash Bin)**: Includes *Trash*, *Restore*, and *Force Delete* safety nets to prevent accidental data loss.

### 4. Interactive Cart & Transactions
- **Interactive Shopping Cart**: Real-time item additions, quantity updates, and cart deletion prior to checkout.
- **Flexible Shipping/Delivery Options**:
  1. **Courier Shipping**: Integrates third-party logistics services.
  2. **Self-Pickup**: Agents can choose to pick up items directly from the AGRIS main warehouse (Patrang, Jember) to eliminate shipping costs.

### 5. Automated Logistics Integration (Biteship API)
- **Real-Time Postage Cost (Ongkir)**: Integrates the Biteship API to compute exact shipping rates based on total order weight and destination.
- **Live Shipping Tracking**: Real-time parcel tracking via Biteship track links.
- **Automated Webhooks**: Biteship webhook callbacks automatically update order status (`processed` ➔ `shipped` ➔ `completed`).

### 6. Digital Payment Gateway (Midtrans API)
- **Secure Online Payments**: Integrated Midtrans Snap popup supporting Virtual Accounts, E-Wallets (GoPay, ShopeePay), Credit Cards, and QRIS.
- **Offline Payment Simulator**: A testing simulation button in the local/development environment to complete payments without spending real funds.
- **Callback Status Webhook**: Automatic status updates synced from Midtrans (`pending` ➔ `successful` / `failed` / `expired`).
- **Auto Stock Recovery**: Returns reserved items back to the product stock if a payment expires or is canceled.

### 7. Real-Time Chat & Consultation (Live Chat Support)
- **Live Chat**: Directly links Agents and Admins for partner consultations or customer support.
- **WebSocket Engine**: Utilizes Laravel Reverb (local WebSocket server) and Laravel Echo on the frontend to deliver instant message routing without page refreshes.

### 8. Administrative Areas (Wilayah.id API)
- **Hierarchical Address Dropdown**: Automatically synchronizes Indonesian provinces, cities/regencies, sub-districts, and villages dynamically during profile setup to guarantee shipping address accuracy.

### 9. Admin Blog & Reporting Dashboard
- **Agricultural Blog**: Admins can publish agro-tips, product promotions, and company announcements.
- **Financial & Sales Reports**: Detailed transactional summaries and analytics for tracking total revenue, sales volume, and order histories.

---

## 🛠️ API & Third-Party Integrations

The AGRIS application utilizes several external APIs to orchestrate its automated workflows:

1. **Midtrans Payment Gateway API**
   - **Integration Endpoints**: `https://app.sandbox.midtrans.com/snap/v1/transactions` (Sandbox) / `https://app.midtrans.com/snap/v1/transactions` (Production)
   - **Usage**: Snap token generation, payment status queries, cancellations/refunds, and asynchronous webhook handling.
   - **Package**: `midtrans/midtrans-php`

2. **Biteship Courier Aggregator API**
   - **Integration Endpoint**: `https://api.biteship.com/v1` (or sandbox alternative)
   - **Usage**: Area coordinates lookup, shipping rate calculations (JNE, SiCepat, J&T, TIKI, Lion, Ninja, Anteraja), courier booking, live tracking, and shipping status webhooks.
   - **Client**: `Illuminate\Support\Facades\Http`

3. **Wilayah.id API**
   - **Integration Endpoint**: `https://wilayah.id/api`
   - **Usage**: Retrieves dynamic hierarchical lists of Indonesian administrative subdivisions.
   - **Client**: `Illuminate\Support\Facades\Http`

4. **Google OAuth API**
   - **Usage**: Authenticates and registers agents using Google accounts.
   - **Package**: `laravel/socialite`

5. **Laravel Reverb (Pusher Protocol)**
   - **Usage**: Local real-time WebSocket messaging server for the live chat channel.
   - **Packages**: `laravel/reverb` & `pusher/pusher-php-server`

6. **Laravel Mailer (SMTP)**
   - **Usage**: Sends OTP codes and password reset links.

---

## 💻 Tech Stack

- **Backend Framework**: Laravel 13.x (PHP 8.3+)
- **Database**: MySQL / MariaDB (Database Session, Queue, Cache)
- **Frontend Utility**: Tailwind CSS v4, Alpine.js, Axios, AOS (Animate on Scroll)
- **Real-Time Client**: Laravel Echo & Pusher JS
- **Testing Engine**: Pest PHP

---

## ⚙️ Installation & Local Setup

Follow these steps to set up the AGRIS application locally:

### Prerequisites
- PHP >= 8.3
- Composer
- Node.js & NPM
- MySQL / MariaDB Server

### Installation Steps

1. **Clone the Repository & Navigate**
   ```bash
   git clone <repository-url>
   cd Agris
   ```

2. **Run Automatic Setup Script**
   The project includes a predefined script to handle dependency installation and workspace configuration:
   ```bash
   composer run setup
   ```
   *This command runs `composer install`, copies `.env.example` to `.env`, generates the application key (`APP_KEY`), runs database migrations, runs `npm install`, and builds the frontend assets with Vite.*

3. **Configure the Environment (`.env`)**
   Open the `.env` file and set up your database connection:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=agris
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

   Provide credentials for the third-party integrations:
   ```env
   # Midtrans Credentials
   MIDTRANS_SERVER_KEY=your_midtrans_server_key
   MIDTRANS_CLIENT_KEY=your_midtrans_client_key
   MIDTRANS_IS_PRODUCTION=false

   # Biteship Credentials
   BITESHIP_API_KEY=your_biteship_api_key

   # Google Socialite Credentials
   GOOGLE_CLIENT_ID=your_google_client_id
   GOOGLE_CLIENT_SECRET=your_google_client_secret
   GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

   # Mail/SMTP Configuration (for OTP delivery)
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your_mailtrap_username
   MAIL_PASSWORD=your_mailtrap_password
   ```

4. **Seed the Database**
   Run the database seeders to populate initial product categories and establish the default administrator account:
   ```bash
   php artisan db:seed
   ```
5. **Start the Development Servers**
   To spin up the web server, queue listener, Vite asset bundler, and Reverb WebSocket server simultaneously, execute:
   ```bash
   composer run dev
   ```
   Or using NPM:
   ```bash
   npm run dev
   ```
   *Your application will be available at `http://localhost:8000`.*

---

## 🧪 Automated Testing

To run the test suites with Pest PHP:
```bash
composer run test
```
