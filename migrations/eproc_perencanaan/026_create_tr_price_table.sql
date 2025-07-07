-- VMS eProc Migration
-- Database: eproc_perencanaan
-- Table: tr_price
-- Generated: 2025-07-07 22:58:55

-- Create tr_price table
CREATE TABLE IF NOT EXISTS `tr_price` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_fppbj` int(11) NOT NULL,
    `idr_anggaran` decimal(45,2) NOT NULL,
    `usd_anggaran` decimal(40,2) NOT NULL,
    `year_anggaran` varchar(4) NOT NULL,
    `entry_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `edit_stamp` timestamp NULL,
    `del` tinyint(1) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

