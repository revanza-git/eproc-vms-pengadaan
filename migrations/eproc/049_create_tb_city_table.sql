-- VMS eProc Migration
-- Database: eproc
-- Table: tb_city
-- Generated: 2025-07-07 22:58:55

-- Create tb_city table
CREATE TABLE IF NOT EXISTS `tb_city` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_country` int(11) NULL,
    `id_province` int(11) NULL,
    `name` varchar(15) NULL,
    `index` tinyint(4) NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

