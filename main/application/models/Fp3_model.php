<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fp3_model extends MY_Model{
	public $table = 'ms_fp3';
	function __construct(){
		parent::__construct();
	}
		function getFppbj($form){
		$admin = $this->session->userdata('admin');
		$query = "	SELECT
						a.*
						FROM ms_fppbj a WHERE a.del = 0 AND a.is_writeoff = 0 AND is_status < 2 AND id_division = ?";

		$query = $this->db->query($query,array($admin['id_division']))->result_array();
		$data = array();
		foreach ($query as $key => $value) {
			$data[$value['id']] = $value['nama_pengadaan'];
		}
		return $data;
	}

	function getData($form=array()){
		$query = "	SELECT
						a.nama_pengadaan,
						b.nama_pengadaan nama_lama,
						a.metode_pengadaan,
						a.jadwal_pengadaan,
						a.desc,
						a.is_status,
						a.id,
						a.status,
						b.id id_fppbj,
						a.lampiran_persetujuan,
						a.is_approved,
						a.is_reject,
						c.value,
						a.entry_stamp

						FROM ms_fp3 a

						LEFT JOIN ms_fppbj b ON b.id = a.id_fppbj
						LEFT JOIN tr_note c ON b.id = c.id_fppbj
					WHERE a.del = 0";
		$query .= " GROUP BY a.id ";
		if($this->input->post('filter')){
			$query .= $this->filter($form, $this->input->post('filter'), false);
		}
		return $query;
	}

	function selectData($id){
		$query = "SELECT 
						a.nama_pengadaan,
						b.nama_pengadaan nama_lama,
						a.metode_pengadaan,
						a.jadwal_pengadaan,
						a.desc,
						a.is_status,
						a.id,
						a.status,
						b.nama_pengadaan nama_lama,
						a.lampiran_persetujuan,
						a.is_approved,
						a.is_reject,
						a.idr_anggaran,
						c.value,
						a.id_fppbj,
						a.jwpp_start,
						a.jwpp_end

						FROM ms_fp3 a

						LEFT JOIN ms_fppbj b ON b.id = a.id_fppbj
						LEFT JOIN tr_note c ON b.id = c.id_fppbj
	
					WHERE a.id = ?";
		$query = $this->db->query($query, array($id));
		return $query->row_array();
	}
		function updateStatus($id,$status = ''){
		$query	= "UPDATE
						`ms_fp3` 
						SET
						`status` = ".$status."
						WHERE `ms_fp3`.`id`=?";
		$query = $this->db->query($query, array($id));
		return $query;
	}

	public function get_data_fppbj($id)
	{
		$query = "SELECT * FROM ms_fppbj WHERE id = ".$id;
		return $this->db->query($query)->row_array();
	}
	
	public function edit_to_fp3($data){
		$id_fppbj = $data['id_fppbj'];
		if ($data['nama_pengadaan'] !== '' || $data['metode_pengadaan'] !== '' || $data['jadwal_pengadaan'] !== '') {
			$data_fppbj = array(
				// 'nama_pengadaan'   => $data['nama_pengadaan'],
				// 'metode_pengadaan' => $data['metode_pengadaan'],
				// 'desc' 			   => $data['desc'],
				'edit_stamp' 	   => date('Y-m-d H:i:s'),
				'is_status' 	   => 1,
				'is_approved' 	   => 0
			);
			$this->db->where('id',$id_fppbj)->update('ms_fppbj',$data_fppbj);
			return $this->db->insert('ms_fp3',$data);
		}else{
			$get_data = "SELECT * FROM ms_fppbj WHERE id = ".$data['id_fppbj'];
			$get_fppbj = $this->db->query($get_data)->row_array();
			$this->db->where('id',$data['id_fppbj'])->update('ms_fppbj',array('is_writeoff'=>0));
			$data_fp3 = array(
				'id_fppbj'=>$data['id_fppbj'],
				'status'=>'hapus',
				'nama_pengadaan'=>$data['nama_pengadaan'],
				'metode_pengadaan'=>$data['metode_pengadaan'],
				'jwpp_start'=>$data['jwpp_start'],
				'jwpp_end'=>$data['jwpp_end'],
				'desc'=>$data['desc'],
				'idr_anggaran'=>$data['idr_anggaran'],
				'is_status' => 0,
				'is_approved' => 0,
				'lampiran_persetujuan' =>$data['lampiran_persetujuan'],
			);
			$this->insert_tr_email_blast($get_fppbj['id'],$get_fppbj['jwpp_start'],$get_fppbj['metode_pengadaan']);
			return $this->db->insert('ms_fp3',$data_fp3);
		}
	}

	public function insert_tr_email_blast($id,$jwpp_start,$metode)
	{
			$metode_day	= 0;

			$get_metode = $this->db->where('id',$metode)->get('tb_proc_method')->row_array();

			$metode = trim($get_metode['name']);
			if ($metode == "Pelelangan") {
				$metode_day = 60; //60 hari
			}else if ($metode == "Pengadaan Langsung") {
				$metode_day = 10;// 10 hari
			}else if ($metode == "Pemilihan Langsung"){
				$metode_day = 45; //45 hari
			}else if ($metode == "Swakelola"){
				$metode_day = 0;
			}else if ($metode == "Penunjukan Langsung") {
				$metode_day = 20;// 20 hari
			}else{
				// $metode_day = 1;
			}
			$yellow = $jwpp_start;
	        // echo $value['metode_pengadaan'].'<br>';
	        $start_yellow 	= $metode_day+14;
	        $end_yellow 	= $metode_day+1;
			$yellow__ 		= date('Y-m-d', strtotime($yellow.'-'.$start_yellow.' days'));
			$yellow___ 		= date('Y-m-d', strtotime($yellow.'-'.$end_yellow.' days'));
			
			$prevDate 		= date('Y-m-d', strtotime($yellow__.'-14 days'));

			$this->date_periode($id,$prevDate,$yellow__,1);
			$this->date_periode($id,$yellow__,$yellow___,2);
		
	}

	public function date_periode($id,$begin,$end,$type)
	{
		$begin = new DateTime($begin);
		$end = new DateTime($end);

		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($begin, $interval, $end);
		
		foreach ($period as $dt) {
			// echo $dt->format("Y-m-d").'<br>';die;
			$data = array(
				'id_pengadaan'	=> $id,
				'date_alert'	=> $dt->format("Y-m-d"),
				'type'			=> $type
			);
			$this->db->where('id_pengadaan',$id)->update('tr_email_blast',$data);
		}
	}
}
