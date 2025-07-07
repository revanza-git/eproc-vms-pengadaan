-- VMS eProc Migration
-- Database: eproc
-- Table: tr_answer_hse
-- Generated: 2025-07-07 22:58:55

-- Create tr_answer_hse table
CREATE TABLE IF NOT EXISTS `tr_answer_hse` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_answer` int(11) NULL,
    `id_vendor` bigint(20) NULL,
    `value` text NULL,
    `entry_stamp` datetime NULL,
    `edit_time` datetime NULL,
    `id_csms` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

