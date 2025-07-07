-- VMS eProc Migration
-- Database: eproc
-- Table: tb_bidang
-- Generated: 2025-07-07 22:58:55

-- Create tb_bidang table
CREATE TABLE IF NOT EXISTS `tb_bidang` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_dpt_type` int(11) NULL,
    `name` varchar(200) NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    `del` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

