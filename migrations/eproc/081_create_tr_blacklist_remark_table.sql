-- VMS eProc Migration
-- Database: eproc
-- Table: tr_blacklist_remark
-- Generated: 2025-07-07 22:58:55

-- Create tr_blacklist_remark table
CREATE TABLE IF NOT EXISTS `tr_blacklist_remark` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `remark` text NOT NULL,
    `type` varchar(50) NOT NULL,
    `del` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

