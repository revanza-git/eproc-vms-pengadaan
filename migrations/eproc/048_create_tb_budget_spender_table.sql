-- VMS eProc Migration
-- Database: eproc
-- Table: tb_budget_spender
-- Generated: 2025-07-07 22:58:55

-- Create tb_budget_spender table
CREATE TABLE IF NOT EXISTS `tb_budget_spender` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NULL,
    `id_copy1` timestamp NULL,
    `edit_stamp` timestamp NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

