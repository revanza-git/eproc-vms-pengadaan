-- VMS eProc Migration
-- Database: eproc
-- Table: tb_kurs
-- Generated: 2025-07-07 22:58:55

-- Create tb_kurs table
CREATE TABLE IF NOT EXISTS `tb_kurs` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(20) NULL,
    `symbol` varchar(3) NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    `session_id` varchar(10) NULL,
    `del` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

