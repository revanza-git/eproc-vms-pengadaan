-- VMS eProc Migration
-- Database: eproc
-- Table: ms_agen_bsb
-- Generated: 2025-07-07 22:58:54

-- Create ms_agen_bsb table
CREATE TABLE IF NOT EXISTS `ms_agen_bsb` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_agen` bigint(20) NULL,
    `id_bsb` bigint(20) NULL,
    `id_vendor` bigint(20) NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    `data_status` int(11) NULL DEFAULT '0',
    `data_last_check` timestamp NULL,
    `data_checker_id` bigint(20) NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

