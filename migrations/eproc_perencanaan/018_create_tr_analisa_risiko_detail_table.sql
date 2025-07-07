-- VMS eProc Migration
-- Database: eproc_perencanaan
-- Table: tr_analisa_risiko_detail
-- Generated: 2025-07-07 22:58:55

-- Create tr_analisa_risiko_detail table
CREATE TABLE IF NOT EXISTS `tr_analisa_risiko_detail` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_analisa_risiko` int(11) NULL,
    `is_approved` int(11) NULL DEFAULT '0',
    `apa` varchar(500) NULL,
    `manusia` int(11) NULL,
    `asset` int(11) NULL,
    `lingkungan` int(11) NULL,
    `hukum` int(11) NULL,
    `entry_stamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `edit_stamp` timestamp NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

