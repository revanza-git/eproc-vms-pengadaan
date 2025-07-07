-- VMS eProc Migration
-- Database: eproc
-- Table: tr_note
-- Generated: 2025-07-07 22:58:55

-- Create tr_note table
CREATE TABLE IF NOT EXISTS `tr_note` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_vendor` int(11) NULL,
    `value` text NULL,
    `entry_stamp` datetime NULL,
    `edit_stamp` datetime NULL,
    `is_active` tinyint(1) NULL DEFAULT '1',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

