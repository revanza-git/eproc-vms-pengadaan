-- VMS eProc Migration
-- Database: eproc
-- Table: tb_sub_quest_k3
-- Generated: 2025-07-07 22:58:55

-- Create tb_sub_quest_k3 table
CREATE TABLE IF NOT EXISTS `tb_sub_quest_k3` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_header` int(11) NULL,
    `question` varchar(150) NULL,
    `id_order` int(11) NULL,
    `entry_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `edit_stamp` timestamp NULL,
    `del` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

