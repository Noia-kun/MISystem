# 🏢 MISystem – MIS Office Inventory Management System

MISystem is a web-based system built with Laravel designed to streamline office operations such as inventory management, room reservations, and employee system access.

It is divided into two main parts:
1. System Access Panel (for employees)
2. Admin Inventory Management System (for administrators)

---

## 🚀 Features

### 🔐 System Access Panel (Employee Side)
Provides quick access to:
- 🕒 DTR Employee System
- 🏫 Room Reservations Page

---

### 🛠️ Admin System (Inventory Management)

#### 👨‍💻 Main Admin (MIS Office / IT)
Full system control with access to:
- 📊 Dashboard
- 📦 Lent Items Management
- 🔨 Inventory Items Management
- 🏫 Room Scheduler
- 📚 Backlogs / Request Logs

---

#### 👥 Secondary Admin (HR)
Limited access to:
- 📊 Dashboard
- 🏫 Room Scheduler

---

## 🧩 Core Functionalities

- ✅ Inventory tracking with condition monitoring
- ✅ Item lending and return system
- ✅ Room scheduling with conflict detection
- ✅ Request approval system (Borrowing & Room Scheduling)
- ✅ Location history tracking
- ✅ Usable notes tracking for item maintenance
- ✅ CSV export for inventory data
- ✅ Role-based access (Admin / HR / User)

---

## 🛠️ Built With

- **Laravel (PHP Framework)**
- **MySQL / MariaDB**
- **Bootstrap 5**
- **JavaScript (Vanilla)**
- **jQuery (AJAX features)**

---

## ⚙️ Installation



1. Clone the repository:

git clone https://github.com/your-username/misystem.git

2. Navigate to project:

cd misystem

3. Install dependencies:

composer install

4. Setup environment:

cp .env.example .env
php artisan key:generate

5. Configure database in .env

6. Run migrations:

php artisan migrate

7. Start server:

php artisan serve

---

🧪 Usage

- Access the System Access Panel for employee navigation
- Login as Admin to manage inventory and requests
- Use Room Scheduler for booking and approvals

---

📌 Versioning

This project follows semantic versioning:

vMAJOR.MINOR.PATCH

Example:

v1.0.0 Initial release
v1.1.0 New features
v1.1.1 Bug fixes

---

👨‍💻 Developer

Developed by: RR

📄 License

This project is for internal organizational use of CDBS. All rights reserved.