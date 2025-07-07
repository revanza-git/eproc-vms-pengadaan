-- VMS eProc Migration
-- Database: eproc
-- Table: ms_hse
-- Generated: 2025-07-07 22:58:54

-- Create ms_hse table
CREATE TABLE IF NOT EXISTS `ms_hse` (
    `id_hse` int(11) NOT NULL AUTO_INCREMENT,
    `id_vendor` int(11) NULL,
    `hse_file` varchar(50) NULL,
    `entry_stamp` datetime NULL,
    `edit_stamp` datetime NULL,
    PRIMARY KEY (`id_hse`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

