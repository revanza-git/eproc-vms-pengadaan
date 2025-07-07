-- VMS eProc Migration
-- Database: eproc
-- Table: ms_evaluasi_data
-- Generated: 2025-07-07 22:58:54

-- Create ms_evaluasi_data table
CREATE TABLE IF NOT EXISTS `ms_evaluasi_data` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_quest` varchar(45) NULL,
    `data` text NULL,
    `file_name` varchar(60) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

