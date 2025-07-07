-- VMS eProc Migration
-- Database: eproc
-- Table: ms_akta
-- Generated: 2025-07-07 22:58:54

-- Create ms_akta table
CREATE TABLE IF NOT EXISTS `ms_akta` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_vendor` bigint(20) NULL,
    `type` varchar(20) NULL,
    `no` varchar(100) NULL,
    `notaris` varchar(150) NULL,
    `issue_date` date NULL,
    `akta_file` varchar(40) NULL,
    `file_extension_akta` text NULL,
    `authorize_by` varchar(200) NULL,
    `authorize_no` varchar(150) NULL,
    `authorize_file` varchar(40) NULL,
    `authorize_date` date NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    `data_status` int(11) NULL DEFAULT '0',
    `data_last_check` timestamp NULL,
    `data_checker_id` bigint(20) NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

