-- VMS eProc Migration
-- Database: eproc_perencanaan
-- Table: tr_email_blast
-- Generated: 2025-07-07 22:58:55

-- Create tr_email_blast table
CREATE TABLE IF NOT EXISTS `tr_email_blast` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_pengadaan` int(11) NOT NULL,
    `date_alert` date NOT NULL,
    `type` int(11) NOT NULL,
    `entry_stamp` datetime NULL,
    `edit_stamp` datetime NULL,
    `del` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

