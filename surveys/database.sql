-- ============================================================
-- SurveyStack — Database Setup SQL
-- Run this to create the database and run migrations
-- ============================================================

CREATE DATABASE IF NOT EXISTS `saas_surveys`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `saas_surveys`;

-- Run migrations with: php spark migrate
-- Run seeders with:    php spark db:seed MainSeeder

-- ============================================================
-- Quick Setup (manual alternative to migrations)
-- ============================================================

CREATE TABLE IF NOT EXISTS `plans` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL,
    `slug` varchar(50) NOT NULL,
    `price_monthly` decimal(10,2) NOT NULL DEFAULT '0.00',
    `price_yearly` decimal(10,2) NOT NULL DEFAULT '0.00',
    `max_surveys` int NOT NULL DEFAULT '3',
    `max_responses_per_survey` int NOT NULL DEFAULT '100',
    `max_team_members` int NOT NULL DEFAULT '1',
    `max_questions_per_survey` int NOT NULL DEFAULT '10',
    `features` json DEFAULT NULL,
    `is_active` tinyint(1) NOT NULL DEFAULT '1',
    `sort_order` int NOT NULL DEFAULT '0',
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `tenants` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `slug` varchar(100) NOT NULL,
    `plan_id` int unsigned NOT NULL DEFAULT '1',
    `plan_expires_at` datetime DEFAULT NULL,
    `status` enum('active','suspended','cancelled') NOT NULL DEFAULT 'active',
    `logo` varchar(255) DEFAULT NULL,
    `primary_color` varchar(7) NOT NULL DEFAULT '#4F46E5',
    `website` varchar(255) DEFAULT NULL,
    `industry` varchar(100) DEFAULT NULL,
    `size` varchar(50) DEFAULT NULL,
    `settings` json DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `users` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `tenant_id` int unsigned NOT NULL,
    `name` varchar(100) NOT NULL,
    `email` varchar(150) NOT NULL,
    `password` varchar(255) NOT NULL,
    `role` enum('owner','admin','member','viewer') NOT NULL DEFAULT 'owner',
    `avatar` varchar(255) DEFAULT NULL,
    `status` enum('active','inactive','pending') NOT NULL DEFAULT 'active',
    `email_verified_at` datetime DEFAULT NULL,
    `reset_token` varchar(100) DEFAULT NULL,
    `reset_token_expires` datetime DEFAULT NULL,
    `last_login_at` datetime DEFAULT NULL,
    `last_login_ip` varchar(45) DEFAULT NULL,
    `preferences` json DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `tenant_email` (`tenant_id`, `email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `surveys` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `tenant_id` int unsigned NOT NULL,
    `user_id` int unsigned NOT NULL,
    `title` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `slug` varchar(100) NOT NULL,
    `status` enum('draft','published','closed','archived') NOT NULL DEFAULT 'draft',
    `start_date` datetime DEFAULT NULL,
    `end_date` datetime DEFAULT NULL,
    `is_anonymous` tinyint(1) NOT NULL DEFAULT '1',
    `allow_multiple_responses` tinyint(1) NOT NULL DEFAULT '0',
    `show_progress_bar` tinyint(1) NOT NULL DEFAULT '1',
    `show_question_numbers` tinyint(1) NOT NULL DEFAULT '1',
    `redirect_url` varchar(500) DEFAULT NULL,
    `thank_you_message` text DEFAULT NULL,
    `cover_image` varchar(255) DEFAULT NULL,
    `primary_color` varchar(7) NOT NULL DEFAULT '#4F46E5',
    `password` varchar(255) DEFAULT NULL,
    `response_limit` int DEFAULT NULL,
    `total_responses` int unsigned NOT NULL DEFAULT '0',
    `settings` json DEFAULT NULL,
    `published_at` datetime DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `slug` (`slug`),
    KEY `tenant_id` (`tenant_id`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `questions` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `survey_id` int unsigned NOT NULL,
    `type` enum('short_text','long_text','single_choice','multiple_choice','dropdown','rating','nps','date','email','number','file_upload','matrix','ranking','yes_no') NOT NULL,
    `title` text NOT NULL,
    `description` text DEFAULT NULL,
    `placeholder` varchar(255) DEFAULT NULL,
    `is_required` tinyint(1) NOT NULL DEFAULT '0',
    `sort_order` int NOT NULL DEFAULT '0',
    `settings` json DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `survey_id` (`survey_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `options` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `question_id` int unsigned NOT NULL,
    `label` varchar(500) NOT NULL,
    `value` varchar(500) DEFAULT NULL,
    `sort_order` int NOT NULL DEFAULT '0',
    `is_other` tinyint(1) NOT NULL DEFAULT '0',
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `question_id` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `survey_responses` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `survey_id` int unsigned NOT NULL,
    `respondent_ip` varchar(45) DEFAULT NULL,
    `respondent_email` varchar(150) DEFAULT NULL,
    `respondent_name` varchar(100) DEFAULT NULL,
    `session_id` varchar(100) DEFAULT NULL,
    `status` enum('partial','complete') NOT NULL DEFAULT 'partial',
    `time_taken` int DEFAULT NULL COMMENT 'seconds',
    `user_agent` text DEFAULT NULL,
    `referrer` varchar(500) DEFAULT NULL,
    `metadata` json DEFAULT NULL,
    `completed_at` datetime DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `survey_id` (`survey_id`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `survey_answers` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `response_id` int unsigned NOT NULL,
    `question_id` int unsigned NOT NULL,
    `answer_value` text DEFAULT NULL,
    `answer_values` json DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `response_id` (`response_id`),
    KEY `question_id` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed default plans
INSERT INTO `plans` (`name`, `slug`, `price_monthly`, `price_yearly`, `max_surveys`, `max_responses_per_survey`, `max_team_members`, `max_questions_per_survey`, `features`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
('Free', 'free', 0.00, 0.00, 3, 100, 1, 10, '["basic_analytics","email_notifications"]', 1, 1, NOW(), NOW()),
('Pro', 'pro', 29.00, 290.00, 50, 1000, 5, 50, '["advanced_analytics","export_csv","export_pdf","custom_branding","email_notifications","skip_logic"]', 1, 2, NOW(), NOW()),
('Enterprise', 'enterprise', 99.00, 990.00, -1, -1, -1, -1, '["advanced_analytics","export_csv","export_pdf","custom_branding","email_notifications","skip_logic","api_access","white_label","priority_support","sso"]', 1, 3, NOW(), NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();
