-- VMS eProc Migration
-- Database: eproc
-- Table: tb_evaluasi_fm4
-- Generated: 2025-07-07 22:58:55

-- Create tb_evaluasi_fm4 table
CREATE TABLE IF NOT EXISTS `tb_evaluasi_fm4` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_ms_quest` int(11) NULL,
    `name` varchar(45) NULL,
    `point_a` int(11) NULL,
    `point_b` int(11) NULL,
    `point_c` int(11) NULL,
    `point_d` int(11) NULL,
    `del` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

