-- VMS eProc Migration
-- Database: eproc
-- Table: ms_ijin_usaha
-- Generated: 2025-07-07 22:58:54

-- Create ms_ijin_usaha table
CREATE TABLE IF NOT EXISTS `ms_ijin_usaha` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_vendor` bigint(20) NULL,
    `id_dpt_type` bigint(20) NULL,
    `type` varchar(50) NULL,
    `no` varchar(100) NULL,
    `grade` varchar(5) NULL,
    `issue_date` varchar(45) NULL,
    `qualification` varchar(10) NULL,
    `authorize_by` varchar(200) NULL,
    `izin_file` varchar(40) NULL,
    `expire_date` varchar(11) NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    `data_status` int(11) NULL DEFAULT '0',
    `data_last_check` timestamp NULL,
    `data_checker_id` bigint(20) NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

