-- VMS eProc Migration
-- Database: eproc
-- Table: tb_sub_quest_fm4
-- Generated: 2025-07-07 22:58:55

-- Create tb_sub_quest_fm4 table
CREATE TABLE IF NOT EXISTS `tb_sub_quest_fm4` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_header` int(11) NULL,
    `question` text NULL,
    `id_order` int(11) NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

