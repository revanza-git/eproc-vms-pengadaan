-- VMS eProc Migration
-- Database: eproc
-- Table: tr_ass_point
-- Generated: 2025-07-07 22:58:55

-- Create tr_ass_point table
CREATE TABLE IF NOT EXISTS `tr_ass_point` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_vendor` int(11) NULL,
    `id_procurement` int(11) NULL,
    `point` int(11) NULL,
    `date` datetime NULL,
    `entry_stamp` datetime NULL,
    `edit_stamp` datetime NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

