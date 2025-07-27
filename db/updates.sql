-- SMS 2025-07-24
ALTER TABLE `tbl_tododata` ADD `type` VARCHAR(50) NOT NULL AFTER `title`; 

-- Adminer 4.8.1 MySQL 9.2.0 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `tbl_notes`;
CREATE TABLE `tbl_notes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `user_id` int NOT NULL,
  `fileupload` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_date` timestamp NOT NULL,
  `Updated_date` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `tbl_notes` (`id`, `title`, `content`, `user_id`, `fileupload`, `created_date`, `Updated_date`) VALUES
(7,	'da',	'sdasdasd',	1,	NULL,	'2025-07-26 07:49:59',	'2025-07-26 08:16:08');

-- 2025-07-26 08:18:23

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `tbl_events`;
CREATE TABLE `tbl_events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `user_id` int NOT NULL,
  `created_date` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_date` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
