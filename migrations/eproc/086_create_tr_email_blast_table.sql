-- VMS eProc Migration
-- Database: eproc
-- Table: tr_email_blast
-- Generated: 2025-07-07 22:58:55

-- Create tr_email_blast table
CREATE TABLE IF NOT EXISTS `tr_email_blast` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_doc` bigint(20) NULL,
    `doc_type` varchar(30) NULL,
    `distance` int(11) NULL,
    `date` date NULL,
    `message` longtext NULL,
    `no` varchar(100) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

