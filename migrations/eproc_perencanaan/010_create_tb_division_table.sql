-- VMS eProc Migration
-- Database: eproc_perencanaan
-- Table: tb_division
-- Generated: 2025-07-07 22:58:55

-- Create tb_division table
CREATE TABLE IF NOT EXISTS `tb_division` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_kadiv` int(11) NULL,
    `name` varchar(70) NOT NULL,
    `del` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

