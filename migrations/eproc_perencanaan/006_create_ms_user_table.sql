-- VMS eProc Migration
-- Database: eproc_perencanaan
-- Table: ms_user
-- Generated: 2025-07-07 22:58:55

-- Create ms_user table
CREATE TABLE IF NOT EXISTS `ms_user` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_division` int(11) NULL,
    `id_role` int(11) NULL,
    `name` varchar(100) NULL,
    `email` varchar(255) NULL,
    `username` varchar(60) NULL,
    `password` varchar(100) NULL,
    `raw_password` varchar(100) NULL,
    `photo_profile` varchar(255) NULL DEFAULT 'Man-Avatar.png',
    `entry_stamp` datetime NULL,
    `edit_stamp` datetime NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

