-- VMS eProc Migration
-- Database: eproc
-- Table: tr_certificate
-- Generated: 2025-07-07 22:58:55

-- Create tr_certificate table
CREATE TABLE IF NOT EXISTS `tr_certificate` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_vendor` int(11) NULL,
    `certificate_no` text NULL,
    `dpt_date` datetime NULL,
    `is_active` int(11) NULL,
    `entry_stamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `edit_stamp` timestamp NULL,
    `del` int(11) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

