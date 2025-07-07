-- VMS eProc Migration
-- Database: eproc
-- Table: ms_procurement_persyaratan
-- Generated: 2025-07-07 22:58:55

-- Create ms_procurement_persyaratan table
CREATE TABLE IF NOT EXISTS `ms_procurement_persyaratan` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_proc` int(11) NULL,
    `description` text NULL,
    `entry_stamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `edit_stamp` timestamp NULL,
    `del` int(11) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

