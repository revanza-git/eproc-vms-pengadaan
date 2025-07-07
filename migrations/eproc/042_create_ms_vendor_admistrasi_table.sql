-- VMS eProc Migration
-- Database: eproc
-- Table: ms_vendor_admistrasi
-- Generated: 2025-07-07 22:58:55

-- Create ms_vendor_admistrasi table
CREATE TABLE IF NOT EXISTS `ms_vendor_admistrasi` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_vendor` bigint(20) NULL,
    `id_legal` int(11) NULL,
    `npwp_code` varchar(20) NULL,
    `npwp_date` date NULL,
    `npwp_file` varchar(40) NULL,
    `nppkp_code` varchar(45) NULL,
    `nppkp_date` date NULL,
    `nppkp_file` varchar(40) NULL,
    `vendor_office_status` varchar(7) NULL,
    `vendor_address` text NULL,
    `vendor_country` varchar(50) NULL,
    `vendor_province` varchar(50) NULL,
    `vendor_city` varchar(50) NULL,
    `vendor_phone` varchar(25) NULL,
    `vendor_fax` varchar(15) NULL,
    `vendor_email` varchar(50) NULL,
    `vendor_postal` varchar(10) NULL,
    `vendor_website` varchar(60) NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    `data_status` int(11) NULL,
    `data_last_check` timestamp NULL,
    `data_checker_id` bigint(20) NULL,
    `surat_pernyataan_file` varchar(255) NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

