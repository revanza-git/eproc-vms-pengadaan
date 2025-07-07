-- VMS eProc Migration
-- Database: eproc
-- Table: tr_surat_ubo
-- Generated: 2025-07-07 22:58:55

-- Create tr_surat_ubo table
CREATE TABLE IF NOT EXISTS `tr_surat_ubo` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_vendor` int(11) NOT NULL,
    `ubo_file` varchar(255) NOT NULL,
    `entry_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `edit_stamp` timestamp NULL,
    `del` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

