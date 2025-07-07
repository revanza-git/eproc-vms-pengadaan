-- VMS eProc Migration
-- Database: eproc_perencanaan
-- Table: tb_role
-- Generated: 2025-07-07 22:58:55

-- Create tb_role table
CREATE TABLE IF NOT EXISTS `tb_role` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(45) NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

