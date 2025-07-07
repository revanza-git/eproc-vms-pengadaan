-- VMS eProc Migration
-- Database: eproc
-- Table: tr_ass_result
-- Generated: 2025-07-07 22:58:55

-- Create tr_ass_result table
CREATE TABLE IF NOT EXISTS `tr_ass_result` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_vendor` int(11) NULL,
    `id_procurement` varchar(45) NULL,
    `id_ass` int(11) NULL,
    `value` varchar(11) NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    `is_approve` tinyint(1) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

