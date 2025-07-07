-- VMS eProc Migration
-- Database: eproc
-- Table: tr_progress_pengadaan
-- Generated: 2025-07-07 22:58:55

-- Create tr_progress_pengadaan table
CREATE TABLE IF NOT EXISTS `tr_progress_pengadaan` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_proc` int(11) NULL,
    `id_progress` int(11) NULL,
    `value` tinyint(1) NULL,
    `date` date NULL,
    `date_` date NULL,
    `file` text NULL,
    `entry_stamp` datetime NULL,
    `edit_stamp` datetime NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

