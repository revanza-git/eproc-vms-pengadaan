-- VMS eProc Migration
-- Database: eproc_perencanaan
-- Table: tb_in_rate
-- Generated: 2025-07-07 22:58:55

-- Create tb_in_rate table
CREATE TABLE IF NOT EXISTS `tb_in_rate` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `kurs` varchar(45) NULL,
    `value_in_idr` decimal(25,2) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

