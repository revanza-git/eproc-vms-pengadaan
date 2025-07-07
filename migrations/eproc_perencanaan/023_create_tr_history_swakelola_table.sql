-- VMS eProc Migration
-- Database: eproc_perencanaan
-- Table: tr_history_swakelola
-- Generated: 2025-07-07 22:58:55

-- Create tr_history_swakelola table
CREATE TABLE IF NOT EXISTS `tr_history_swakelola` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_pengadaan` int(11) NULL,
    `waktu` varchar(45) NULL,
    `biaya` varchar(45) NULL,
    `tenaga` varchar(45) NULL,
    `bahan` varchar(45) NULL,
    `peralatan` varchar(45) NULL,
    `desc` text NULL,
    `entry_stamp` datetime NULL DEFAULT CURRENT_TIMESTAMP,
    `edit_stamp` datetime NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

