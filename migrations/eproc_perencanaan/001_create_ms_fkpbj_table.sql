-- VMS eProc Migration
-- Database: eproc_perencanaan
-- Table: ms_fkpbj
-- Generated: 2025-07-07 22:58:55

-- Create ms_fkpbj table
CREATE TABLE IF NOT EXISTS `ms_fkpbj` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_fppbj` int(11) NULL,
    `id_pic` int(11) NULL,
    `id_division` int(11) NULL,
    `is_status` tinyint(4) NULL DEFAULT '0',
    `is_approved` tinyint(1) NULL,
    `is_reject` int(11) NOT NULL,
    `idr_anggaran` decimal(40,2) NULL,
    `no_pr` varchar(100) NULL,
    `tipe_pr` varchar(50) NULL,
    `pr_lampiran` varchar(255) NULL,
    `nama_pengadaan` varchar(100) NULL,
    `usd_anggaran` decimal(40,2) NULL,
    `year_anggaran` year(4) NULL,
    `desc_pengadaan` text NULL,
    `hps` tinyint(1) NULL,
    `kak_lampiran` text NULL,
    `desc_dokumen` text NULL,
    `jenis_pengadaan` varchar(45) NULL,
    `metode_pengadaan` varchar(45) NULL,
    `penggolongan_penyedia` varchar(45) NULL,
    `jwpp` text NULL,
    `jwp` text NULL,
    `penerimaan_pekerjaan` timestamp NULL,
    `desc_metode_pembayaran` text NULL,
    `jenis_kontrak` varchar(70) NULL,
    `penggolongan_CSMS` varchar(45) NULL,
    `file` text NULL,
    `desc` text NULL,
    `entry_stamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `edit_stamp` timestamp NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    `sistem_kontrak` varchar(50) NULL,
    `pengadaan` varchar(100) NULL,
    `lingkup_kerja` text NULL,
    `dpt` varchar(200) NULL,
    `jwpp_start` date NULL,
    `jwpp_end` date NULL,
    `jwp_start` date NULL,
    `jwp_end` date NULL,
    `pejabat_pengadaan_id` int(11) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

