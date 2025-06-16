-- Adminer 4.8.1 MySQL 9.2.0 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `tbl_tododata`;
CREATE TABLE `tbl_tododata` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `Updated_date` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_tododata` (`id`, `title`, `status`, `created_date`, `Updated_date`) VALUES
(17,	'test',	1,	'2025-06-15 11:45:53',	'2025-06-15 11:45:53'),
(18,	'test 1',	1,	'2025-06-15 11:46:12',	'2025-06-15 11:46:12');

-- 2025-06-16 17:33:37