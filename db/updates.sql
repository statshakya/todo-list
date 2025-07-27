-- SMS 2025-07-24
ALTER TABLE `tbl_tododata` ADD `type` VARCHAR(50) NOT NULL AFTER `title`; 

-- SMS 2025-07-27
CREATE TABLE `tbl_events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tbl_notes` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `fileupload` text NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `tbl_events` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tbl_notes` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
