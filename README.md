# 🐟 Fish Market Management System — Zanzibar

A web-based system designed to digitize and automate fish market operations in Zanzibar, Tanzania. This project replaces manual paper-based record keeping with a reliable digital solution for managing daily sales, fish inventory, fishermen records, and revenue reports.

## 📋 About This Project

This is a **Diploma Final Project** built to help fish market administrators and staff efficiently manage:

- Daily fish sales transactions
- Fish inventory and stock levels
- Fishermen (supplier) records
- Revenue reports (daily, weekly, monthly)
- User accounts with role-based access control

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Frontend | HTML5, CSS3, JavaScript |
| Backend | PHP |
| Database | MySQL |
| Server | Apache (LAMP Stack) |
| OS | Zorin OS (Linux/Ubuntu based) |

## ✨ Features

- 🔐 Secure login with password hashing (bcrypt)
- 👥 Role-Based Access Control (Admin & Staff)
- 🐠 Fish & stock management with low-stock alerts
- 💰 Sales recording with automatic price calculation
- 🎣 Fishermen records management
- 📊 Daily/weekly/monthly revenue reports
- 👤 User management (Admin only)

## 📂 Project Structure

```
fish_market/
├── includes/        # Database connection, header, footer
├── fish/            # Fish management (CRUD)
├── sales/           # Sales management
├── fishermen/       # Fishermen records
├── reports/         # Revenue reports
├── users/           # User management
├── assets/css/      # Stylesheet
├── login.php
├── logout.php
└── dashboard.php
```

## 🚀 Installation (Local Setup)

### Requirements
- Apache web server
- PHP 8.0+
- MySQL 8.0+

### Steps

1. Clone this repository into your web server directory:
```bash
git clone https://github.com/Yaasr-tech/fish-market-zanzibar.git
```

2. Create the database using the provided SQL structure file.

3. Create a MySQL user and grant privileges:
```sql
CREATE USER 'fishmarket'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON fish_market_db.* TO 'fishmarket'@'localhost';
FLUSH PRIVILEGES;
```

4. Create `includes/db.php` with your database credentials:
```php
<?php
$db_host     = "localhost";
$db_user     = "fishmarket";
$db_password = "your_password";
$db_name     = "fish_market_db";

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
```

5. Open in your browser:
```
http://localhost/fish_market/login.php
```

## 🔑 Default Login

| Role | Username | Password |
|---|---|---|
| Admin | admin | admin123 |

## 📊 Database Tables

- `users` — Admin and staff accounts
- `fish` — Fish types, prices, and stock
- `fishermen` — Fish suppliers
- `sales` — Sales transactions
- `reports` — Generated reports log

## 👤 Author

Diploma Final Project — Zanzibar, Tanzania 🇹🇿

## 📄 License

This project was created for educational purposes as part of a diploma program.
email me if you like it
"yasriissa71@gmail.com"
