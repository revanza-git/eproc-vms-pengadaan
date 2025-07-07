-- VMS eProc Migration
-- Database: eproc
-- Table: ms_pemilik
-- Generated: 2025-07-07 22:58:54

-- Create ms_pemilik table
CREATE TABLE IF NOT EXISTS `ms_pemilik` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_vendor` bigint(20) NULL,
    `id_akta` int(11) NOT NULL,
    `name` varchar(100) NULL,
    `position` varchar(60) NULL,
    `percentage` bigint(20) NULL,
    `shares` int(11) NOT NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    `data_status` int(11) NULL DEFAULT '0',
    `data_last_check` timestamp NULL,
    `data_checker_id` bigint(20) NULL,
    `del` tinyint(1) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

