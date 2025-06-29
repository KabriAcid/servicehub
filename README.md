# ServiceHub Project Guide (MVP)

A web-based platform that connects users to local service providers such as plumbers, electricians, cleaners, lawyers, and other skilled professionals. This MVP version is inspired by industry leaders like [UrbanCompany](https://www.urbancompany.com) and [TaskRabbit](https://www.taskrabbit.com), with a focus on simplicity and mobile-first design.

---

## 💡 Project Goals

- Connect service seekers with qualified local professionals.
- Allow easy booking and job tracking.
- Empower service providers to manage incoming job requests.
- Ensure admin has basic control over content and user activities.

---

## 🏗️ System Overview

### Users

| Role       | Access                  | Description                                                  |
|------------|-------------------------|--------------------------------------------------------------|
| Guest      | Browse services         | Can explore service categories, but cannot book without login |
| Customer   | Book services           | Register, browse providers, place bookings, track job status |
| Provider   | Manage service requests | Accept/decline bookings, manage profile, track job history   |
| Admin      | Manage the platform     | Approve providers, manage users, monitor bookings            |

---

## 🔧 Core Features (By Role)

### 1. 👤 **Customer Features**

- Register/Login
- Browse service categories
- Search for providers
- View provider profiles
- Book a service (with address, time, and notes)
- View active/past bookings
- Rate and review providers

### 2. 🧰 **Provider Features**

- Register/Login
- Select services offered
- Add bio, ID verification, certifications
- Accept or reject service bookings
- Update job status (Accepted → On The Way → Completed)
- View ratings and job history

### 3. 🛠️ **Admin Features (Basic for MVP)**

- Admin login dashboard
- View all users (customers + providers)
- Approve/decline providers
- View all bookings and statuses
- Manage service categories


---

## 🗂️ Database Tables (Simplified)

### `users`
| id | name | email | password | role (`customer` / `provider` / `admin`) | ... |

### `services`
| id | name | category | description |

### `providers`
| id | user_id | bio | verification_status | services_offered |

### `bookings`
| id | customer_id | provider_id | service_id | status | time | address | notes |

### `reviews`
| id | booking_id | customer_id | provider_id | rating | comment |

---

## 🧭 Feature Flow Examples

### Booking a Service (Customer)

1. Visit Homepage
2. Choose a service category
3. Browse provider profiles
4. Click “Book Now”
5. Fill out form (time, address, notes)
6. Submit → Get confirmation
7. Track status via dashboard
8. After completion, rate provider

### Job Handling (Provider)

1. Login to dashboard
2. View new job requests
3. Accept or decline
4. Change status as job progresses
5. Mark job as complete
6. View feedback

---

🔄 Future Improvements (Optional)
Payment integration (Flutterwave, Stripe)

Real-time messaging/chat

Notifications (email/SMS)

Google Maps for address/pin

Smart contract agreements (blockchain-ready)