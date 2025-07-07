-- VMS eProc Migration
-- Database: eproc
-- Table: ms_vendor
-- Generated: 2025-07-07 22:58:55

-- Create ms_vendor table
CREATE TABLE IF NOT EXISTS `ms_vendor` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_sbu` int(11) NULL,
    `vendor_status` tinyint(1) NULL DEFAULT '0',
    `npwp_code` varchar(20) NULL,
    `vendor_code` varchar(50) NULL,
    `name` varchar(150) NULL,
    `is_active` tinyint(1) NULL DEFAULT '1',
    `certificate_no` varchar(50) NULL,
    `ever_blacklisted` tinyint(1) NULL DEFAULT '0',
    `dpt_first_date` datetime NULL,
    `is_vms` tinyint(1) NULL DEFAULT '1',
    `need_approve` tinyint(1) NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    `del` int(11) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

