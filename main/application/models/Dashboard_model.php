<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard_model extends MY_Model{
	
	function __construct(){
		parent::__construct();
	}
	function total_rencana_baseline($form){
		$admin = $this->session->userdata('admin');
		$query = "SELECT COUNT(*) ct FROM ms_baseline WHERE del = 0 AND status = 1";
		if($admin['id_role']==2){
			$query .= " AND id_pengguna = ".$admin['id_division'];
		}
		$query = $this->db->query($query)->row_array();
		return $query['ct'];
	}
	function total_realisasi_baseline($form){
		$admin = $this->session->userdata('admin');
		$query = "SELECT COUNT(*) ct FROM ms_procurement a JOIN ms_baseline b ON a.id_baseline = b.id WHERE a.del = 0";
		if($admin['id_role']==2){
			$query .= " AND a.id_pengguna = ".$admin['id_division'];
		}
		$query = $this->db->query($query)->row_array();
		return $query['ct'];
	}
	function total_realisasi_non_baseline($form){
		$admin = $this->session->userdata('admin');
		$query = "SELECT COUNT(*) ct FROM ms_procurement a WHERE a.id_baseline IS NULL AND a.del = 0";
		if($admin['id_role']==2){
			$query .= " AND a.id_pengguna = ".$admin['id_division'];
		}
		$query = $this->db->query($query)->row_array();
		return $query['ct'];
	}
	function total_nilai_baseline($form){
		$admin = $this->session->userdata('admin');
		$query = "SELECT (SUM(idr_budget_investasi) + SUM(idr_budget_operasi)) ct FROM ms_baseline a WHERE del = 0 AND status = 1";
		if($admin['id_role']==2){
			$query .= " AND a.id_pengguna = ".$admin['id_division'];
		}
		$query = $this->db->query($query)->row_array();
		
		return $query['ct'];
	}

	function total_nilai_terkontrak_baseline($form){
		$admin = $this->session->userdata('admin');
		$query = "SELECT SUM(c.contract_price) ct FROM ms_procurement a JOIN ms_baseline b ON a.id_baseline = b.id JOIN ms_contract c ON a.id = c.id_procurement WHERE a.del = 0";
		if($admin['id_role']==2){
			$query .= " AND a.id_pengguna = ".$admin['id_division'];
		}
		$query = $this->db->query($query)->row_array();
		
		return $query['ct'];
	}

	function total_nilai_terkontrak_non_baseline($form){
		$admin = $this->session->userdata('admin');
		$ct = 0;
		$query = "SELECT SUM(c.contract_price) ct FROM ms_procurement a JOIN ms_contract c ON a.id = c.id_procurement WHERE a.del = 0 AND a.id_baseline IS NULL";
		if($admin['id_role']==2){
			$query .= " AND a.id_pengguna = ".$admin['id_division'];
		}
		$query = $this->db->query($query)->row_array();
		if($query['ct']!=null){
			$ct = $query['ct'];
		}
		
		return $ct;
	}
	function total_nilai_terbayar_baseline($form){
		$admin = $this->session->userdata('admin');
		$ct = 0;
		$query = "SELECT SUM(a.value) ct FROM ms_invoice a JOIN ms_procurement b ON a.id_procurement = b.id  WHERE a.del = 0 AND id_baseline IS NOT NULL";
		if($admin['id_role']==2){
			$query .= " AND a.id_pengguna = ".$admin['id_division'];
		}
		$query = $this->db->query($query)->row_array();
		if($query['ct']!=null){
			$ct = $query['ct'];
		}
		
		return $ct;
	}
	function total_nilai_terbayar_non_baseline($form){
		$admin = $this->session->userdata('admin');
		$ct = 0;
		$query = "SELECT SUM(a.value) ct FROM ms_invoice a JOIN ms_procurement b ON a.id_procurement = b.id  WHERE a.del = 0 AND id_baseline IS NULL";
		if($admin['id_role']==2){
			$query .= " AND a.id_pengguna = ".$admin['id_division'];
		}
		$query = $this->db->query($query)->row_array();
		if($query['ct']!=null){
			$ct = $query['ct'];
		}
		
		return $ct;
	}
}
