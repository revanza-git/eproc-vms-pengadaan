-- VMS eProc Migration
-- Database: eproc_perencanaan
-- Table: tr_history_analisa_resiko
-- Generated: 2025-07-07 22:58:55

-- Create tr_history_analisa_resiko table
CREATE TABLE IF NOT EXISTS `tr_history_analisa_resiko` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_analisa_risiko` int(11) NULL,
    `id_pengadaan` int(11) NULL,
    `apa` varchar(45) NULL,
    `manusia` varchar(45) NULL,
    `asset` varchar(45) NULL,
    `lingkungan` varchar(45) NULL,
    `hukum` varchar(45) NULL,
    `entry_stamp` datetime NULL DEFAULT CURRENT_TIMESTAMP,
    `edit_stamp` datetime NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

