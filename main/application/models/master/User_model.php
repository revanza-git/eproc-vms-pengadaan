<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends MY_Model{
	public $table = 'ms_user';
	function __construct(){
		parent::__construct();

	}
	function getData($form, $id=null){
		if($id==null){
			$user = $this->session->userdata('user');
		}else{
			$user['id_user'] = $id;
		}
		
		$query = "	SELECT  a.name,
							a.username,
							a.raw_password,
							b.name role_name,
							c.name division,
							a.id
					FROM ".$this->table." a 
					JOIN tb_role b ON a.id_role = b.id
					LEFT JOIN tb_division c ON a.id_division = c.id  
					WHERE a.del = 0";
		if($this->input->post('filter')){
			$query .= $this->filter($form, $this->input->post('filter'), false);
		}
		
		return $query;
	}

	function selectData($id){
		$query = "SELECT 	name,
							username,
							-- raw_password,
							id_role,
							id_division,
							email
							FROM ".$this->table." WHERE id = ?";
		$query = $this->db->query($query, array($id));
		return $query->row_array();
	}
	function getRoleOption(){
		$query = "	SELECT id, name 
					FROM tb_role";
		$query = $this->db->query($query);

		$return = array();
		foreach($query->result_array() as $key => $row){
			$return[$row['id']] = $row['name'];
		}
		return $return;
	}


	public function insert_admin($data){
		$this->db->insert('ms_admin',$data);
		return $this->db->insert_id();
	}
	public function insert_data_admin($table, $data){
		return $this->db->insert($table,$data);	
	}
	public function update_admin($id, $data){
		$this->db->where('id', $id)->update('ms_admin',$data);

	}
	public function update_data_admin($id, $data){
		return $this->db->where('id_user', $id)->where('type', 'admin')->update('ms_login',$data);	
	}
}