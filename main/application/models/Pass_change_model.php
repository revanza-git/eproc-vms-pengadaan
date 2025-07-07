<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Pass_change_model extends MY_Model{
	public $table = 'ms_user';
	function __construct(){
		parent::__construct();
	}

	function selectData($id){
		$user = $this->session->userdata('admin');
		$query = "	SELECT
						a.password
					FROM 
						".$this->table." a 
					WHERE 
						a.del = 0 AND a.id = ".$user['id_user'];

		$query = $this->db->query($query, array($id));
		return $query->row_array();

	}

	function getCurrentPassword($user_id)
	{
		$query = $this->db->where('id',$user_id)
						  ->get('ms_user');
		if ($query->num_rows() > 0) {
			return $query->row();
		}
	}

    function change_password($id,$data){
		$res = $this->db->where('id',$id)->update('ms_user',$data);
		
		return $res;
	}
	
}
