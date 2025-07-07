-- VMS eProc Migration
-- Database: eproc_perencanaan
-- Table: ms_perencanaan_umum
-- Generated: 2025-07-07 22:58:55

-- Create ms_perencanaan_umum table
CREATE TABLE IF NOT EXISTS `ms_perencanaan_umum` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_fppbj` text NULL,
    `lampiran` text NOT NULL,
    `lampiran_approved` text NOT NULL,
    `note` text NULL,
    `year` varchar(4) NULL,
    `description` longtext NULL,
    `date_close` date NOT NULL,
    `entry_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `edit_stamp` timestamp NULL,
    `del` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

