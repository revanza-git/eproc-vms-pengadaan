-- VMS eProc Migration
-- Database: eproc
-- Table: ms_procurement_bsb
-- Generated: 2025-07-07 22:58:54

-- Create ms_procurement_bsb table
CREATE TABLE IF NOT EXISTS `ms_procurement_bsb` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_proc` bigint(20) NOT NULL,
    `id_bidang` bigint(20) NOT NULL,
    `id_sub_bidang` bigint(20) NOT NULL,
    `entry_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `del` tinyint(1) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

