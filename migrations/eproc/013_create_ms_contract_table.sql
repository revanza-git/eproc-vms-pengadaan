-- VMS eProc Migration
-- Database: eproc
-- Table: ms_contract
-- Generated: 2025-07-07 22:58:54

-- Create ms_contract table
CREATE TABLE IF NOT EXISTS `ms_contract` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_procurement` bigint(20) NULL,
    `id_vendor` bigint(20) NULL,
    `id_kurs` int(11) NOT NULL,
    `no_sppbj` varchar(100) NULL,
    `sppbj_date` date NULL,
    `no_spmk` varchar(100) NULL,
    `spmk_date` date NULL,
    `start_work` date NULL,
    `end_work` date NULL,
    `contract_date` date NULL,
    `no_contract` varchar(100) NULL,
    `po_file` varchar(60) NULL,
    `contract_price` bigint(20) NULL,
    `contract_price_kurs` bigint(20) NULL,
    `contract_kurs` int(11) NULL,
    `start_contract` date NULL,
    `end_contract` date NULL,
    `end_actual` date NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

