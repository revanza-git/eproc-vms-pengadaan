-- VMS eProc Migration
-- Database: eproc
-- Table: ms_procurement_peserta
-- Generated: 2025-07-07 22:58:55

-- Create ms_procurement_peserta table
CREATE TABLE IF NOT EXISTS `ms_procurement_peserta` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_vendor` bigint(20) NULL,
    `id_proc` bigint(20) NULL,
    `surat` varchar(10) NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    `is_winner` int(11) NULL DEFAULT '0',
    `is_final_winner` int(11) NULL,
    `id_surat` bigint(20) NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    `id_kurs` int(11) NULL,
    `idr_value` bigint(20) NULL,
    `kurs_value` decimal(20,2) NULL,
    `nilai_evaluasi` varchar(50) NULL,
    `kurs_kontrak` int(11) NULL,
    `idr_kontrak` bigint(20) NULL,
    `id_kurs_kontrak` int(11) NULL,
    `remark` text NULL,
    `negosiasi` text NULL,
    `fee` decimal(20,2) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

