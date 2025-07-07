-- VMS eProc Migration
-- Database: eproc_perencanaan
-- Table: tb_csms_limit
-- Generated: 2025-07-07 22:58:55

-- Create tb_csms_limit table
CREATE TABLE IF NOT EXISTS `tb_csms_limit` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `start_score` decimal(11,2) NULL,
    `end_score` decimal(11,2) NULL,
    `value` varchar(45) NULL,
    `entry_stamp` datetime NULL,
    `edit_stamp` datetime NULL,
    `del` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

