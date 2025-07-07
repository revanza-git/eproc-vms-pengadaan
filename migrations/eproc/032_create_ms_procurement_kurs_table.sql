-- VMS eProc Migration
-- Database: eproc
-- Table: ms_procurement_kurs
-- Generated: 2025-07-07 22:58:54

-- Create ms_procurement_kurs table
CREATE TABLE IF NOT EXISTS `ms_procurement_kurs` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_procurement` int(11) NOT NULL,
    `id_kurs` int(11) NOT NULL,
    `rate` int(11) NOT NULL,
    `entry_stamp` datetime NOT NULL,
    `edit_stamp` datetime NULL,
    `del` tinyint(1) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

