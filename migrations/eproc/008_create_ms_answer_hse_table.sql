-- VMS eProc Migration
-- Database: eproc
-- Table: ms_answer_hse
-- Generated: 2025-07-07 22:58:54

-- Create ms_answer_hse table
CREATE TABLE IF NOT EXISTS `ms_answer_hse` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_question` int(11) NULL,
    `type` varchar(20) NULL,
    `value` text NULL,
    `label` text NULL,
    `entry_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `edit_stamp` timestamp NULL,
    `del` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

