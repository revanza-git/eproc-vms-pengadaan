-- VMS eProc Migration
-- Database: eproc_perencanaan
-- Table: tr_analisa_swakelola
-- Generated: 2025-07-07 22:58:55

-- Create tr_analisa_swakelola table
CREATE TABLE IF NOT EXISTS `tr_analisa_swakelola` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_fppbj` int(11) NULL,
    `is_approved` int(11) NULL DEFAULT '0',
    `waktu` int(11) NULL,
    `biaya` int(11) NULL,
    `tenaga` int(11) NULL,
    `bahan` int(11) NULL,
    `peralatan` int(11) NULL,
    `desc` text NULL,
    `entry_stamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `edit_stamp` timestamp NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

