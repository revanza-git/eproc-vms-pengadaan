-- VMS eProc Migration
-- Database: eproc
-- Table: ms_procurement_barang
-- Generated: 2025-07-07 22:58:54

-- Create ms_procurement_barang table
CREATE TABLE IF NOT EXISTS `ms_procurement_barang` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_procurement` int(11) NOT NULL,
    `is_catalogue` tinyint(1) NULL,
    `id_material` int(11) NULL,
    `nama_barang` varchar(255) NOT NULL,
    `nilai_hps` bigint(20) NOT NULL,
    `id_kurs` int(11) NOT NULL,
    `entry_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `edit_stamp` timestamp NULL,
    `del` int(11) NOT NULL,
    `volume` int(11) NOT NULL,
    `category` varchar(15) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

