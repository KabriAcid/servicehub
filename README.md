# 🛠️ ServiceHub - Final Year Project Guide

**ServiceHub** is a service marketplace platform that connects users with verified service providers. This guide outlines the essential features, implementation steps, and project structure to ensure timely completion.

---

## 📌 Core Features (Streamlined for Expedience)

1. **Authentication**
   - Role-based login (Client/Provider).
   - User registration with email verification.

2. **Service Listings**
   - CRUD operations for services (providers).
   - Search and filter services by category and location.

3. **Booking System**
   - Create and manage bookings.
   - Booking status updates (Pending, Confirmed, Completed).

4. **Wallet System**
   - Fund wallet via payment gateway (Flutterwave or Paystack).
   - Escrow logic for holding and releasing funds.

5. **Ratings & Reviews**
   - Clients can rate and review providers after service completion.

6. **Admin Panel**
   - User verification and dispute resolution.

7. **Responsive Design**
   - Mobile-first design for all pages.

---

## 🏗️ Tech Stack

| Layer         | Tool/Tech                      |
|---------------|-------------------------------|
| Frontend      | HTML, CSS (Bootstrap), JavaScript (Vanilla / jQuery) |
| Backend       | PHP (Raw or Laravel)          |
| Database      | MySQL                         |
| Payment       | Flutterwave or Paystack       |
| API           | REST (JSON, PHP endpoints)    |
| Deployment    | Shared hosting, cPanel, or VPS|

---

## 📊 Database Overview

### 🔐 `users` table
- `id`, `name`, `email`, `password`, `role` (client/provider), `location`, `verified`

### 🔧 `services` table
- `id`, `user_id`, `category`, `description`, `price`, `status`

### 📦 `bookings` table
- `id`, `client_id`, `provider_id`, `service_id`, `status`, `scheduled_date`

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
5. Providers request withdrawal (manual approval).

---

## 🧪 API Endpoints (Essential)

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

## 🚀 Implementation Plan

### Phase 1: Core Setup
1. **Environment Setup**
   - Install XAMPP and configure MySQL.
   - Clone the repository and set up the database.

2. **Authentication**
   - Implement role-based login and registration.
   - Add email verification.

3. **Service Listings**
   - Create CRUD operations for services.
   - Add search and filter functionality.

### Phase 2: Booking & Wallet
1. **Booking System**
   - Implement booking creation and status updates.
   - Add notifications for booking confirmations.

2. **Wallet System**
   - Integrate payment gateway (Flutterwave or Paystack).
   - Implement escrow logic for holding and releasing funds.

### Phase 3: Admin Panel & Reviews
1. **Admin Panel**
   - Add user verification and dispute resolution features.

2. **Ratings & Reviews**
   - Allow clients to rate and review providers.

### Phase 4: Testing & Deployment
1. **Testing**
   - Test all features for bugs and edge cases.
   - Ensure mobile responsiveness.

2. **Deployment**
   - Deploy on shared hosting or VPS.
   - Configure `.env` for sensitive data.

---

## 📅 Timeline

| Phase            | Duration       |
|------------------|----------------|
| Core Setup       | 1 Week         |
| Booking & Wallet | 2 Weeks        |
| Admin & Reviews  | 1 Week         |
| Testing & Deploy | 1 Week         |

---

## 🛠️ Tools & Resources

- **Code Editor**: Visual Studio Code
- **Version Control**: GitHub
- **Database Management**: phpMyAdmin
- **Payment Gateway**: Flutterwave or Paystack
- **Hosting**: cPanel or VPS

---

## 🎯 Final Deliverables

1. Fully functional ServiceHub platform.
2. Documentation for setup and usage.
3. Presentation slides for project defense.

---

This guide ensures a focused and efficient development process while meeting the requirements of a final-year project.