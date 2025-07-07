-- VMS eProc Migration
-- Database: eproc_perencanaan
-- Table: tr_note
-- Generated: 2025-07-07 22:58:55

-- Create tr_note table
CREATE TABLE IF NOT EXISTS `tr_note` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_user` int(11) NULL,
    `id_fppbj` int(11) NOT NULL,
    `entry_by` int(11) NULL,
    `is_active` int(11) NULL,
    `is_user_close` int(11) NULL,
    `is_admin_close` int(11) NULL,
    `value` text NULL,
    `type` varchar(20) NULL,
    `is_note_reject` int(11) NOT NULL,
    `entry_stamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `edit_stamp` timestamp NULL,
    `document` text NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

