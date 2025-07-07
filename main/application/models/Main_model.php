<?php

class Main_model extends CI_model{

	public $eproc_db;
	function __construct()
	{
		parent::__construct();
		$this->eproc_db = $this->load->database('eproc',true);
	}

	public function cek_login()
	{
		$username = encode_php_tags($this->input->post('username'));
		$password = $this->input->post('password');

		$sql = "SELECT * FROM ms_login WHERE username = ? AND password = ?";
		$_sql = $this->eproc_db->query($sql, array($username, $password));
		$sql = $_sql->row_array();

		$ct_sql = '';
		if($_sql->num_rows() > 0){

			if($sql['type'] == "user"){
				$ct_sql = "SELECT * FROM ms_vendor WHERE id=? AND is_active =?";
				$ct_sql = $this->eproc_db->query($ct_sql, array($sql['id_user'],1));
				if(count($ct_sql->result_array() )> 0){
					$data = $ct_sql->row_array();

				$this->session->sess_destroy();
				$set_session = array(
					'id_user' 		=> 	$data['id'],
					'name'			=>	$data['name'],
					'id_sbu'		=>	$data['id_sbu'],
					'vendor_status'	=>	$data['vendor_status'],
					'is_active'		=>	$data['is_active'],
					'app'			=>	'vms',
					'type'			=> 'user'
				);

				$this->session->set_userdata('user',$set_session);
				$this->eproc_db->where('username', $username)->update('ms_login', array(
					'attempts' => 0,
					'lock_time' => NULL
				));
				return true;
				}
				else{
					return false;
				}
			}else if($sql['type'] == "admin" AND $sql['type_app'] == 1){
				$ct_sql = "	SELECT 
								*,
								ms_admin.id id, 
								ms_admin.name name, 
								tb_role.name role_name,
								ms_admin.id_division
							FROM 
								ms_admin 
							JOIN 
								tb_role ON ms_admin.id_role = tb_role.id
							WHERE 
								ms_admin.id = ? AND ms_admin.is_disable = 0";

				$ct_sql = $this->eproc_db->query($ct_sql, array($sql['id_user']));
				
				if(count($ct_sql->result_array() )> 0){
					$data = $ct_sql->row_array();

					$this->session->sess_destroy();
					$set_session = array(
						'id_user' 		=> 	$data['id'],
						'name'			=>	$data['name'],
						'id_sbu'		=>	$data['id_sbu'],
						'id_role'		=>	$data['id_role'],
						'role_name'		=>	$data['role_name'],
						'sbu_name'		=>	$data['sbu_name'],
						'app'			=>	'vms',
						'app_type'		=>	$sql['type_app'],
						'type'			=> 'admin',
						'id_division'		=>	$sql['id_division']
					);

					$this->session->set_userdata('admin',$set_session);

					$this->eproc_db->where('username', $username)->update('ms_login', array(
						'attempts' => 0,
						'lock_time' => NULL
					));
					return true;
				}else{
					return false;
				}

			}else if ($sql['type'] == "admin" AND $sql['type_app'] == 2) {
				$ct_sql = " SELECT 
								a.name,
								a.email,
								a.id_role_app2,
								a.id_division,
								a.photo_profile,
								a.id
							FROM
								ms_admin a 
							WHERE
								 a.id = ? AND a.is_disable = 0
								";

				$ct_sql = $this->eproc_db->query($ct_sql, array($sql['id_user']));

				if(count($ct_sql->result_array())> 0){
					$data = $ct_sql->row_array();

					$this->session->sess_destroy();
					$set_session = array(
						'name'			=>	$data['name'],
						'id_user' 		=> 	$data['id'],
						'id_role'		=>	$data['id_role_app2'],
						'id_division'	=>  $data['id_division'],
						'email'			=>  $data['email'],
						'photo_profile' =>  $data['photo_profile'],
						'app_type' 		=>	$sql['type_app']
					);
					$this->session->set_userdata('admin',$set_session);
					$this->eproc_db->where('username', $username)->update('ms_login', array(
						'attempts' => 0,
						'lock_time' => NULL
					));
					return true;
				}else{
					return false;
				}
			}
		}else{
			$data = $this->eproc_db->query("SELECT * FROM ms_login WHERE username = ?", array($username))->row_array();
			$attempts = $data['attempts'];
			$attempts++;

			if ($attempts > 2) {
				$now = date('Y-m-d H:i:s');
				if ($data['type'] == 'admin') {
					$lock_time = date('Y-m-d H:i:s',strtotime($now . "-9hour + 15minute"));
				}else{
					$lock_time = date('Y-m-d H:i:s',strtotime($now . "-9hour + 5minute"));
				}
				$this->eproc_db->where('username', $username)->update('ms_login', array(
					'attempts' => 0,
					'lock_time' => $lock_time
				));
			}else{
				$this->eproc_db->set('attempts', $attempts)->where('username', $username)->update('ms_login');
				return false;
			}
		}
	}

