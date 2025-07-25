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
  `user_id` int NOT NULL,
  `created_date` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `Updated_date` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_tododata` (`id`, `title`, `status`, `user_id`, `created_date`, `Updated_date`) VALUES
(23,	'asdasdasd',	0,	0,	'2025-06-28 11:34:39',	'2025-06-28 11:34:39'),
(24,	'asdcasdasd',	0,	0,	'2025-06-28 11:34:51',	'2025-06-28 11:34:51');

DROP TABLE IF EXISTS `tbl_users`;
CREATE TABLE `tbl_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_users` (`id`, `name`, `email`, `username`, `password`, `user_id`, `created_at`, `updated_at`) VALUES
(1,	'sahas shakya',	'statshakya@gmail.com',	'sahas',	'$2y$10$8kkanefUfxInvKrxnabHP.PvI.RfPMmfxa9Cxke3Xs4oBBN.FahuK',	0,	'2025-06-28 08:25:56',	'2025-07-13 07:42:57'),
(2,	'test',	'test@gmail.com',	'test',	'$2y$10$eB/w8vZ/StbNgxkrKUWl9OGHf7oPVzM6Juy48CicwJZr6lOOCdaYi',	0,	'2025-06-28 10:34:48',	'2025-06-28 10:34:48'),
(3,	'thumbnails',	'statshakya123@gmail.com',	'stat',	'$2y$10$QNur7LwMt9qhEEOIHjeMr.Y9rqNlFj7fEFb8HhyzjfE6pdu03eD8a',	0,	'2025-07-05 03:34:04',	'2025-07-05 03:34:04');

-- 2025-07-13 07:54:01