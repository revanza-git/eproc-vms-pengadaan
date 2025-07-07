-- VMS eProc Migration
-- Database: eproc
-- Table: ms_iu_bsb
-- Generated: 2025-07-07 22:58:54

-- Create ms_iu_bsb table
CREATE TABLE IF NOT EXISTS `ms_iu_bsb` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_vendor` int(11) NOT NULL,
    `id_ijin_usaha` int(11) NOT NULL,
    `id_bidang` int(11) NOT NULL,
    `id_sub_bidang` int(11) NOT NULL,
    `entry_stamp` datetime NOT NULL,
    `edit_stamp` datetime NULL,
    `data_status` int(11) NOT NULL,
    `data_last_check` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `data_checker_id` bigint(20) NULL,
    `del` tinyint(1) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

