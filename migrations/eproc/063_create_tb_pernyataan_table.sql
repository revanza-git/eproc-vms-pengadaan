-- VMS eProc Migration
-- Database: eproc
-- Table: tb_pernyataan
-- Generated: 2025-07-07 22:58:55

-- Create tb_pernyataan table
CREATE TABLE IF NOT EXISTS `tb_pernyataan` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `value` text NOT NULL,
    `entry_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `edit_stamp` timestamp NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

