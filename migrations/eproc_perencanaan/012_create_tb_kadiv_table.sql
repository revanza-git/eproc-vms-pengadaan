-- VMS eProc Migration
-- Database: eproc_perencanaan
-- Table: tb_kadiv
-- Generated: 2025-07-07 22:58:55

-- Create tb_kadiv table
CREATE TABLE IF NOT EXISTS `tb_kadiv` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(45) NULL,
    `del` tinyint(4) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

