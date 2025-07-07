-- VMS eProc - Complete Database Migration Script
-- Run this script to create all tables for both databases
-- Usage: mysql -h localhost -P 3307 -u root -p < run_all_migrations.sql

-- =====================================================
-- Create databases if they don't exist
-- =====================================================
CREATE DATABASE IF NOT EXISTS `eproc`;
CREATE DATABASE IF NOT EXISTS `eproc_perencanaan`;

-- =====================================================
-- EPROC DATABASE MIGRATIONS (93 tables)
-- =====================================================
USE `eproc`;

-- Master tables
SOURCE eproc/001_create_migrations_table.sql;
SOURCE eproc/002_create_ms_admin_table.sql;
SOURCE eproc/003_create_ms_agen_table.sql;
SOURCE eproc/004_create_ms_agen_bsb_table.sql;
SOURCE eproc/005_create_ms_agen_produk_table.sql;
SOURCE eproc/006_create_ms_akta_table.sql;
SOURCE eproc/007_create_ms_amandemen_table.sql;
SOURCE eproc/008_create_ms_answer_hse_table.sql;
SOURCE eproc/009_create_ms_answer_hse_fm4_table.sql;
SOURCE eproc/010_create_ms_ass_table.sql;
SOURCE eproc/011_create_ms_ass_group_table.sql;
SOURCE eproc/012_create_ms_bast_table.sql;
SOURCE eproc/013_create_ms_contract_table.sql;
SOURCE eproc/014_create_ms_contract_proc_table.sql;
SOURCE eproc/015_create_ms_csms_table.sql;
SOURCE eproc/016_create_ms_csms_fm4_table.sql;
SOURCE eproc/017_create_ms_evaluasi_data_table.sql;
SOURCE eproc/018_create_ms_hse_table.sql;
SOURCE eproc/019_create_ms_ijin_usaha_table.sql;
SOURCE eproc/020_create_ms_iu_bsb_table.sql;
SOURCE eproc/021_create_ms_iu_bsb__table.sql;
SOURCE eproc/022_create_ms_key_value_table.sql;
SOURCE eproc/023_create_ms_login_table.sql;
SOURCE eproc/024_create_ms_material_table.sql;
SOURCE eproc/025_create_ms_pemilik_table.sql;
SOURCE eproc/026_create_ms_penawaran_table.sql;
SOURCE eproc/027_create_ms_pengalaman_table.sql;
SOURCE eproc/028_create_ms_pengurus_table.sql;
SOURCE eproc/029_create_ms_procurement_table.sql;
SOURCE eproc/030_create_ms_procurement_barang_table.sql;
SOURCE eproc/031_create_ms_procurement_bsb_table.sql;
SOURCE eproc/032_create_ms_procurement_kurs_table.sql;
SOURCE eproc/033_create_ms_procurement_negosiasi_table.sql;
SOURCE eproc/034_create_ms_procurement_persyaratan_table.sql;
SOURCE eproc/035_create_ms_procurement_peserta_table.sql;
SOURCE eproc/036_create_ms_procurement_tatacara_table.sql;
SOURCE eproc/037_create_ms_score_k3_table.sql;
SOURCE eproc/038_create_ms_situ_table.sql;
SOURCE eproc/039_create_ms_spk_table.sql;
SOURCE eproc/040_create_ms_tdp_table.sql;
SOURCE eproc/041_create_ms_vendor_table.sql;
SOURCE eproc/042_create_ms_vendor_admistrasi_table.sql;
SOURCE eproc/043_create_ms_vendor_pic_table.sql;

-- Configuration tables
SOURCE eproc/044_create_tb_bast_print_table.sql;
SOURCE eproc/045_create_tb_bidang_table.sql;
SOURCE eproc/046_create_tb_blacklist_limit_table.sql;
SOURCE eproc/047_create_tb_budget_holder_table.sql;
SOURCE eproc/048_create_tb_budget_spender_table.sql;
SOURCE eproc/049_create_tb_city_table.sql;
SOURCE eproc/050_create_tb_country_table.sql;
SOURCE eproc/051_create_tb_csms_limit_table.sql;
SOURCE eproc/052_create_tb_csms_limit_fm4_table.sql;
SOURCE eproc/053_create_tb_division_table.sql;
SOURCE eproc/054_create_tb_dpt_type_table.sql;
SOURCE eproc/055_create_tb_evaluasi_table.sql;
SOURCE eproc/056_create_tb_evaluasi_fm4_table.sql;
SOURCE eproc/057_create_tb_kurs_table.sql;
SOURCE eproc/058_create_tb_legal_table.sql;
SOURCE eproc/059_create_tb_mekanisme_table.sql;
SOURCE eproc/060_create_tb_ms_quest_fm4_table.sql;
SOURCE eproc/061_create_tb_ms_quest_k3_table.sql;
SOURCE eproc/062_create_tb_pejabat_pengadaan_table.sql;
SOURCE eproc/063_create_tb_pernyataan_table.sql;
SOURCE eproc/064_create_tb_progress_pengadaan_table.sql;
SOURCE eproc/065_create_tb_province_table.sql;
SOURCE eproc/066_create_tb_quest_table.sql;
SOURCE eproc/067_create_tb_quest_fm4_table.sql;
SOURCE eproc/068_create_tb_role_table.sql;
SOURCE eproc/069_create_tb_sbu_table.sql;
SOURCE eproc/070_create_tb_sub_bidang_table.sql;
SOURCE eproc/071_create_tb_sub_quest_fm4_table.sql;
SOURCE eproc/072_create_tb_sub_quest_k3_table.sql;

