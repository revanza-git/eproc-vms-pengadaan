-- VMS eProc Migration
-- Database: eproc
-- Table: ms_admin
-- Generated: 2025-07-07 22:58:54

-- Create ms_admin table
CREATE TABLE IF NOT EXISTS `ms_admin` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_role` int(11) NULL,
    `id_role_app2` int(11) NULL,
    `id_sbu` int(11) NULL,
    `name` varchar(60) NULL,
    `password` varchar(45) NULL,
    `id_division` int(11) NULL,
    `email` varchar(100) NULL,
    `photo_profile` varchar(500) NOT NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    `is_disable` tinyint(1) NULL DEFAULT '0',
    `del` tinyint(1) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

