-- VMS eProc Migration
-- Database: eproc
-- Table: ms_agen
-- Generated: 2025-07-07 22:58:54

-- Create ms_agen table
CREATE TABLE IF NOT EXISTS `ms_agen` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `no` varchar(50) NULL,
    `issue_date` varchar(10) NULL,
    `principal` varchar(255) NOT NULL,
    `type` varchar(20) NULL,
    `expire_date` varchar(10) NULL,
    `agen_file` varchar(40) NULL,
    `id_vendor` bigint(20) NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    `data_status` int(11) NULL DEFAULT '0',
    `data_last_check` timestamp NULL,
    `data_checker_id` bigint(20) NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

