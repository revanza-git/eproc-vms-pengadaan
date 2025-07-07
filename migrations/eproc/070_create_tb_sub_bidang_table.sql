-- VMS eProc Migration
-- Database: eproc
-- Table: tb_sub_bidang
-- Generated: 2025-07-07 22:58:55

-- Create tb_sub_bidang table
CREATE TABLE IF NOT EXISTS `tb_sub_bidang` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_bidang` int(11) NULL,
    `name` varchar(350) NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    `del` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

