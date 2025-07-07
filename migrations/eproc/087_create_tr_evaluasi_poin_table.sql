-- VMS eProc Migration
-- Database: eproc
-- Table: tr_evaluasi_poin
-- Generated: 2025-07-07 22:58:55

-- Create tr_evaluasi_poin table
CREATE TABLE IF NOT EXISTS `tr_evaluasi_poin` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_evaluasi` bigint(20) NULL,
    `id_vendor` int(11) NULL,
    `poin` int(11) NULL,
    `entry_stamp` datetime NULL,
    `edit_stamp` datetime NULL,
    `id_csms` int(11) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

