<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pemaketan_model extends MY_Model{
	public $fppbj = 'ms_fppbj';
	function __construct(){
		parent::__construct();

	}
	
	function getData($form=array()){
		$admin = $this->session->userdata('admin');
		if ($admin['id_role'] != in_array(7,8,9)) {
			$get = "WHERE ms_fppbj.del = 0";
		}if ($admin['id_role'] == 7) {
				$get = 'WHERE ms_fppbj.is_status = 0 AND 
				        ms_fppbj.is_approved = 3 AND 
				        ms_fppbj.is_reject = 0 AND 
				        ms_fppbj.is_writeoff = 0 AND 
				        ((ms_fppbj.idr_anggaran > 100000000 AND ms_fppbj.idr_anggaran <= 1000000000) AND 
				        (ms_fppbj.metode_pengadaan = 4 OR 
				        ms_fppbj.metode_pengadaan = 2 OR 
				        ms_fppbj.metode_pengadaan = 1)) AND ms_fppbj.del = 0';
			} if ($admin['id_role'] == 8) {
				$get = 'WHERE ms_fppbj.is_status = 0 AND ms_fppbj.is_approved = 3 AND ms_fppbj.is_reject = 0 AND ms_fppbj.is_writeoff = 0 AND (ms_fppbj.idr_anggaran > 1000000000 AND ms_fppbj.idr_anggaran <= 10000000000) AND (ms_fppbj.metode_pengadaan = 4 OR ms_fppbj.metode_pengadaan = 2 OR ms_fppbj.metode_pengadaan = 1)';
			} if ($admin['id_role'] == 9) {
				$get = 'WHERE ms_fppbj.is_status = 0 AND ms_fppbj.is_approved = 3 AND ms_fppbj.is_reject = 0 AND ms_fppbj.is_writeoff = 0 AND ms_fppbj.idr_anggaran >= 10000000000 AND (ms_fppbj.metode_pengadaan = 4 OR ms_fppbj.metode_pengadaan = 2 OR ms_fppbj.metode_pengadaan = 1)';
			}
		$query = "	SELECT  name,
							count(*) AS total,
							ms_fppbj.id,
							tb_division.id id_division
					FROM ".$this->fppbj."
					JOIN tb_division ON ms_fppbj.id_division = tb_division.id 
					 ".$get."";
		if($this->input->post('filter')){
			$query .= $this->filter($form, $this->input->post('filter'), false);
		}
		if($this->session->userdata('admin')['id_role']==3){
			// $query .= ' AND is_approved >= 1 ';
		}else if($this->session->userdata('admin')['id_role']==2){
			// $query .= 'AND is_approved >= 2';
		}else if($this->session->userdata('admin')['id_role']==4){
			// $query .= ' AND is_approved >= 0 ';
		}
		//echo $query;die;
		$query .= " GROUP BY id_division ";
		return $query;
	}

	function selectData($id){
		$query = "SELECT 	
						a.*,
						a.sistem_kontrak,
						b.name nama_pic
				  FROM ".$this->fppbj." a 
				  LEFT JOIN 
				  	   	ms_user b ON b.id=a.id_pic
				  WHERE a.id = ?";
		$query = $this->db->query($query, array($id));
		return $query->row_array();
	}

	public function get_multi_years($id)
	{
		return $this->db->select('*')->where('id_fppbj',$id)->get('tr_price')->result_array();
	}

	public function get_id_analisa_risiko($id)
	{
		return $this->db->select('*')->where('id_fppbj',$id)->get('tr_analisa_risiko')->row_array();
	}

	function getDataDivision($form=array(), $id_division="",$id_fppbj=""){
		$admin = $this->session->userdata('admin');
		if ($admin['id_role'] != in_array(7,8,9)) {
			$where = "ms_fppbj.id_division = ".$id_division."
					AND	ms_fppbj.del=0 ";
		}if ($admin['id_role'] == 7) {
				$where = 'ms_fppbj.is_status = 0 AND 
				        ms_fppbj.is_approved = 3 AND 
				        ms_fppbj.is_reject = 0 AND 
				        ms_fppbj.is_writeoff = 0 AND 
				        ((ms_fppbj.idr_anggaran > 100000000 AND ms_fppbj.idr_anggaran <= 1000000000) AND 
				        (ms_fppbj.metode_pengadaan = 4 OR 
				        ms_fppbj.metode_pengadaan = 2 OR 
				        ms_fppbj.metode_pengadaan = 1)) AND ms_fppbj.del = 0';
			} if ($admin['id_role'] == 8) {
				$where = 'ms_fppbj.is_status = 0 AND ms_fppbj.is_approved = 3 AND ms_fppbj.is_reject = 0 AND ms_fppbj.is_writeoff = 0 AND (ms_fppbj.idr_anggaran > 1000000000 AND ms_fppbj.idr_anggaran <= 10000000000) AND (ms_fppbj.metode_pengadaan = 4 OR ms_fppbj.metode_pengadaan = 2 OR ms_fppbj.metode_pengadaan = 1)';
			} if ($admin['id_role'] == 9) {
				$where = 'ms_fppbj.is_status = 0 AND ms_fppbj.is_approved = 3 AND ms_fppbj.is_reject = 0 AND ms_fppbj.is_writeoff = 0 AND ms_fppbj.idr_anggaran >= 10000000000 AND (ms_fppbj.metode_pengadaan = 4 OR ms_fppbj.metode_pengadaan = 2 OR ms_fppbj.metode_pengadaan = 1)';
			} 
		if ($id_fppbj == '') {
			$id_fppbj = '';
		} else {
			$id_fppbj = 'AND ms_fppbj.id = '.$id_fppbj; 
		}
		$query = "	SELECT  nama_pengadaan,
							tb_proc_method.name metode,
							year_anggaran,
							ms_fppbj.is_status,
							ms_fppbj.is_approved,
							ms_fppbj.lampiran_persetujuan,
							ms_fppbj.id_perencanaan_umum,
							ms_fppbj.is_reject,
							ms_fppbj.is_writeoff,
							ms_fppbj.id,
							ms_fppbj.tipe_pengadaan,
							ms_fppbj.metode_pengadaan,
							ms_fppbj.is_approved_hse,
							tr_note.value,
							ms_fppbj.is_planning,
							ms_fppbj.jenis_pengadaan,
							ms_fppbj.idr_anggaran,
							tr_note.type,
							ms_fppbj.del
					FROM ".$this->fppbj."
					LEFT JOIN tb_proc_method ON ms_fppbj.metode_pengadaan = tb_proc_method.id
					LEFT JOIN tr_note ON tr_note.id_fppbj=ms_fppbj.id AND tr_note.type = 'reject'
					WHERE id_division =".$id_division." ".$id_fppbj." AND ".$where;
		// echo $query;die;
		if($this->input->post('filter')){
			$query .= $this->filter($form, $this->input->post('filter'), false);
		}

		$barang = 'barang';
		if($this->session->userdata('admin')['id_role']==3){
			// $query .= 'AND is_approved >= 1';
		}else if($this->session->userdata('admin')['id_role']==2){
			// $query .= 'AND ms_fppbj.tipe_pengadaan != "'.$barang.'"';
			// $query .= 'AND is_approved >= 2';
		}else if($this->session->userdata('admin')['id_role']==4){
			// $query .= 'AND is_approved >= 0';
		}

		if($this->session->userdata('admin')['id_division'] == 5 && $id_division != 5){
			$query .= " AND ms_fppbj.tipe_pengadaan = 'jasa' AND is_approved = 1";
		}

		$query .= " GROUP BY ms_fppbj.id ";
		// print_r($query);
		return $query;
	}

	public function getProc($id){
		$this->db->select('ms_fppbj.id, year_anggaran, tb_proc_method.name metode_pengadaan, ms_fppbj.nama_pengadaan, ms_fppbj.jenis_pengadaan,ms_fppbj.penggolongan_penyedia, ms_fppbj.csms,ms_fppbj.jwpp_start,ms_fppbj.jwpp_end, ms_fppbj.jwp_start,ms_fppbj.jwp_end, ms_fppbj.hps, ms_fppbj.desc_metode_pembayaran, ms_fppbj.jenis_kontrak, ms_fppbj.desc');
		$this->db->where('(ms_fppbj.del IS NULL OR ms_fppbj.del = 0)');
		$this->db->where('ms_fppbj.id', $id);
		$this->db->join('tb_proc_method', 'tb_proc_method.id = metode_pengadaan', 'LEFT');
		$query= $this->db->get('ms_fppbj');
		
		$data = $query->row_array();

		$data['fkpbj_detail'] 	= $this->db->select('ms_fkpbj.id id_fkpbj, file, desc, is_status, ms_fkpbj.entry_stamp,ms_user.name user')->where('ms_fkpbj.del', 0)->where('id_fppbj', $data['id'])->join('ms_user','ms_user.id=ms_fkpbj.id_pic','LEFT')->group_by('ms_fkpbj.id_fppbj')->get('ms_fkpbj')->result_array();
		$data['fp3_detail'] 	= $this->db->select('ms_fp3.id id_fp3, status, nama_pengadaan, tb_proc_method.name metode_pengadaan, jadwal_pengadaan, ms_fp3.desc, ms_fp3.is_status, ms_fp3.entry_stamp')->where('ms_fp3.del', 0)->where('id_fppbj', $data['id'])->join('tb_proc_method', 'tb_proc_method.id = ms_fp3.metode_pengadaan')->get('ms_fp3')->result_array();

		foreach ($data['fkpbj_detail'] as $key => $value)
			$data['fkpbj_detail'][$key]['type']	= "fkpbj";
		
		
		foreach ($data['fp3_detail'] as $key => $value)
			$data['fp3_detail'][$key]['type']	= "fp3";
			

		$data['detail']	= array_merge ($data['fkpbj_detail'], $data['fp3_detail']);

		
		// print_r($data);
		return $data;

	}

	public function get_status($id){
		$query = "	SELECT  nama_pengadaan,
							tb_proc_method.name metode,
							year_anggaran,
							ms_fppbj.is_status,
							ms_fppbj.is_approved,
							ms_fppbj.id_perencanaan_umum,
							ms_fppbj.id,
							ms_fppbj.lampiran_persetujuan
					FROM ".$this->fppbj."
					LEFT JOIN tb_proc_method ON ms_fppbj.metode_pengadaan = tb_proc_method.id
					WHERE 	ms_fppbj.id = ".$id."
					AND		ms_fppbj.del=0 ";

		if($this->input->post('filter')){
			$query .= $this->filter($form, $this->input->post('filter'), true);
		}

		if($this->session->userdata('admin')['id_role']==3){
			$query .= 'AND is_approved >= 1';
		}else if($this->session->userdata('admin')['id_role']==2){
			$query .= 'AND is_approved >= 2';
		}

		// print_r($query);
		return $this->db->query($query)->row_array();
	}





	/*============================================================================

									REKAP SECTION

	==============================================================================*/

	function getDataYear($form = "", $year = null){

		if($year > 0){
			//echo "Kondisi 1";
			$query = "	SELECT  tb_division.name AS division,
								a.nama_pengadaan AS name,
								a.year_anggaran AS year,
								a.id
						FROM ".$this->fppbj." a
						LEFT JOIN tb_division ON tb_division.id = a.id_division
						WHERE  a.is_status = 0 AND
						a.is_reject = 0 
				        AND a.del = 0 
				        AND a.is_approved_hse < 2
						AND ((a.year_anggaran = ".$year."
						AND a.is_approved = 3 AND (a.idr_anggaran <= 100000000 OR (a.idr_anggaran > 100000000 AND a.metode_pengadaan = 3))))
						OR  (a.year_anggaran = ".$year."
						AND a.is_approved = 4 AND a.idr_anggaran > 100000000)";
			$query .= " GROUP BY a.id";
			//echo $query;
		}else{
			//echo "Kondisi 2";
			$query = "	SELECT  nama_pengadaan AS name,
								count(*) AS total,
								year_anggaran AS year,
								ms_fppbj.id
						FROM ".$this->fppbj."
						WHERE 	is_reject = 0 
				        AND del = 0 
				        AND is_approved_hse < 2
						AND ((is_approved = 3 AND (idr_anggaran <= 100000000 OR (idr_anggaran > 100000000 AND metode_pengadaan = 3))))
						OR  (is_approved = 4 AND idr_anggaran > 100000000)";
			$query .= " GROUP BY year";
		}
		// print_r($query);

		if($this->input->post('filter')){
			$query .= $this->filter($form, $this->input->post('filter'), true);
		}
		return $query;
	}

	function approve($data){
		// $data['tipe_pengadaan'] = $data['pengadaan'];
		$data['del'] = 0;
		unset($data['pengadaan']);
		
		$a 		=  $this->db->insert("ms_perencanaan_umum", $data);
		$a_id 	= $this->db->insert_id();

		$this->db->where('year_anggaran', $data['year'])->update('ms_fppbj', array('id_perencanaan_umum' => $a_id));

		return $a;
	}

	public function get_data_step($id)
	{
		$sql = "SELECT 
					a.*
				FROM 
					ms_fppbj a 
				WHERE a.id = ".$id;
		$query = $this->db->query($sql)->row_array();

		// $query['query'] = "SELECT 
		// 				a.* 
		// 			FROM 
		// 				tr_analisa_risiko_detail a 
		// 			INNER JOIN
		// 				tr_analisa_risiko b ON a.id=b.id_analisa_risiko
		// 			WHERE b.id_fppbj = ".$query['id'];
		// print_r($query);
		return $query;
	}

	public function get_data_analisa($id)
	{
		$sql1 = "SELECT a.* FROM ms_fppbj a WHERE a.id = ".$id;
		$query1 = $this->db->query($sql1)->row_array();
		$sql = "SELECT 
						a.* 
					FROM 
						tr_analisa_risiko_detail a 
					INNER JOIN
						tr_analisa_risiko b ON b.id=a.id_analisa_risiko
					WHERE b.id_fppbj = ".$query1['id'];
		$sql .= " GROUP BY a.id ASC";
		$query = $this->db->query($sql)->result_array();
		return $query;
	}

	public function upload_lampiran_persetujuan($id, $save)
	{
		return $this->db->where('id',$id)->update('ms_fppbj',$save);
	}

	public function get_pic($metode){
		// if ($metode == 3) {
		// 	$query = "SELECT id, name FROM ms_user WHERE id_division = 1";
		// } else {
			$query = "SELECT id, name FROM ms_user WHERE id_role = 6";
		// }
		
		$query = $this->db->query($query);
		// $data = array();
		// foreach ($query as $key => $value) {
		// 	$data[$value['id']] = $value['name'];
		// }
		// print_r($data);
		return $query;
	}

	public function get_swakelola($id){
		$query = $this->db->where('id_fppbj',$id)->get('tr_analisa_swakelola');
		return $query->row_array();
	}

	public function update($id, $data){
		
		foreach ($data as $key => $value) {
			foreach ($value as $keys => $values) {
					if($values=='') unset($value[$keys]);
				}
			if(is_array($value)){
				$data[$key] = implode(',',$value);
			}
		}
		$a = $this->db->
				where('id', $id)->
				update('ms_fppbj', $data);
				return $a;
	}

	public function check_perencanaan_umum($year){
		$check =$this->db->where('year', $year)->get('ms_perencanaan_umum')->result_array();
		$check = count($check);

		return $check;
	}
}