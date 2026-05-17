-- ============================================================
-- HRM System Database Schema
-- CodeIgniter 4 | MySQL | Bootstrap 5
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `hrm_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `hrm_db`;

-- ============================================================
-- CORE TABLES
-- ============================================================

CREATE TABLE `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `head_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `designations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `department_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `level` varchar(50) DEFAULT NULL,
  `min_salary` decimal(12,2) DEFAULT 0.00,
  `max_salary` decimal(12,2) DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `department_id` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(20) NOT NULL UNIQUE,
  `first_name` varchar(60) NOT NULL,
  `last_name` varchar(60) NOT NULL,
  `email` varchar(150) NOT NULL UNIQUE,
  `phone` varchar(20) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `national_id` varchar(30) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `reporting_to` int(11) DEFAULT NULL,
  `employment_type` enum('full_time','part_time','contract','intern') DEFAULT 'full_time',
  `join_date` date DEFAULT NULL,
  `termination_date` date DEFAULT NULL,
  `basic_salary` decimal(12,2) DEFAULT 0.00,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_account` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(60) DEFAULT NULL,
  `state` varchar(60) DEFAULT NULL,
  `country` varchar(60) DEFAULT 'Bangladesh',
  `emergency_contact_name` varchar(100) DEFAULT NULL,
  `emergency_contact_phone` varchar(20) DEFAULT NULL,
  `status` enum('active','inactive','terminated','on_leave') DEFAULT 'active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `department_id` (`department_id`),
  KEY `designation_id` (`designation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `username` varchar(60) NOT NULL UNIQUE,
  `email` varchar(150) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','admin','hr_manager','manager','employee') DEFAULT 'employee',
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- ATTENDANCE
-- ============================================================

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `total_hours` decimal(5,2) DEFAULT 0.00,
  `overtime_hours` decimal(5,2) DEFAULT 0.00,
  `status` enum('present','absent','late','half_day','holiday','weekend') DEFAULT 'present',
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `emp_date` (`employee_id`,`date`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `holidays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `type` enum('national','religious','company') DEFAULT 'national',
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- LEAVE MANAGEMENT
-- ============================================================

CREATE TABLE `leave_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `days_allowed` int(11) NOT NULL DEFAULT 0,
  `carry_forward` tinyint(1) DEFAULT 0,
  `is_paid` tinyint(1) DEFAULT 1,
  `requires_approval` tinyint(1) DEFAULT 1,
  `color` varchar(20) DEFAULT '#007bff',
  `status` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `leave_allocations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `year` year NOT NULL,
  `allocated_days` decimal(5,1) DEFAULT 0.0,
  `used_days` decimal(5,1) DEFAULT 0.0,
  `remaining_days` decimal(5,1) DEFAULT 0.0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `emp_type_year` (`employee_id`,`leave_type_id`,`year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `total_days` decimal(5,1) NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','cancelled') DEFAULT 'pending',
  `approved_by` int(11) DEFAULT NULL,
  `approval_date` datetime DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `leave_type_id` (`leave_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- PAYROLL
-- ============================================================

CREATE TABLE `salary_components` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` enum('earning','deduction') NOT NULL,
  `calculation_type` enum('fixed','percentage') DEFAULT 'fixed',
  `value` decimal(10,2) DEFAULT 0.00,
  `is_taxable` tinyint(1) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `payroll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `month` tinyint(2) NOT NULL,
  `year` year NOT NULL,
  `basic_salary` decimal(12,2) DEFAULT 0.00,
  `house_rent` decimal(12,2) DEFAULT 0.00,
  `medical_allowance` decimal(12,2) DEFAULT 0.00,
  `transport_allowance` decimal(12,2) DEFAULT 0.00,
  `other_earnings` decimal(12,2) DEFAULT 0.00,
  `overtime_pay` decimal(12,2) DEFAULT 0.00,
  `gross_salary` decimal(12,2) DEFAULT 0.00,
  `tax_deduction` decimal(12,2) DEFAULT 0.00,
  `provident_fund` decimal(12,2) DEFAULT 0.00,
  `other_deductions` decimal(12,2) DEFAULT 0.00,
  `total_deductions` decimal(12,2) DEFAULT 0.00,
  `net_salary` decimal(12,2) DEFAULT 0.00,
  `attendance_days` int(11) DEFAULT 0,
  `absent_days` int(11) DEFAULT 0,
  `leave_days` int(11) DEFAULT 0,
  `overtime_hours` decimal(5,2) DEFAULT 0.00,
  `status` enum('draft','processed','paid') DEFAULT 'draft',
  `payment_date` date DEFAULT NULL,
  `payment_method` enum('bank_transfer','cash','check') DEFAULT 'bank_transfer',
  `notes` text DEFAULT NULL,
  `generated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `emp_month_year` (`employee_id`,`month`,`year`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- RECRUITMENT
-- ============================================================

CREATE TABLE `job_postings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `employment_type` enum('full_time','part_time','contract','intern') DEFAULT 'full_time',
  `vacancies` int(11) DEFAULT 1,
  `experience_required` varchar(50) DEFAULT NULL,
  `education_required` varchar(100) DEFAULT NULL,
  `salary_from` decimal(12,2) DEFAULT NULL,
  `salary_to` decimal(12,2) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `benefits` text DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `status` enum('open','closed','on_hold') DEFAULT 'open',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `job_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `applicant_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `resume` varchar(255) DEFAULT NULL,
  `cover_letter` text DEFAULT NULL,
  `experience_years` decimal(4,1) DEFAULT 0.0,
  `expected_salary` decimal(12,2) DEFAULT NULL,
  `stage` enum('applied','screening','interview','offer','hired','rejected') DEFAULT 'applied',
  `interview_date` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `job_id` (`job_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- PERFORMANCE
-- ============================================================

CREATE TABLE `performance_reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `reviewer_id` int(11) NOT NULL,
  `review_period` varchar(20) NOT NULL,
  `review_date` date NOT NULL,
  `punctuality` tinyint(1) DEFAULT 0,
  `quality_of_work` tinyint(1) DEFAULT 0,
  `teamwork` tinyint(1) DEFAULT 0,
  `communication` tinyint(1) DEFAULT 0,
  `initiative` tinyint(1) DEFAULT 0,
  `overall_score` decimal(5,2) DEFAULT 0.00,
  `rating` enum('excellent','good','satisfactory','needs_improvement','unsatisfactory') DEFAULT 'good',
  `strengths` text DEFAULT NULL,
  `areas_for_improvement` text DEFAULT NULL,
  `goals_next_period` text DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `status` enum('draft','submitted','acknowledged') DEFAULT 'draft',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- DOCUMENTS
-- ============================================================

CREATE TABLE `employee_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `document_type` varchar(60) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- NOTIFICATIONS & ACTIVITY
-- ============================================================

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','success','warning','danger') DEFAULT 'info',
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- SAMPLE DATA
-- ============================================================

-- Departments
INSERT INTO `departments` (`name`, `code`, `description`) VALUES
('Human Resources', 'HR', 'Manages employee relations and company culture'),
('Information Technology', 'IT', 'Handles all technology infrastructure'),
('Finance & Accounts', 'FIN', 'Manages financial operations'),
('Sales & Marketing', 'SM', 'Drives business growth and marketing'),
('Operations', 'OPS', 'Oversees day-to-day operations'),
('Customer Support', 'CS', 'Handles customer inquiries and support');

-- Designations
INSERT INTO `designations` (`department_id`, `title`, `level`, `min_salary`, `max_salary`) VALUES
(1, 'HR Director', 'Executive', 150000, 250000),
(1, 'HR Manager', 'Senior', 80000, 150000),
(1, 'HR Officer', 'Mid', 40000, 80000),
(2, 'CTO', 'Executive', 200000, 350000),
(2, 'Senior Developer', 'Senior', 100000, 180000),
(2, 'Junior Developer', 'Junior', 40000, 80000),
(3, 'CFO', 'Executive', 180000, 300000),
(3, 'Finance Manager', 'Senior', 90000, 150000),
(4, 'Sales Director', 'Executive', 150000, 280000),
(4, 'Sales Executive', 'Mid', 45000, 90000),
(5, 'Operations Manager', 'Senior', 85000, 160000),
(6, 'Support Lead', 'Mid', 50000, 90000);

-- Leave Types
INSERT INTO `leave_types` (`name`, `days_allowed`, `carry_forward`, `is_paid`, `color`) VALUES
('Annual Leave', 20, 1, 1, '#28a745'),
('Sick Leave', 14, 0, 1, '#dc3545'),
('Casual Leave', 7, 0, 1, '#007bff'),
('Maternity Leave', 90, 0, 1, '#e83e8c'),
('Paternity Leave', 7, 0, 1, '#6f42c1'),
('Unpaid Leave', 30, 0, 0, '#6c757d');

-- Salary Components
INSERT INTO `salary_components` (`name`, `type`, `calculation_type`, `value`, `is_taxable`) VALUES
('House Rent Allowance', 'earning', 'percentage', 40.00, 0),
('Medical Allowance', 'earning', 'fixed', 5000.00, 0),
('Transport Allowance', 'earning', 'fixed', 3000.00, 0),
('Income Tax', 'deduction', 'percentage', 10.00, 0),
('Provident Fund', 'deduction', 'percentage', 10.00, 0);

-- Holidays
INSERT INTO `holidays` (`name`, `date`, `type`) VALUES
('New Year Day', '2025-01-01', 'national'),
('Independence Day', '2025-03-26', 'national'),
('Bengali New Year', '2025-04-14', 'national'),
('Eid ul Fitr', '2025-03-31', 'religious'),
('Eid ul Adha', '2025-06-07', 'religious'),
('Victory Day', '2025-12-16', 'national');

-- Admin User
INSERT INTO `employees` (`employee_id`, `first_name`, `last_name`, `email`, `phone`, `gender`, `department_id`, `designation_id`, `join_date`, `basic_salary`, `status`) VALUES
('EMP-001', 'Admin', 'User', 'admin@hrmsystem.com', '01700000001', 'male', 1, 1, '2020-01-01', 150000.00, 'active'),
('EMP-002', 'John', 'Smith', 'john.smith@hrmsystem.com', '01700000002', 'male', 2, 5, '2021-03-15', 100000.00, 'active'),
('EMP-003', 'Sarah', 'Johnson', 'sarah.j@hrmsystem.com', '01700000003', 'female', 1, 3, '2022-01-10', 55000.00, 'active'),
('EMP-004', 'Mike', 'Chen', 'mike.chen@hrmsystem.com', '01700000004', 'male', 2, 6, '2023-06-01', 45000.00, 'active'),
('EMP-005', 'Emily', 'Davis', 'emily.d@hrmsystem.com', '01700000005', 'female', 4, 10, '2022-09-01', 60000.00, 'active'),
('EMP-006', 'Robert', 'Wilson', 'r.wilson@hrmsystem.com', '01700000006', 'male', 3, 8, '2021-07-20', 95000.00, 'active'),
('EMP-007', 'Lisa', 'Anderson', 'lisa.a@hrmsystem.com', '01700000007', 'female', 5, 11, '2020-11-05', 85000.00, 'active'),
('EMP-008', 'David', 'Taylor', 'david.t@hrmsystem.com', '01700000008', 'male', 6, 12, '2023-02-14', 55000.00, 'active');

-- Users (password: Admin@1234)
INSERT INTO `users` (`employee_id`, `username`, `email`, `password`, `role`) VALUES
(1, 'admin', 'admin@hrmsystem.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin'),
(2, 'john.smith', 'john.smith@hrmsystem.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'manager'),
(3, 'sarah.j', 'sarah.j@hrmsystem.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hr_manager'),
(4, 'mike.chen', 'mike.chen@hrmsystem.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee'),
(5, 'emily.d', 'emily.d@hrmsystem.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee');

-- Leave Allocations
INSERT INTO `leave_allocations` (`employee_id`, `leave_type_id`, `year`, `allocated_days`, `used_days`, `remaining_days`) VALUES
(2, 1, 2025, 20, 5, 15),
(2, 2, 2025, 14, 2, 12),
(3, 1, 2025, 20, 8, 12),
(3, 2, 2025, 14, 1, 13),
(4, 1, 2025, 20, 3, 17),
(5, 1, 2025, 20, 0, 20);

-- Sample Attendance
INSERT INTO `attendance` (`employee_id`, `date`, `check_in`, `check_out`, `total_hours`, `status`) VALUES
(2, CURDATE(), '09:00:00', '17:00:00', 8.00, 'present'),
(3, CURDATE(), '09:15:00', '17:00:00', 7.75, 'late'),
(4, CURDATE(), '09:00:00', '17:00:00', 8.00, 'present'),
(5, CURDATE(), '09:00:00', '17:00:00', 8.00, 'present');

-- Sample Job Posting
INSERT INTO `job_postings` (`title`, `department_id`, `designation_id`, `employment_type`, `vacancies`, `experience_required`, `salary_from`, `salary_to`, `description`, `deadline`, `status`, `created_by`) VALUES
('Senior Software Developer', 2, 5, 'full_time', 2, '3-5 years', 80000, 150000, 'We are looking for experienced developers to join our team.', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 'open', 1),
('HR Officer', 1, 3, 'full_time', 1, '1-2 years', 35000, 55000, 'Looking for an enthusiastic HR professional.', DATE_ADD(CURDATE(), INTERVAL 20 DAY), 'open', 1);

COMMIT;
