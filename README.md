# 🏢 MISystem – MIS Office Information Management System

MISystem is a web-based system built with Laravel designed to streamline office operations such as inventory management, room reservations, and employee system access. This system also connected to an existing old system "DTR Attendance System".

It is divided into two main parts:
1. System Access Panel (for employees) 
2. Admin Information Management System (for administrators)

---

## 🚀 Features

### 🔐 System Access Panel (Employee Side)
Provides quick access to:
- 🕒 DTR Employee System
- 🏫 Room Reservations Page

---

### 🛠️ Admin System (Information Management)

#### 👨‍💻 Main Admin (MIS Office / IT) — `admin_id=1`
Full system control with access to:
- 📊 Dashboard
- 📦 Lent Items Management
- 🔨 Inventory Items Management
- 🏫 Room Scheduler
- 📚 Backlogs / Request Logs

#### 👑 Sister/Owner — `admin_id=3`
- 📊 Dashboard
- 📝 Leave Requests
- 🗄️ Inventory Management

#### 🎓 Principal — `admin_id=4`
- 📊 Dashboard
- 📝 Leave Requests (with AP Disapproved tab)

#### 🏫 Assistant Principals (GS/JHS/SHS) — `admin_id=5,6,7`
- 📊 Dashboard
- 📝 Leave Requests (department-filtered)

#### 👥 HR / Reception — `admin_id=2`
- 📊 Dashboard
- 🏫 Room Scheduler

---

## 🖼️ Screenshots

### Login Page
![Admin Login](https://github.com/Noia-kun/MISystem/releases/download/v1.10.2-assets/Login.Page.png)


### Dashboards

#### MIS Staff Dashboard
![MIS Staff Dashboard](https://github.com/Noia-kun/MISystem/releases/download/v1.10.2-assets/MISAdmin.Dashboard.Page.png)

#### Sister/Owner Dashboard
![Sister Dashboard](https://github.com/Noia-kun/MISystem/releases/download/v1.10.2-assets/Admin.Dashboard.Page.png)

#### Principal Dashboard
![Principal Dashboard](https://github.com/Noia-kun/MISystem/releases/download/v1.10.2-assets/Principal.Dashboard.Page.png)

---

### Leave Requests

#### Leave Requests Page (General View)
![Leave Requests](https://github.com/Noia-kun/MISystem/releases/download/v1.10.2-assets/Admins.Leave.Requests.Page.png)

#### Leave Requests Page (Principal View — with AP Disapproved tab)
![Leave Requests Principal](https://github.com/Noia-kun/MISystem/releases/download/v1.10.2-assets/Principal.Leave.Requests.Page.png)

---

### Inventory

#### MIS Office Inventory
![MIS Inventory](https://github.com/Noia-kun/MISystem/releases/download/v1.10.2-assets/MISAdmin.Inventory.Items.Page.png)

#### Sister Inventory
![Sister Inventory](https://github.com/Noia-kun/MISystem/releases/download/v1.10.2-assets/Admin.Inventory.Page.png)

---

### Room Management (MIS Office)

#### Room Scheduler
![Room Scheduler](https://github.com/Noia-kun/MISystem/releases/download/v1.10.2-assets/MISAdmin.Room.Scheduling.Page.png)

---

### Employees Pages
![Employee System Access Panel](https://github.com/Noia-kun/MISystem/releases/download/v1.10.2-assets/Employee.System.Access.Panel.Page.png)

![Employee Room Reservation](https://github.com/Noia-kun/MISystem/releases/download/v1.10.2-assets/Employee.Room.Reservation.Page.png)

---

### Other Pages (MIS Office)

#### Lent Items
![Lent Items](https://github.com/Noia-kun/MISystem/releases/download/v1.10.2-assets/MISAdmin.LentItems.Page.png)

#### Back Logs
![Back Logs](https://github.com/Noia-kun/MISystem/releases/download/v1.10.2-assets/MISAdmin.Logs.Page.png)

---

## 🧩 Core Functionalities

- ✅ Inventory tracking with condition monitoring
- ✅ Item lending and return system
- ✅ Room scheduling with conflict detection
- ✅ Multi-level leave request approval workflow (AP → Principal)
- ✅ Request approval system (Borrowing & Room Scheduling)
- ✅ Location history tracking
- ✅ Usable notes tracking for item maintenance
- ✅ CSV export for inventory data
- ✅ Role-based access (7 admin accounts)

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
```
git clone https://github.com/your-username/misystem.git
```

2. Navigate to project:
```
cd misystem
```

3. Install dependencies:
```
composer install
```

4. Setup environment:
```
cp .env.example .env
php artisan key:generate
```

5. Configure database in `.env`

6. Run migrations:
```
php artisan migrate
```

7. Start server:
```
php artisan serve
```

---

## 🧪 Usage

- Access the System Access Panel for employee navigation
- Login as Admin to manage inventory and requests
- Use Room Scheduler for booking and approvals

---

## 📌 Versioning

This project follows semantic versioning:

```
vMAJOR.MINOR.PATCH
```

| Version | Description |
|---|---|
| v1.0.0 | Initial release |
| v1.10.0 | Sister Inventory System |
| v1.10.1 | Serial Number + Logo Fix |

---

## 👨‍💻 Developer

Developed by: RR

## 📄 License

This project is for internal organizational use of CDBS. All rights reserved.
