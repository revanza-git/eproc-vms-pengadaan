-- VMS eProc Migration
-- Database: eproc
-- Table: ms_situ
-- Generated: 2025-07-07 22:58:55

-- Create ms_situ table
CREATE TABLE IF NOT EXISTS `ms_situ` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_vendor` bigint(20) NULL,
    `type` varchar(100) NULL,
    `no` varchar(100) NULL,
    `issue_date` date NULL,
    `issue_by` varchar(45) NOT NULL,
    `address` text NULL,
    `situ_file` varchar(35) NULL,
    `file_photo` varchar(35) NULL,
    `file_extension_situ` text NULL,
    `expire_date` varchar(11) NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    `data_status` int(11) NULL DEFAULT '0',
    `data_last_check` timestamp NULL,
    `data_checker_id` bigint(20) NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

