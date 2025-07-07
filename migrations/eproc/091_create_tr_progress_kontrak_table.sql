-- VMS eProc Migration
-- Database: eproc
-- Table: tr_progress_kontrak
-- Generated: 2025-07-07 22:58:55

-- Create tr_progress_kontrak table
CREATE TABLE IF NOT EXISTS `tr_progress_kontrak` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_procurement` int(11) NOT NULL,
    `id_contract` int(11) NULL,
    `id_spk` int(11) NULL,
    `id_bast` int(11) NULL,
    `id_amandemen` int(11) NULL,
    `denda` varchar(45) NOT NULL,
    `step_name` longtext NULL,
    `supposed` int(11) NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    `solid_start_date` datetime NOT NULL,
    `start_date` datetime NULL,
    `end_date` datetime NULL,
    `type` tinyint(1) NULL DEFAULT '0',
    `parent` int(11) NULL,
    `is_kontrak` int(11) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

