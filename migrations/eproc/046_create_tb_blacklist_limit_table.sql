-- VMS eProc Migration
-- Database: eproc
-- Table: tb_blacklist_limit
-- Generated: 2025-07-07 22:58:55

-- Create tb_blacklist_limit table
CREATE TABLE IF NOT EXISTS `tb_blacklist_limit` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `start_score` int(11) NULL,
    `end_score` int(11) NULL,
    `value` varchar(45) NULL,
    `number_range` int(11) NULL,
    `range_time` varchar(20) NULL,
    `del` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

