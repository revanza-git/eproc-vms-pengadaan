-- VMS eProc Migration
-- Database: eproc
-- Table: ms_procurement_negosiasi
-- Generated: 2025-07-07 22:58:55

-- Create ms_procurement_negosiasi table
CREATE TABLE IF NOT EXISTS `ms_procurement_negosiasi` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_proc` int(11) NULL,
    `id_vendor` int(11) NULL,
    `value` decimal(25,2) NULL,
    `remark` text NULL,
    `number` int(11) NULL,
    `fee` decimal(15,2) NULL,
    `del` tinyint(1) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

