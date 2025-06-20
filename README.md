# 🛠️ ServiceHub - Service Marketplace Platform

**ServiceHub** is a full-stack web application that connects users with verified service providers (plumbers, lawyers, freelancers, mechanics, etc.). It supports secure booking, location-based matching, wallet-based escrow logic, and a future-ready API for mobile apps or blockchain integration.

---

## 📌 Features

- 🔐 User and Provider Authentication (role-based)
- 🗂️ Service Categories & Listings
- 📍 Location-Based Filtering
- 🧾 Booking System with status tracking
- 💰 Wallet-Based Escrow Payment System
- ⭐ Ratings & Reviews
- 🧑‍💼 Admin Panel for Verification & Dispute Handling
- 📱 Responsive Design (Mobile-First)
- 🛠️ Built for Laravel, raw PHP, or hybrid implementation

---

## 🏗️ Tech Stack

| Layer         | Tool/Tech                      |
|---------------|-------------------------------|
| Frontend      | HTML, CSS (Bootstrap), JavaScript (Vanilla / jQuery) |
| Backend       | PHP (Raw or Laravel)          |
| Database      | MySQL                         |
| Payment       | Flutterwave or Paystack       |
| API           | REST (JSON, PHP endpoints)    |
| Storage       | Local File System / Cloudinary (optional) |
| Deployment    | Shared hosting, cPanel, or VPS|

---


---

## 📊 Database Overview

### 🔐 `users` table
- `id`, `name`, `email`, `password`, `role` (client/provider), `location`, `verified`

### 🔧 `services` table
- `id`, `user_id`, `category`, `description`, `price`, `status`

### 📦 `bookings` table
- `id`, `client_id`, `provider_id`, `service_id`, `status`, `scheduled_date`, `confirmed`

### 💼 `wallets` table
- `user_id`, `balance`, `last_updated`

### 💳 `transactions` table
- `id`, `user_id`, `type` (deposit, hold, release), `amount`, `status`, `booking_id`

### ⭐ `ratings` table
- `id`, `user_id`, `provider_id`, `stars`, `comment`

---

## 💸 Wallet + Escrow Logic

1. User funds their wallet (via Flutterwave or Paystack).
2. On booking, the cost is held (`status = 'held'`) in transactions.
3. When the client confirms service completion:
   - Funds move to provider’s wallet (`status = 'released'`).
4. After a 72-hour window (if no action), funds are auto-released via cron job.
5. Providers request withdrawal (manual or automated approval).

---

## 🧪 API Endpoints (Sample)

| Method | Endpoint                      | Description                      |
|--------|-------------------------------|----------------------------------|
| POST   | `/api/register`               | User registration                |
| POST   | `/api/login`                  | User login                       |
| GET    | `/api/services`               | Fetch all service listings       |
| POST   | `/api/bookings/create`        | Book a service                   |
| POST   | `/api/bookings/confirm`       | Confirm job completion           |
| POST   | `/api/wallet/fund`            | Fund user wallet (via API)       |
| POST   | `/api/wallet/withdraw`        | Provider withdraws funds         |

---

## 🚀 Getting Started (Local Setup)

1. **Clone the Repo**
   ```bash
   git clone https://github.com/KabriAcid/servicehub.git
   cd servicehub


