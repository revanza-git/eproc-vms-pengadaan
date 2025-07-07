-- VMS eProc Migration
-- Database: eproc
-- Table: ms_procurement_tatacara
-- Generated: 2025-07-07 22:58:55

-- Create ms_procurement_tatacara table
CREATE TABLE IF NOT EXISTS `ms_procurement_tatacara` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_procurement` int(11) NULL,
    `metode_auction` varchar(255) NULL,
    `metode_penawaran` varchar(255) NULL,
    `entry_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `edit_stamp` timestamp NULL,
    `del` int(11) NULL,
    `hps` int(11) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

