-- VMS eProc Migration
-- Database: eproc
-- Table: tb_pejabat_pengadaan
-- Generated: 2025-07-07 22:58:55

-- Create tb_pejabat_pengadaan table
CREATE TABLE IF NOT EXISTS `tb_pejabat_pengadaan` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

