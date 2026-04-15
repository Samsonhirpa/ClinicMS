CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','reception','doctor','lab','nurse') NOT NULL DEFAULT 'reception',
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default admin login: admin@clinicms.local / Admin@123
INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`, `created_at`)
SELECT 'System Admin', 'admin@clinicms.local',
       '$2y$12$h0CohUoC.BZgx9/Zf.twQ.lTVKU9/3OAl3QsyD.1uMXc3pPmaJzrC',
       'admin', 'active', NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `users` WHERE `email` = 'admin@clinicms.local'
);

CREATE TABLE IF NOT EXISTS `patients` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `patient_code` VARCHAR(50) NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `age` INT UNSIGNED NULL,
  `gender` VARCHAR(20) NULL,
  `phone` VARCHAR(30) NULL,
  `address` VARCHAR(255) NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'active',
  `created_by` INT UNSIGNED NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_patient_code` (`patient_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `patient_payments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `patient_id` INT UNSIGNED NOT NULL,
  `payment_type` VARCHAR(40) NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `currency` VARCHAR(5) NOT NULL DEFAULT 'ETB',
  `status` VARCHAR(20) NOT NULL DEFAULT 'pending',
  `approved_by` INT UNSIGNED NULL,
  `approved_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_payment_type_status` (`payment_type`, `status`),
  KEY `idx_patient` (`patient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `doctor_cases` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `patient_id` INT UNSIGNED NOT NULL,
  `doctor_id` INT UNSIGNED NULL,
  `consultation_note` TEXT NULL,
  `recommended_tests` TEXT NULL,
  `status` VARCHAR(40) NOT NULL DEFAULT 'new',
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_doctor_case_patient` (`patient_id`),
  KEY `idx_doctor_status` (`doctor_id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `lab_requests` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `doctor_case_id` INT UNSIGNED NOT NULL,
  `patient_id` INT UNSIGNED NOT NULL,
  `requested_by` INT UNSIGNED NOT NULL,
  `test_name` VARCHAR(255) NOT NULL,
  `instructions` TEXT NULL,
  `result_text` TEXT NULL,
  `status` VARCHAR(30) NOT NULL DEFAULT 'pending_payment',
  `completed_by` INT UNSIGNED NULL,
  `completed_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_lab_status` (`status`),
  KEY `idx_lab_case` (`doctor_case_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