	function getKurs(){
		$return = array();
		$query = "SELECT * FROM tb_kurs WHERE del = 0";
		$query = $this->db->query($query);
		foreach ($query->result_array() as $key => $value) {
			$return[$value['id']] = $value['symbol'];
		}
		return $return;
	}

	public function getRole(){
		$return = array();
		$query = "SELECT * FROM tb_role";
		$query = $this->db->query($query);
		foreach ($query->result_array() as $key => $value) {
			$return[$value['id']] = $value['name'];
		}
		return $return;
	}

	public function getDiv(){
		$return = array();
		$query = "SELECT * FROM tb_division WHERE del = 0";
		$query = $this->db->query($query);
		foreach ($query->result_array() as $key => $value) {
			$return[$value['id']] = $value['name'];
		}
		return $return;
	}
	
	function getProcMethod(){
		$return[''] = 'Pilih Dibawah Ini';
		$query = "SELECT * FROM tb_proc_method WHERE del = 0";
		$query = $this->db->query($query);
		foreach ($query->result_array() as $key => $value) {
			$return[$value['id']] = $value['name'];
		}
		return $return;
	}


	function getFppbj(){
		$return[''] = 'Pilih Dibawah Ini';
		$query = "SELECT * FROM ms_fppbj WHERE del = 0";
		$query = $this->db->query($query);
		foreach ($query->result_array() as $key => $value) {
			$return[$value['id']] = $value['nama_pengadaan'];
		}
		return $return;
	}

	function getDiv_($id=""){
		return  $this->db->where('id', $id)->get('tb_division')->row_array();
	}

	public function update_status($table_= "ms_fppbj", $id, $param_){
		return $this->db->where('id', $id)->update($table_, array('is_status' => $param_));
	}

	public function approve($table, $id, $param_){
		//print_r($param_);
		// update fppbj detail
		// print_r($table);die;
		
		$data = $this->db->where('id', $id)->get($table)->row_array();
		if ($data['is_status'] == 1) {
			$this->db->where('id', $data['id_fppbj'])->update('ms_fppbj', $param_);
			return $this->db->where('id', $id)->update($table, $param_);
		}
		else if ($data['is_status'] == 2) {
			$update = array(
				'id_pic' => $param_['id_pic'],
				'is_approved' => $param_['is_approved']
			);
			$this->db->where('id_fppbj', $data['id_fppbj'])->update('ms_fkpbj', $param_);
			return $this->db->where('id', $data['id_fppbj'])->update('ms_fppbj', $param_);
		} else{
			$this->db->where('id', $id)->update($table, $param_);
			return $this->db->where('id', $data['id_fppbj'])->update('ms_fppbj', $param_);
		}
	}

	public function notification($id_user='', $active = 1){
		$note = "SELECT * FROM tr_note WHERE id_user = ".$id_user." AND is_active = ".$active." AND type IS NULL ORDER BY id DESC";
		$query = $this->db->query($note);
		// foreach ($note as $key => $value) {
		// 	$data .= '<div class="notification is-warning"><p>'.$value['value'].'</p><button class="delete">X</button></div>';
		// }

		return $query;
	}

	public function get_fppbj(){
		$sql = "SELECT
						*
				  FROM
				  		ms_fppbj
				  WHERE
				  		is_status = 0 AND is_approved = 0 AND del = 0 ";

		$id_division = $this->session->userdata('admin')['id_division'];
		$id_role 	 = $this->session->userdata('admin')['id_role'];

		if ($id_division != 1) {
			$sql .= " AND id_division = ".$id_division;
		}
		/*if($id_division != 1 && $id_role == 2){
			$sql .= " AND is_approved = 2";
		}*/
		$query = $this->db->query($sql);
		return $query;
	}

