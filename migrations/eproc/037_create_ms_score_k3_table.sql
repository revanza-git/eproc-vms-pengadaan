-- VMS eProc Migration
-- Database: eproc
-- Table: ms_score_k3
-- Generated: 2025-07-07 22:58:55

-- Create ms_score_k3 table
CREATE TABLE IF NOT EXISTS `ms_score_k3` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_vendor` int(11) NULL,
    `score` decimal(4,2) NULL,
    `data_status` tinyint(1) NULL,
    `entry_stamp` date NULL,
    `edit_stamp` date NULL,
    `data_last_check` timestamp NULL,
    `data_checker_id` bigint(20) NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    `id_csms_limit` int(11) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

