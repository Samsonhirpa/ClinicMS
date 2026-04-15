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