	public function get_fppbj_selesai(){
		$id_division = $this->session->userdata('admin')['id_division'];
		if ($id_division != 1 && $id_division != 5) {
			$divisi = "id_division = ".$id_division." AND ";
		}else{
			$divisi = '';
		}
		$sql = "SELECT
						*
				  FROM
				  		ms_fppbj
				   WHERE 
				  		is_status = 0 AND 
				        is_reject = 0 
				        AND del = 0
				        AND is_approved_hse < 2
						AND ((".$divisi." del = 0 AND is_approved = 3 AND (idr_anggaran <= 100000000 OR (idr_anggaran > 100000000 AND metode_pengadaan = 3))))
						OR  (".$divisi." del = 0 AND is_approved = 4 AND idr_anggaran > 100000000) ";

		$query = $this->db->query($sql);
		return $query;
	}

	public function get_total_fppbj_semua(){
		$id_role = $this->session->userdata('admin')['id_role'];
		$id_division = $this->session->userdata('admin')['id_division'];
		$sql = "SELECT
						*
				  FROM
				  		ms_fppbj
				  WHERE 
				  		del = 0 AND is_status = 0 AND is_approved_hse < 2";

		if ($id_division != 1 && $id_division != 5) {
			$sql .= " AND id_division = ".$id_division;
		}
		// if ($id_role == 4 && $id_division != 5) {
		// 	$sql .= " AND is_approved <= 1";
		// } 
		/*if ($id_role == 2) {
			$sql .= " AND is_approved = 2";
		}*/
		$query = $this->db->query($sql);
		return $query;
	}

	public function total_pending_dir()
	{
		$sql = "SELECT
						*
				  FROM
				  		ms_fppbj
				  WHERE 
				  		is_status = 0 
				  		AND is_approved = 3
						AND del = 0
				        AND is_reject = 0
				        AND is_writeoff = 0
				        AND idr_anggaran > 100000000
				        AND (metode_pengadaan = 4
				        OR metode_pengadaan = 2
				        OR metode_pengadaan = 1)";
		$query = $this->db->query($sql);
		return $query;
	}

	public function get_fppbj_pending(){
		$id_role = $this->session->userdata('admin')['id_role'];
		$id_division = $this->session->userdata('admin')['id_division'];
		
		$sql = "SELECT
						*
				  FROM
				  		ms_fppbj
				  WHERE 
				  		is_status = 0 AND (is_reject = 0 OR is_reject = 1) AND del = 0 AND lampiran_persetujuan IS NULL  AND is_approved_hse <2";
		if ($id_division!= 1 && $id_division != 5) {
			$sql .= " AND id_division = ".$id_division;
		}
		if ($id_role == 4 && $id_division != 5) {
			$sql .= " AND is_approved = 0";
		} 
		$query = $this->db->query($sql);
		return $query;
	}

	function search_data($value){
		$result = array();
		$admin = $this->session->userdata('admin');		
		$query = "	SELECT
		                a.id,
		                a.nama_pengadaan,
		                b.name nama_divisi,
		                a.is_status,
		                a.is_approved
					FROM ms_fppbj a
					LEFT JOIN tb_division b ON b.id=a.id_division
					WHERE a.del = 0 AND a.nama_pengadaan LIKE ? OR b.name LIKE ?
					LIMIT 5";

	    $query = $this->db->query($query, array('%'.$_POST['search'].'%','%'.$_POST['search'].'%'))->result_array();		
		$result = array();
		foreach($query as $key => $value){
			if ($value['is_status'] == 0) {
				$class = 'fppbj';
			} elseif ($value['is_status'] == 1) {
				$class = 'fp3';
			} else {
				$class = 'fkpbj';
			}
			$result[$value['id']] = '<div class="search-result"><div class="sr-logo '.$class.'">
				<span class="icon"><i class="fas fa-file-alt"></i></span>
				</div>
				<div class="sr-item">
					<div class="sr-name"><span class="sr-no">1.</span>'.$value['nama_pengadaan'].'</div>
					<div class="sr-keterangan">
						<span class="status">Aktif</span>
						<span class="divisi"'.$value['nama_divisi'].'</span>
					</div>
					<div class="sr-icon">
						<span class="icon ar">
							<i class="fas fa-radiation"></i>
						</span>
						<span class="icon sw" style="font-size: 13px">
							<i class="fas fa-luggage-cart"></i>
						</span>
					</div>
				</div></div>';
		}
		
		return $result;
	}

