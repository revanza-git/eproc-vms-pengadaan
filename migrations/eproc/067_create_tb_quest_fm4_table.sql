-- VMS eProc Migration
-- Database: eproc
-- Table: tb_quest_fm4
-- Generated: 2025-07-07 22:58:55

-- Create tb_quest_fm4 table
CREATE TABLE IF NOT EXISTS `tb_quest_fm4` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_ms_header` int(11) NULL,
    `id_sub_header` int(11) NULL,
    `entry_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `edit_stamp` timestamp NULL,
    `id_evaluasi` int(11) NULL,
    `del` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

