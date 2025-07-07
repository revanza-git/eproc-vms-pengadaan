-- VMS eProc Migration
-- Database: eproc
-- Table: tr_feedback
-- Generated: 2025-07-07 22:58:55

-- Create tr_feedback table
CREATE TABLE IF NOT EXISTS `tr_feedback` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_procurement` int(11) NOT NULL,
    `id_vendor` int(11) NOT NULL,
    `remark` text NOT NULL,
    `is_reply` tinyint(1) NOT NULL DEFAULT '0',
    `reply` text NOT NULL,
    `reply_by` int(11) NOT NULL,
    `entry_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `edit_stamp` timestamp NULL,
    `del` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