	public function get_fppbj_reject(){
		$id_role = $this->session->userdata('admin')['id_role'];
		$id_division = $this->session->userdata('admin')['id_division'];
		if ($id_division!= 1 && $id_division != 5) {
			$divisi = "id_division = ".$id_division." AND ";
		} else{
			$divisi = '';
		}
		$sql = "SELECT
						*
				  FROM
				  		ms_fppbj
				  WHERE 
				  ".$divisi."
				  		is_status = 0 
						AND del = 0
				        
				        AND is_writeoff = 0
						AND 
						(is_approved = 2 AND is_reject = 1 AND idr_anggaran < 100000000)
                        OR
						(is_approved = 3 AND is_reject = 1 AND idr_anggaran > 100000000 AND (metode_pengadaan = 4 OR metode_pengadaan = 2 OR metode_pengadaan = 1))";
		
		/*if ($id_role == 2) {
			$sql .= " AND is_approved <= 2"; 
		}*/
		$query = $this->db->query($sql);
		return $query;
	}

	function rekapPerencanaanGraph($year){
		$data['total']	= count($this->db->select('id')->where('year_anggaran', $year)->where('del', 0)->where('is_reject', 0)->get('ms_fppbj')->result_array());
		$data['plan']	= count($this->db->select('id')->where('year_anggaran', $year)->where('del', 0)->where('is_status < 2')->where('is_reject', 0)->get('ms_fppbj')->result_array());
		$data['act']	= count($this->db->select('id')->where('year_anggaran', $year)->where('del', 0)->where('is_status', 2)->where('is_reject', 0)->get('ms_fppbj')->result_array());

		$data['plan']	= $data['plan'] / $data['total'] * 100;
		$data['act']	= $data['act'] / $data['total'] * 100;
		
		return json_encode($data);
	}

	public function delete($id)
	{
		return $this->db->where('id',$id)->update('tr_note',array('is_active' => 0,'edit_stamp'=>date('Y-m-d H:i:s')));
	}

	public function get_pending_kadiv()
	{
		$query = "SELECT 
						* 
					FROM 
						ms_fppbj
					WHERE
						is_status = 0 AND is_approved = 0 AND del = 0";
		return $this->db->query($query);
	}

	public function get_pending_admin_hsse()
	{
		$query = "SELECT 
						* 
					FROM 
						ms_fppbj
					WHERE
						is_status = 0 AND is_approved = 1 AND is_approved_hse = 0 AND tipe_pengadaan = 'jasa' AND del = 0 AND (is_reject = 0 OR is_reject = 1)";
		return $this->db->query($query);
	}

	public function get_pending_admin_pengendalian()
	{
		$query = "SELECT 
						* 
					FROM 
						ms_fppbj
					WHERE
						is_status = 0 AND del = 0 AND is_approved = 1 AND ((is_approved_hse = 1  AND tipe_pengadaan = 'jasa') OR (is_approved_hse = 0 AND tipe_pengadaan = 'barang')) AND (is_reject = 0 OR is_reject = 1)";
		return $this->db->query($query);
	}

	public function get_pending_kadept_proc()
	{
		$query = "SELECT 
						* 
					FROM 
						ms_fppbj
					WHERE
						is_status = 0 
					AND 
						is_writeoff = 0
			        AND 
						del = 0
			        AND 
						(is_approved = 2 AND is_reject = 0 AND idr_anggaran <= 100000000 AND (metode_pengadaan = 4))
			        OR 
						(is_approved = 2 AND is_reject = 1 AND idr_anggaran > 100000000 AND (metode_pengadaan = 4 OR metode_pengadaan = 2 OR metode_pengadaan = 1))
					OR
						(is_approved = 2 AND is_reject = 0 AND del = 0 AND idr_anggaran <= 100000000 AND (metode_pengadaan != 4))
					OR
						(is_approved = 2 AND is_reject = 1 AND del = 0 AND idr_anggaran <= 100000000 AND (metode_pengadaan != 4))";
		return $this->db->query($query);
	}
 
