-- VMS eProc Migration
-- Database: eproc_perencanaan
-- Table: tr_log_activity
-- Generated: 2025-07-07 22:58:55

-- Create tr_log_activity table
CREATE TABLE IF NOT EXISTS `tr_log_activity` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_user` int(11) NULL,
    `activity` longtext NOT NULL,
    `activity_date` datetime NOT NULL,
    `iden` int(11) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

