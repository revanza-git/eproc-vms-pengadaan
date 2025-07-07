-- VMS eProc Migration
-- Database: eproc
-- Table: ms_contract_proc
-- Generated: 2025-07-07 22:58:54

-- Create ms_contract_proc table
CREATE TABLE IF NOT EXISTS `ms_contract_proc` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_proc` int(11) NOT NULL,
    `no` varchar(255) NOT NULL,
    `start_date` date NULL,
    `end_date` date NOT NULL,
    `contract_file` varchar(255) NOT NULL,
    `entry_stamp` datetime NOT NULL,
    `edit_stamp` datetime NULL,
    `del` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

