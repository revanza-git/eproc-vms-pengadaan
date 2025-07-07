-- VMS eProc Migration
-- Database: eproc
-- Table: tr_assessment
-- Generated: 2025-07-07 22:58:55

-- Create tr_assessment table
CREATE TABLE IF NOT EXISTS `tr_assessment` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_vendor` int(11) NULL,
    `point` int(11) NULL,
    `category` tinyint(4) NULL,
    `entry_stamp` datetime NULL,
    `edit_stamp` datetime NULL,
    `del` tinyint(4) NULL DEFAULT '0',
    `id_procurement` int(11) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

