-- VMS eProc Migration
-- Database: eproc
-- Table: ms_login
-- Generated: 2025-07-07 22:58:54

-- Create ms_login table
CREATE TABLE IF NOT EXISTS `ms_login` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_user` bigint(20) NULL,
    `type` varchar(6) NULL,
    `type_app` int(11) NOT NULL DEFAULT '0',
    `username` varchar(60) NULL,
    `password` varchar(60) NULL,
    `attempts` tinyint(4) NULL DEFAULT '0',
    `lock_time` datetime NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    `del` int(11) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