	public function get_pending_dirut()
	{
		$query = "SELECT 
						* 
					FROM 
						ms_fppbj
					WHERE
						is_status = 0 AND is_approved = 3 AND is_reject = 0 AND is_writeoff = 0 AND idr_anggaran >= 10000000000 AND (metode_pengadaan = 4 OR metode_pengadaan = 2 OR metode_pengadaan = 1)";
		return $this->db->query($query);
	}

	public function get_pending_dirke()
	{
		$query = "SELECT 
						* 
					FROM 
						ms_fppbj
					WHERE
						is_status = 0 AND is_approved = 3 AND is_reject = 0 AND is_writeoff = 0 AND (idr_anggaran > 1000000000 AND idr_anggaran <= 10000000000) AND (metode_pengadaan = 4 OR metode_pengadaan = 2 OR metode_pengadaan = 1)";
		return $this->db->query($query);
	}

	public function get_pending_dirsdm()
	{
		$query = "SELECT 
						* 
					FROM 
						ms_fppbj
					WHERE
						is_status = 0 AND 
				        is_approved = 3 AND 
				        is_reject = 0 AND 
				        is_writeoff = 0 AND 
				        ((idr_anggaran > 100000000 AND idr_anggaran <= 1000000000) AND 
				        (metode_pengadaan = 4 OR 
				        metode_pengadaan = 2 OR 
				        metode_pengadaan = 1)) AND del = 0";
		return $this->db->query($query);
	}

	public function get_done_dirut()
	{
		$query = "SELECT 
						* 
					FROM 
						ms_fppbj
					WHERE
						is_status = 0 AND is_approved = 4 AND is_reject = 0 AND is_writeoff = 0 AND idr_anggaran >= 10000000000 AND (metode_pengadaan = 'Penunjukan Langsung' OR metode_pengadaan = 'Pemilihan Langsung' OR metode_pengadaan = 'Pelelangan')";
		return $this->db->query($query);
	}

	public function get_done_dirke()
	{
		$query = "SELECT 
						* 
					FROM 
						ms_fppbj
					WHERE
						is_status = 0 AND is_approved = 4 AND is_reject = 0 AND is_writeoff = 0 AND (idr_anggaran > 1000000000 AND idr_anggaran <= 10000000000) AND (metode_pengadaan = 4 OR metode_pengadaan = 2 OR metode_pengadaan = 1) AND del = 0";
		return $this->db->query($query);
	}

	public function get_done_dirsdm()
	{
		$query = "SELECT 
						* 
					FROM 
						ms_fppbj
					WHERE
						is_status = 0 AND 
				        is_approved = 4 AND 
				        is_reject = 0 AND 
				        is_writeoff = 0 AND 
				        ((idr_anggaran > 100000000 AND idr_anggaran <= 1000000000) AND 
				        (metode_pengadaan = 4 OR 
				        metode_pengadaan = 2 OR 
				        metode_pengadaan = 1)) AND del = 0";
		return $this->db->query($query);
	}

	public function get_reject_dirut()
	{
		$query = "SELECT 
						* 
					FROM 
						ms_fppbj
					WHERE
						is_status = 0 AND is_approved = 3 AND is_reject = 1 AND is_writeoff = 0 AND idr_anggaran >= 10000000000 AND (metode_pengadaan = 'Penunjukan Langsung' OR metode_pengadaan = 'Pemilihan Langsung' OR metode_pengadaan = 'Pelelangan')";
		return $this->db->query($query);
	}

	public function get_reject_dirke()
	{
		$query = "SELECT 
						* 
					FROM 
						ms_fppbj
					WHERE
						is_status = 0 AND is_approved = 3 AND is_reject = 1 AND is_writeoff = 0 AND (idr_anggaran > 1000000000 AND idr_anggaran <= 10000000000) AND (metode_pengadaan = 4 OR metode_pengadaan = 2 OR metode_pengadaan = 1)";
		return $this->db->query($query);
	}

	public function get_reject_dirsdm()
	{
		$query = "SELECT 
						* 
					FROM 
						ms_fppbj
					WHERE
						is_status = 0 AND 
				        is_approved = 3 AND 
				        is_reject = 1 AND 
				        is_writeoff = 0 AND 
				        ((idr_anggaran > 100000000 AND idr_anggaran <= 1000000000) AND 
				        (metode_pengadaan = 4 OR 
				        metode_pengadaan = 2 OR 
				        metode_pengadaan = 1)) AND del = 0";
		return $this->db->query($query);
	}

