-- VMS eProc Migration
-- Database: eproc
-- Table: ms_ass
-- Generated: 2025-07-07 22:58:54

-- Create ms_ass table
CREATE TABLE IF NOT EXISTS `ms_ass` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_group` int(11) NULL,
    `id_role` int(11) NULL,
    `value` text NULL,
    `point` int(11) NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

