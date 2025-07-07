-- VMS eProc Migration
-- Database: eproc
-- Table: ms_bast
-- Generated: 2025-07-07 22:58:54

-- Create ms_bast table
CREATE TABLE IF NOT EXISTS `ms_bast` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_proc` int(11) NOT NULL,
    `id_spk` int(11) NULL,
    `no` varchar(255) NOT NULL,
    `bast_type` varchar(20) NOT NULL,
    `start_date` date NULL,
    `end_date` date NOT NULL,
    `spk_file` varchar(255) NULL,
    `bast_file` varchar(255) NOT NULL,
    `entry_stamp` datetime NOT NULL,
    `edit_stamp` datetime NULL,
    `del` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

