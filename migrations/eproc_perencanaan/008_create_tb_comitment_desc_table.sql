-- VMS eProc Migration
-- Database: eproc_perencanaan
-- Table: tb_comitment_desc
-- Generated: 2025-07-07 22:58:55

-- Create tb_comitment_desc table
CREATE TABLE IF NOT EXISTS `tb_comitment_desc` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `description` longtext NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

