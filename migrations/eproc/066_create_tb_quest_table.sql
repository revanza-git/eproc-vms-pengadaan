-- VMS eProc Migration
-- Database: eproc
-- Table: tb_quest
-- Generated: 2025-07-07 22:58:55

-- Create tb_quest table
CREATE TABLE IF NOT EXISTS `tb_quest` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_ms_header` int(11) NULL,
    `id_sub_header` int(11) NULL,
    `entry_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `edit_stamp` timestamp NULL,
    `id_evaluasi` int(11) NULL,
    `del` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

