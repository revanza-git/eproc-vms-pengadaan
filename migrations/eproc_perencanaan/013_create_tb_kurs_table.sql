-- VMS eProc Migration
-- Database: eproc_perencanaan
-- Table: tb_kurs
-- Generated: 2025-07-07 22:58:55

-- Create tb_kurs table
CREATE TABLE IF NOT EXISTS `tb_kurs` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(45) NOT NULL,
    `symbol` varchar(5) NULL,
    `entry_stamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `edit_stamp` timestamp NULL,
    `del` tinyint(1) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

