-- VMS eProc Migration
-- Database: eproc
-- Table: ms_tdp
-- Generated: 2025-07-07 22:58:55

-- Create ms_tdp table
CREATE TABLE IF NOT EXISTS `ms_tdp` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_vendor` bigint(20) NULL,
    `no` varchar(45) NULL,
    `issue_date` date NULL,
    `expiry_date` varchar(11) NULL,
    `entry_stamp` timestamp NULL,
    `tdp_file` varchar(40) NULL,
    `authorize_by` varchar(200) NULL,
    `extension_file` varchar(40) NULL,
    `data_status` int(11) NULL DEFAULT '0',
    `data_last_check` timestamp NULL,
    `data_checker_id` bigint(20) NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    `ms_tdpcol` varchar(45) NULL,
    `edit_stamp` timestamp NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

