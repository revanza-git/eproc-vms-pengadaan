-- VMS eProc Migration
-- Database: eproc
-- Table: ms_key_value
-- Generated: 2025-07-07 22:58:54

-- Create ms_key_value table
CREATE TABLE IF NOT EXISTS `ms_key_value` (
    `key` varchar(255) NULL,
    `value` text NULL,
    `deleted_at` datetime NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

