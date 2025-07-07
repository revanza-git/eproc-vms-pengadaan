-- VMS eProc Migration
-- Database: eproc
-- Table: tr_blacklist
-- Generated: 2025-07-07 22:58:55

-- Create tr_blacklist table
CREATE TABLE IF NOT EXISTS `tr_blacklist` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_vendor` bigint(20) NULL,
    `id_blacklist` int(11) NULL,
    `start_date` varchar(40) NULL,
    `end_date` varchar(40) NULL,
    `remark` text NULL,
    `blacklist_file` varchar(50) NULL,
    `entry_stamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `edit_stamp` timestamp NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    `need_approve` tinyint(1) NULL DEFAULT '0',
    `is_white` tinyint(1) NULL DEFAULT '0',
    `white_file` varchar(50) NULL,
    `white_date` varchar(40) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

