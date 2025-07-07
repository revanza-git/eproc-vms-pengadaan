-- VMS eProc Migration
-- Database: eproc
-- Table: tb_legal
-- Generated: 2025-07-07 22:58:55

-- Create tb_legal table
CREATE TABLE IF NOT EXISTS `tb_legal` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(15) NULL,
    `index` tinyint(4) NULL,
    `entry_stamp` timestamp NULL,
    `edit_stamp` timestamp NULL,
    `del` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

