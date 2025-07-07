-- VMS eProc Migration
-- Database: eproc
-- Table: tb_progress_pengadaan
-- Generated: 2025-07-07 22:58:55

-- Create tb_progress_pengadaan table
CREATE TABLE IF NOT EXISTS `tb_progress_pengadaan` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_procurement` int(11) NOT NULL,
    `no` int(11) NULL,
    `value` text NULL,
    `lampiran` text NULL,
    `color` varchar(8) NULL,
    `category` text NULL,
    `entry_stamp` datetime NULL,
    `edit_stamp` datetime NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

