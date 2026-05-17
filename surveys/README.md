# SurveyStack — SaaS Survey Platform (CodeIgniter 4)

A full-featured, multi-tenant SaaS survey management system built with **CodeIgniter 4**.

## Features

### Core Survey Features
- **14+ Question Types**: Short text, Long text, Single choice, Multiple choice, Dropdown, Rating (1-5 or 1-10), NPS (0-10), Yes/No, Email, Number, Date, Matrix, Ranking, File upload
- **Survey Builder**: Drag-and-drop question reordering, live AJAX save
- **Survey Settings**: Progress bar, question numbers, anonymous responses, response limits, date ranges
- **Public Survey Link**: Shareable URL with QR code and embed code
- **Preview Mode**: See survey before publishing

### Multi-Tenancy (SaaS)
- **Organization Accounts**: Each registration creates an isolated tenant
- **Role-Based Access**: Owner, Admin, Member, Viewer roles
- **Team Management**: Invite team members with role assignments
- **Plan Limits**: Per-tenant survey, response, and team member limits

### Subscription Plans
| Plan | Surveys | Responses/Survey | Team Members | Price |
|------|---------|-----------------|--------------|-------|
| Free | 3 | 100 | 1 | $0 |
| Pro | 50 | 1,000 | 5 | $29/mo |
| Enterprise | Unlimited | Unlimited | Unlimited | $99/mo |

### Analytics & Reports
- **Real-time Dashboard**: Response trends, key metrics
- **Per-survey Reports**: Chart visualization for each question type
- **Response Browser**: View individual responses
- **CSV Export**: Download all response data
- **NPS Calculation**: Automatic promoter/detractor breakdown

### Authentication
- Email/password login
- Secure registration with org creation
- Password reset via email token
- Session management

## Tech Stack

- **Framework**: CodeIgniter 4.5+
- **PHP**: 8.1+
- **Database**: MySQL 8.0+ / MariaDB 10.4+
- **Frontend**: Bootstrap 5.3, Chart.js 4, Bootstrap Icons
- **Build**: No build step required — pure CI4

## Project Structure

```
surveys/
├── app/
│   ├── Config/         # Routes, Database, Filters, App config
│   ├── Controllers/
│   │   ├── Auth/       # Login, Register, ForgotPassword
│   │   ├── Public/     # Public survey taking
│   │   ├── DashboardController.php
│   │   ├── SurveysController.php
│   │   ├── QuestionsController.php
│   │   ├── ResponsesController.php
│   │   ├── ReportsController.php
│   │   ├── TeamController.php
│   │   ├── BillingController.php
│   │   └── SettingsController.php
│   ├── Database/
│   │   └── Migrations/ # 8 migration files
│   ├── Filters/        # Auth, Guest filters
│   ├── Models/         # 6 models (Tenant, User, Survey, Question, Option, Response, Plan)
│   └── Views/
│       ├── layouts/    # app.php, auth.php
│       ├── auth/       # login, register, forgot-password
│       ├── dashboard/
│       ├── surveys/    # index, create, edit, share
│       ├── responses/  # index, show
│       ├── reports/
│       ├── billing/
│       ├── team/
│       ├── settings/
│       └── public/     # survey.php, thank-you.php
├── public/
│   ├── index.php
│   ├── .htaccess
│   └── assets/
│       ├── css/app.css
│       ├── css/survey-public.css
│       └── js/app.js
├── database.sql        # Ready-to-run SQL schema
├── composer.json
└── .env                # Environment config template
```

## Installation

### Requirements
- PHP 8.1+
- MySQL 8.0+ or MariaDB 10.4+
- Composer
- Apache/Nginx with mod_rewrite

### Steps

1. **Clone & install dependencies**
   ```bash
   cd surveys/
   composer install
   ```

2. **Configure environment**
   ```bash
   cp .env.example .env
   # Edit .env with your database credentials
   ```

3. **Create database and run migrations**
   ```bash
   mysql -u root -p < database.sql
   # OR use migrations:
   php spark migrate
   ```

4. **Set permissions**
   ```bash
   chmod -R 755 writable/
   ```

5. **Point web server to `surveys/public/`**

6. **Access the application**
   - Homepage: `http://yoursite.com/`
   - Register: `http://yoursite.com/auth/register`

### Development Server
```bash
php spark serve
# App runs at http://localhost:8080
```

## API Routes

```
GET  /                          Homepage
GET  /auth/login                Login page
POST /auth/login                Process login
GET  /auth/register             Registration page
POST /auth/register             Process registration
GET  /auth/logout               Logout

GET  /dashboard                 Dashboard (auth)
GET  /surveys                   Survey list
GET  /surveys/create            Create form
POST /surveys/create            Store survey
GET  /surveys/:id/edit          Survey builder
POST /surveys/:id/edit          Update settings
POST /surveys/:id/publish       Publish survey
POST /surveys/:id/close         Close survey
GET  /surveys/:id/share         Share page
GET  /surveys/:id/reports       Analytics report
GET  /surveys/:id/responses     Response list
GET  /surveys/:id/responses/:id Response detail

GET  /s/:slug                   Public survey
POST /s/:slug                   Submit response
GET  /s/:slug/thank-you         Thank you page

GET  /billing                   Billing & plans
GET  /team                      Team management
GET  /settings                  Account settings
```

## Security

- CSRF protection on all forms
- Password hashing with bcrypt (cost 12)
- Tenant isolation (all queries scoped to tenant)
- SQL injection prevention (CI4 query builder)
- XSS protection (esc() everywhere)
- Rate limiting ready (via CI4 filters)

## License

MIT License — Free to use and modify.
