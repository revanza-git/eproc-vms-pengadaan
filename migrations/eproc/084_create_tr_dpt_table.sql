-- VMS eProc Migration
-- Database: eproc
-- Table: tr_dpt
-- Generated: 2025-07-07 22:58:55

-- Create tr_dpt table
CREATE TABLE IF NOT EXISTS `tr_dpt` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_vendor` bigint(20) NULL,
    `id_dpt_type` int(11) NULL,
    `start_date` date NULL,
    `end_date` date NULL,
    `status` tinyint(1) NULL DEFAULT '0',
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

