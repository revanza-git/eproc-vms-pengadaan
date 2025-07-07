-- VMS eProc Migration
-- Database: eproc_perencanaan
-- Table: ms_fp3
-- Generated: 2025-07-07 22:58:55

-- Create ms_fp3 table
CREATE TABLE IF NOT EXISTS `ms_fp3` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_fppbj` int(11) NOT NULL,
    `status` varchar(11) NULL,
    `perubahan` varchar(45) NOT NULL,
    `nama_pengadaan` varchar(100) NULL,
    `no_pr` varchar(255) NOT NULL,
    `metode_pengadaan` int(11) NULL,
    `jadwal_pengadaan` text NULL,
    `desc` text NULL,
    `kak_lampiran` text NULL,
    `is_status` tinytext NULL,
    `entry_stamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `edit_stamp` timestamp NULL,
    `del` tinyint(1) NOT NULL DEFAULT '0',
    `is_approved` int(11) NULL DEFAULT '0',
    `is_reject` int(11) NULL DEFAULT '0',
    `idr_anggaran` decimal(40,2) NULL,
    `id_pic` int(11) NOT NULL,
    `jwpp_start` date NULL,
    `jwpp_end` date NULL,
    `pr_lampiran` varchar(255) NULL,
    `desc_batal` text NOT NULL,
    `pejabat_pengadaan_id` int(11) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

