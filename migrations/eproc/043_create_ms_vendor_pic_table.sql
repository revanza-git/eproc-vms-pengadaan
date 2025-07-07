-- VMS eProc Migration
-- Database: eproc
-- Table: ms_vendor_pic
-- Generated: 2025-07-07 22:58:55

-- Create ms_vendor_pic table
CREATE TABLE IF NOT EXISTS `ms_vendor_pic` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_vendor` bigint(20) NULL,
    `pic_name` varchar(100) NULL,
    `pic_position` varchar(100) NULL,
    `pic_phone` varchar(50) NULL,
    `pic_email` varchar(50) NULL,
    `pic_address` text NULL,
    `admin_name` varchar(255) NOT NULL,
    `admin_position` varchar(255) NOT NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

