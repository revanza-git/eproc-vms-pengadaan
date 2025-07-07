-- VMS eProc Migration
-- Database: eproc
-- Table: tb_dpt_type
-- Generated: 2025-07-07 22:58:55

-- Create tb_dpt_type table
CREATE TABLE IF NOT EXISTS `tb_dpt_type` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(60) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

