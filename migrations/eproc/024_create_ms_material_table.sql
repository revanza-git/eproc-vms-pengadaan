-- VMS eProc Migration
-- Database: eproc
-- Table: ms_material
-- Generated: 2025-07-07 22:58:54

-- Create ms_material table
CREATE TABLE IF NOT EXISTS `ms_material` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_barang` int(11) NOT NULL,
    `nama` text NULL,
    `for` varchar(255) NOT NULL,
    `id_kurs` tinyint(4) NULL,
    `gambar_barang` varchar(50) NULL,
    `remark` text NULL,
    `category` varchar(25) NOT NULL,
    `entry_stamp` datetime NULL,
    `edit_stamp` datetime NULL,
    `del` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