-- Transaction tables
SOURCE eproc/073_create_tr_answer_hse_table.sql;
SOURCE eproc/074_create_tr_answer_hse_fm4_table.sql;
SOURCE eproc/075_create_tr_ass_point_table.sql;
SOURCE eproc/076_create_tr_ass_result_table.sql;
SOURCE eproc/077_create_tr_assessment_table.sql;
SOURCE eproc/078_create_tr_assessment_point_table.sql;
SOURCE eproc/079_create_tr_bast_table.sql;
SOURCE eproc/080_create_tr_blacklist_table.sql;
SOURCE eproc/081_create_tr_blacklist_remark_table.sql;
SOURCE eproc/082_create_tr_certificate_table.sql;
SOURCE eproc/083_create_tr_denda_table.sql;
SOURCE eproc/084_create_tr_dpt_table.sql;
SOURCE eproc/085_create_tr_dpt_2_table.sql;
SOURCE eproc/086_create_tr_email_blast_table.sql;
SOURCE eproc/087_create_tr_evaluasi_poin_table.sql;
SOURCE eproc/088_create_tr_feedback_table.sql;
SOURCE eproc/089_create_tr_material_price_table.sql;
SOURCE eproc/090_create_tr_note_table.sql;
SOURCE eproc/091_create_tr_progress_kontrak_table.sql;
SOURCE eproc/092_create_tr_progress_pengadaan_table.sql;
SOURCE eproc/093_create_tr_surat_ubo_table.sql;

-- =====================================================
-- EPROC_PERENCANAAN DATABASE MIGRATIONS (26 tables)
-- =====================================================
USE `eproc_perencanaan`;

-- Planning master tables
SOURCE eproc_perencanaan/001_create_ms_fkpbj_table.sql;
SOURCE eproc_perencanaan/002_create_ms_fp3_table.sql;
SOURCE eproc_perencanaan/003_create_ms_fppbj_table.sql;
SOURCE eproc_perencanaan/004_create_ms_perencanaan_umum_table.sql;
SOURCE eproc_perencanaan/005_create_ms_score_k3_table.sql;
SOURCE eproc_perencanaan/006_create_ms_user_table.sql;
SOURCE eproc_perencanaan/007_create_ms_vendor_table.sql;

-- Configuration tables
SOURCE eproc_perencanaan/008_create_tb_comitment_desc_table.sql;
SOURCE eproc_perencanaan/009_create_tb_csms_limit_table.sql;
SOURCE eproc_perencanaan/010_create_tb_division_table.sql;
SOURCE eproc_perencanaan/011_create_tb_in_rate_table.sql;
SOURCE eproc_perencanaan/012_create_tb_kadiv_table.sql;
SOURCE eproc_perencanaan/013_create_tb_kurs_table.sql;
SOURCE eproc_perencanaan/014_create_tb_proc_method_table.sql;
SOURCE eproc_perencanaan/015_create_tb_role_table.sql;

-- Transaction tables
SOURCE eproc_perencanaan/016_create_tr_analisa_resiko_table.sql;
SOURCE eproc_perencanaan/017_create_tr_analisa_risiko_table.sql;
SOURCE eproc_perencanaan/018_create_tr_analisa_risiko_detail_table.sql;
SOURCE eproc_perencanaan/019_create_tr_analisa_swakelola_table.sql;
SOURCE eproc_perencanaan/020_create_tr_email_blast_table.sql;
SOURCE eproc_perencanaan/021_create_tr_history_analisa_resiko_table.sql;
SOURCE eproc_perencanaan/022_create_tr_history_pengadaan_table.sql;
SOURCE eproc_perencanaan/023_create_tr_history_swakelola_table.sql;
SOURCE eproc_perencanaan/024_create_tr_log_activity_table.sql;
SOURCE eproc_perencanaan/025_create_tr_note_table.sql;
SOURCE eproc_perencanaan/026_create_tr_price_table.sql;

-- =====================================================
-- Migration Complete
-- =====================================================
SELECT 'VMS eProc database migration completed successfully!' AS status; 