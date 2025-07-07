-- VMS eProc Migration
-- Database: eproc
-- Table: tr_dpt_2
-- Generated: 2025-07-07 22:58:55

-- Create tr_dpt_2 table
CREATE TABLE IF NOT EXISTS `tr_dpt_2` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_vendor` int(11) NOT NULL,
    `id_bidang` int(11) NOT NULL,
    `id_dpt_type` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

