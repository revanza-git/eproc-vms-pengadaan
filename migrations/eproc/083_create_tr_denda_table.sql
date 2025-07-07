-- VMS eProc Migration
-- Database: eproc
-- Table: tr_denda
-- Generated: 2025-07-07 22:58:55

-- Create tr_denda table
CREATE TABLE IF NOT EXISTS `tr_denda` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_procurement` varchar(45) NULL,
    `id_spk` int(11) NOT NULL,
    `id_bast` int(11) NOT NULL,
    `start_date` date NULL,
    `end_date` date NULL,
    `denda` int(11) NULL,
    `entry_stamp` datetime NULL,
    `edit_stamp` datetime NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

