-- VMS eProc Migration
-- Database: eproc
-- Table: tb_bast_print
-- Generated: 2025-07-07 22:58:55

-- Create tb_bast_print table
CREATE TABLE IF NOT EXISTS `tb_bast_print` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `text` text NULL,
    `entry_stamp` datetime NULL,
    `edit_stamp` datetime NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

