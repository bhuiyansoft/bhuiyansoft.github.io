# BhuiyanSoft HRM System

A **medium-scale enterprise Human Resource Management System** built with:
- **CodeIgniter 4** (PHP Framework)
- **MySQL** (Database)
- **Bootstrap 5** (UI Framework)

---

## Features

### Core Modules
| Module | Features |
|--------|----------|
| **Authentication** | Login, Logout, Forgot Password, Reset Password, Remember Me |
| **Dashboard** | Stats, Charts, Recent Activities, Quick Actions |
| **Employee Management** | CRUD, Profile, Photo Upload, CSV Export, Table/Card View |
| **Department Management** | CRUD with Employee Count and Head Assignment |
| **Designation Management** | CRUD with Salary Range and Dynamic Loading |
| **Attendance** | Daily Marking, Bulk Mark, Monthly Reports, Per-Employee View |
| **Leave Management** | Apply, Approve/Reject, Leave Types, Allocations, Balance |
| **Payroll** | Generate, Process, Payslip PDF Print, Mark Paid, Annual Report |
| **Recruitment** | Job Postings, Applications, Stage Tracking |
| **Performance** | Reviews, Scoring (5 criteria), Rating System |
| **Reports** | Employee, Attendance, Payroll, Leave, Headcount |
| **Settings** | System Config, User Management, Role-Based Access |
| **Profile** | Self-service profile update, Password change |

---

## Installation

### Requirements
- PHP 8.1+
- MySQL 8.0+
- Apache/Nginx with mod_rewrite
- Composer

### Steps

1. **Clone/Download** to your web server
```bash
cd /var/www/html
git clone <repo_url> hrm
cd hrm
```

2. **Install Dependencies**
```bash
composer install
```

3. **Configure Environment**
```bash
cp .env.example .env
# Edit .env with your database credentials
```

4. **Import Database**
```bash
mysql -u root -p < database/hrm_system.sql
```

5. **Set Permissions**
```bash
chmod -R 775 writable/
```

6. **Configure Web Server**

For Apache, point DocumentRoot to `/hrm/public/`

For Nginx:
```nginx
root /path/to/hrm/public;
location / {
    try_files $uri $uri/ /index.php$is_args$args;
}
```

7. **Access the System**
- URL: `http://localhost/hrm/public/`
- Username: `admin`
- Password: `password`

---

## Default Users

| Role | Username | Password |
|------|----------|----------|
| Super Admin | admin | password |
| Manager | john.smith | password |
| HR Manager | sarah.j | password |
| Employee | mike.chen | password |

---

## Architecture

```
hrm/
├── app/
│   ├── Config/          # Routes, Database, Filters, App config
│   ├── Controllers/
│   │   ├── Auth/        # Login, Logout, Password reset
│   │   └── Admin/       # All protected routes
│   ├── Models/          # Database models with query methods
│   ├── Views/
│   │   ├── layouts/     # Main layout with sidebar
│   │   ├── auth/        # Login, forgot password
│   │   ├── dashboard/   # Dashboard with charts
│   │   ├── employees/   # Employee CRUD + profile
│   │   ├── departments/ # Department CRUD
│   │   ├── designations/# Designation CRUD
│   │   ├── attendance/  # Attendance marking + reports
│   │   ├── leaves/      # Leave management
│   │   ├── payroll/     # Payroll + payslip
│   │   ├── recruitment/ # Jobs + applications
│   │   ├── performance/ # Performance reviews
│   │   ├── reports/     # Various reports
│   │   ├── settings/    # System settings
│   │   └── profile/     # User profile
│   ├── Filters/         # AuthFilter for route protection
│   └── Database/        # Migrations
├── public/              # Web root (point server here)
├── writable/            # Logs, cache, sessions, uploads
├── database/
│   └── hrm_system.sql   # Complete database schema + seed data
├── composer.json
└── .env.example
```

---

## Tech Stack Details

- **Backend**: PHP 8.1+, CodeIgniter 4.5
- **Frontend**: Bootstrap 5.3, Bootstrap Icons
- **Database**: MySQL 8.0 with proper indexing
- **Charts**: Chart.js
- **Tables**: DataTables with Bootstrap 5 theme
- **Forms**: Select2, Flatpickr
- **UI Pattern**: Collapsible sidebar, Responsive design

---

## Security Features
- CSRF protection on all forms
- Password hashing (Bcrypt)
- Session-based authentication
- Role-based access control (RBAC)
- SQL injection prevention via prepared statements
- XSS protection via `esc()` on all output
- File upload validation

---

## License
MIT License — © 2025 BhuiyanSoft
