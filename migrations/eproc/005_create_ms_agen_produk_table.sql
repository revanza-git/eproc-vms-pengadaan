-- VMS eProc Migration
-- Database: eproc
-- Table: ms_agen_produk
-- Generated: 2025-07-07 22:58:54

-- Create ms_agen_produk table
CREATE TABLE IF NOT EXISTS `ms_agen_produk` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_agen` bigint(20) NULL,
    `produk` varchar(50) NULL,
    `merk` varchar(50) NULL,
    `edit_stamp` timestamp NULL,
    `entry_stamp` timestamp NULL,
    `data_status` int(11) NULL DEFAULT '0',
    `data_last_check` timestamp NULL,
    `data_checker_id` bigint(20) NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

