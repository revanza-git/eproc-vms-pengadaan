<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fkpbj_model extends MY_Model{
	public $table = 'ms_fkpbj';
	function __construct(){
		parent::__construct();

	}
	function getFppbj($form){
		$query = "	SELECT
						a.*
						FROM ms_fppbj a WHERE a.del=0";

		$query = $this->db->query($query)->result_array();
		$data = array();
		foreach ($query as $key => $value) {
			$data[$value['id']] = $value['nama_pengadaan'];
		}
		return $data;
	}

	function getData($form=array()){
		$query = "	SELECT
						b.nama_pengadaan,
						a.desc,
						a.file,
						a.is_status,
						a.id,
						a.is_approved

						FROM ".$this->table." a

						LEFT JOIN ms_fppbj b ON b.id = a.id_fppbj

					WHERE a.del = 0";
		if($this->input->post('filter')){
			$query .= $this->filter($form, $this->input->post('filter'), true);
		}
		
		return $query;
	}

	// function selectData($id){
	// 	$query = "SELECT 
	// 					b.nama_pengadaan,
	// 					a.desc,
	// 					a.file,
	// 					a.is_status,
	// 					a.id

	// 					FROM ".$this->table." a
	// 					LEFT JOIN ms_fppbj b ON b.id = a.id_fppbj
	
	// 				WHERE a.del = 0 and a.id = ?";
	// 	$query = $this->db->query($query, array($id));
	// 	return $query->row_array();
	// }

	function selectData($id){
		$query = "SELECT 	ms_fkpbj.*, tb_division.name division
						FROM ".$this->table."
						LEFT JOIN tb_division ON tb_division.id = ms_fkpbj.id_division
						WHERE ms_fkpbj.id = ".$id."";
		$query = $this->db->query($query, array($id));
		return $query->row_array();
	}

	function selectDataFKPBJ($id){
		$query = "SELECT 	ms_fkpbj.*, tb_division.name division
						FROM ".$this->table."
						LEFT JOIN tb_division ON tb_division.id = ms_fkpbj.id_division
						WHERE ms_fkpbj.id = ".$id."";
		$query = $this->db->query($query);
		return $query;
	}

	function get_fkpbj($id){
		$query = "SELECT 	ms_fppbj.*, tb_division.name division
						FROM ".$this->table."
						LEFT JOIN tb_division ON tb_division.id = ms_fppbj.id_division
						WHERE ms_fppbj.id = ".$id."";
		$query = $this->db->query($query, array($id));
		return $query->row_array();
	}

	public function insert($id,$save){
		$update_fppbj = array(
			'pr_lampiran'			 => $save['pr_lampiran'], 	    
			'kak_lampiran' 			 => $save['kak_lampiran'], 	
			'idr_anggaran' 			 => $save['idr_anggaran'],   	
			'year_anggaran' 		 => $save['year_anggaran'],   	
			'hps' 					 => $save['hps'] ,  			
			'lingkup_kerja' 		 => $save['lingkup_kerja'] ,  	
			'penggolongan_penyedia'  => $save['penggolongan_penyedia'] ,  
			'desc_metode_pembayaran' => $save['desc_metode_pembayaran'],  
			'jenis_kontrak' 		 => $save['jenis_kontrak']   	,
			'sistem_kontrak' 		 => $save['sistem_kontrak'],   
			'is_status' 			 => $save['is_status'], 		
			'is_approved' 			 => $save['is_approved'],  	
			'edit_stamp' 			 => $save['edit_stamp'],
			'no_pr'					 => $save['no_pr'],
			'desc_dokumen'			 => $save['desc_dokumen']
		);

		$this->db->where('id',$id)->update('ms_fppbj',$update_fppbj);

		$getDpt = $this->db->where('id_fppbj',$id)->get('tr_analisa_risiko')->row_array();
		if (count($getDpt) > 0) {
			$this->db->where('id_fppbj',$id)->update('tr_analisa_risiko',array('dpt_list'=>$save['dpt'],'edit_stamp'=>date('Y-m-d H:i:s'),));
		} else {
			$this->db->insert('tr_analisa_risiko',array(
				'dpt_list'=>$save['dpt'],
				'id_fppbj'=>$id,
				'entry_stamp'=>date('Y-m-d H:i:s'),
				'del'=>0
			));
		}
		
		return $this->db->insert('ms_fkpbj',$save);
	}
}
