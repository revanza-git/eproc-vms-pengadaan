-- VMS eProc Migration
-- Database: eproc
-- Table: tr_material_price
-- Generated: 2025-07-07 22:58:55

-- Create tr_material_price table
CREATE TABLE IF NOT EXISTS `tr_material_price` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_material` int(11) NULL,
    `id_procurement` int(11) NULL,
    `id_vendor` int(11) NULL,
    `price` bigint(20) NULL,
    `date` date NULL,
    `entry_stamp` datetime NULL,
    `edit_stamp` datetime NULL,
    `del` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

