-- VMS eProc Migration
-- Database: eproc
-- Table: ms_pengalaman
-- Generated: 2025-07-07 22:58:54

-- Create ms_pengalaman table
CREATE TABLE IF NOT EXISTS `ms_pengalaman` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_iu_bsb` bigint(20) NULL,
    `id_ijin_usaha` bigint(20) NULL,
    `id_sbu` int(11) NULL,
    `id_vendor` bigint(20) NULL,
    `job_name` varchar(300) NULL,
    `job_location` varchar(300) NULL,
    `job_giver` varchar(150) NULL,
    `phone_no` varchar(20) NULL,
    `contract_no` varchar(150) NULL,
    `contract_start` date NULL,
    `contract_end` date NULL,
    `bast_date` date NULL,
    `price_idr` decimal(15,2) NULL,
    `price_foreign` decimal(15,2) NULL,
    `currency` varchar(3) NULL,
    `contract_file` varchar(40) NULL,
    `bast_file` varchar(40) NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    `data_status` int(11) NULL DEFAULT '0',
    `data_last_check` timestamp NULL,
    `data_checker_id` bigint(20) NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

