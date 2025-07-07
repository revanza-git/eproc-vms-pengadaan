-- VMS eProc Migration
-- Database: eproc
-- Table: ms_ass_group
-- Generated: 2025-07-07 22:58:54

-- Create ms_ass_group table
CREATE TABLE IF NOT EXISTS `ms_ass_group` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` text NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