	public function get_fppbj_done_kadept_otorisasi()
	{
		$sql = "SELECT
						*
				  FROM
				  		ms_fppbj
				  WHERE 
				  		is_status = 0 AND 
				        is_approved = 3 AND 
				        is_reject = 0 AND 
				        is_writeoff = 0 AND 
				        idr_anggaran < 100000000 AND 
				        (metode_pengadaan = 4)";
		$id_division = $this->session->userdata('admin')['id_division'];
		$query = $this->db->query($sql);
		return $query;
	}

	public function get_total_fppbj_directure()
	{
		$query = "SELECT 
						*
					FROM 
						ms_fppbj
					WHERE
						is_status = 0 AND is_approved = 3 AND idr_anggaran > 100000000 AND del = 0 AND (metode_pengadaan = 4 OR 
				        metode_pengadaan = 2 OR 
				        metode_pengadaan = 1) AND is_approved_hse < 2";
		return $this->db->query($query);
	}

	public function get_total_fppbj_dirke()
	{
		$query = "SELECT 
						* 
					FROM 
						ms_fppbj
					WHERE
						is_status = 0 AND is_approved = 3 AND is_writeoff = 0 AND (idr_anggaran > 1000000000 AND idr_anggaran <= 10000000000) AND (metode_pengadaan = 4 OR metode_pengadaan = 2 OR metode_pengadaan = 1) AND del = 0";
		return $this->db->query($query);
	}

	public function get_total_fppbj_dirut()
	{
		$query = "SELECT 
						* 
					FROM 
						ms_fppbj
					WHERE
						is_status = 0 AND is_approved = 3 AND is_writeoff = 0 AND idr_anggaran >= 10000000000 AND (metode_pengadaan = 4 OR metode_pengadaan = 2 OR metode_pengadaan = 1) AND del = 0";
		return $this->db->query($query);
	}

	public function get_total_fppbj_dirsdm()
	{
		$query = "SELECT 
						* 
					FROM 
						ms_fppbj
					WHERE
						is_status = 0 AND 
				        is_approved = 3 AND 
				        is_writeoff = 0 AND 
				        ((idr_anggaran > 100000000 AND idr_anggaran <= 1000000000) AND 
				        (metode_pengadaan = 4 OR 
				        metode_pengadaan = 2 OR 
				        metode_pengadaan = 1)) AND del = 0";
		return $this->db->query($query);
	}

	// function get_dpt_fkpbj(){

	// 	$sql = "SELECT 
	// 					name ,
	// 					id
	// 			 FROM 
	// 			 		ms_vendor 
	// 			 WHERE 
	// 			 		del = 0 AND name
	// 			 LIKE ? ";
	// 	$query = $this->db->query($sql,array('%'.$_POST['search'].'%',))->result_array();
	// 	$data = array();
	// 	foreach($query as $key => $value){
	//         $data[$value['id']] = $value['name'];
	//     }
	// 	// print_r($query);die;
	//     return $query;
	// }

	// function check($data = array()){

	// 	$username = $this->input->post('username');
	// 	$_password = $this->input->post('password');
	// 	// $password = do_hash($this->input->post('password'),'sha1');


	// 	$sql = "SELECT 
	// 				ms_user.*, 
	// 				tb_division.name as division
	// 			FROM 
	// 				ms_user 
	// 			LEFT JOIN 
	// 				tb_division ON tb_division.id = ms_user.id_division 
	// 			WHERE 
	// 				ms_user.username = ? AND ms_user.password = ? ";
	// 	$sql = $this->db->query($sql, array($username, $_password, 2));

	// 	$sql_result = $sql->row_array();
	// 	// print_r($sql);die;
	// 	if($sql->num_rows() > 0){

	// 		$set_session = array(
	// 			'name'			=>	$sql_result['name'],
	// 			'division'		=>	$sql_result['division'],
	// 			'id_user' 		=> 	$sql_result['id_user'],
	// 			'id_role'		=>	$sql_result['id_role'],
	// 			'id_division'	=>  $sql_result['id_division'],
	// 			'email'			=>  $sql_result['email'],
	// 			'photo_profile' =>  $sql_result['photo_profile']
	// 		);

	// 		$this->session->set_userdata('admin', $set_session);

	// 		return true;

	// 	}else{

	// 		return false;

	// 	}
	// }
}