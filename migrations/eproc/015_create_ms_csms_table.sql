-- VMS eProc Migration
-- Database: eproc
-- Table: ms_csms
-- Generated: 2025-07-07 22:58:54

-- Create ms_csms table
CREATE TABLE IF NOT EXISTS `ms_csms` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_vendor` int(11) NULL,
    `data_status` int(11) NOT NULL,
    `no` varchar(45) NULL,
    `csms_file` varchar(50) NULL,
    `score` decimal(11,2) NULL,
    `start_date` datetime NULL,
    `expiry_date` date NULL,
    `entry_stamp` datetime NULL,
    `edit_stamp` datetime NULL,
    `id_csms_limit` int(11) NULL,
    `del` int(11) NOT NULL,
    `data_last_check` datetime NULL,
    `data_checker_id` int(11) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

