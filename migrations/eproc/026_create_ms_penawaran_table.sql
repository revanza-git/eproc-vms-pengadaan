-- VMS eProc Migration
-- Database: eproc
-- Table: ms_penawaran
-- Generated: 2025-07-07 22:58:54

-- Create ms_penawaran table
CREATE TABLE IF NOT EXISTS `ms_penawaran` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_procurement` int(11) NOT NULL,
    `id_vendor` int(11) NOT NULL,
    `id_barang` int(11) NOT NULL,
    `nilai` bigint(20) NOT NULL,
    `down_percent` decimal(5,2) NOT NULL,
    `id_kurs` int(11) NOT NULL,
    `in_rate` bigint(20) NOT NULL,
    `entry_stamp` datetime NOT NULL,
    `fee` decimal(15,2) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

