-- VMS eProc Migration
-- Database: eproc_perencanaan
-- Table: tb_proc_method
-- Generated: 2025-07-07 22:58:55

-- Create tb_proc_method table
CREATE TABLE IF NOT EXISTS `tb_proc_method` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(75) NULL,
    `entry_stamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `edit_stamp` timestamp NULL,
    `del` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

