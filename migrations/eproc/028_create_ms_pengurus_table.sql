-- VMS eProc Migration
-- Database: eproc
-- Table: ms_pengurus
-- Generated: 2025-07-07 22:58:54

-- Create ms_pengurus table
CREATE TABLE IF NOT EXISTS `ms_pengurus` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_vendor` bigint(20) NULL,
    `id_akta` bigint(20) NULL,
    `no` varchar(60) NULL,
    `name` varchar(100) NULL,
    `position` varchar(60) NULL,
    `position_expire` date NULL,
    `pengurus_file` varchar(45) NULL,
    `expire_date` varchar(11) NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    `data_status` int(11) NULL DEFAULT '0',
    `data_last_check` timestamp NULL,
    `data_checker_id` bigint(20) NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

