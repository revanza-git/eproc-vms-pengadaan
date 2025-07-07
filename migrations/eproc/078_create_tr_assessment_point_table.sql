-- VMS eProc Migration
-- Database: eproc
-- Table: tr_assessment_point
-- Generated: 2025-07-07 22:58:55

-- Create tr_assessment_point table
CREATE TABLE IF NOT EXISTS `tr_assessment_point` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_vendor` int(11) NULL,
    `point` int(11) NULL,
    `category` tinyint(1) NULL,
    `entry_stamp` datetime NULL,
    `edit_stamp` datetime NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

