<?php
/**
 * 
 */
class Input extends CI_Controller
{
	public $db_perencanaan;
	public $db_server_eproc;
	public $db_server_eproc_gabungan;
	
	function __construct()
	{
		parent::__construct();
		$this->db_perencanaan 			= $this->load->database('default',true);
		$this->db_server_eproc 			= $this->load->database('server_eproc',true);
		//$this->db_server_eproc_gabungan = $this->load->database('server_eproc_gabungan',true);
	}

	public function index()
	{
		$data = array(
			'table' 	=> $this->getData(),
			'role_1'	=> $this->role_1(),
			'role_2'	=> $this->role_2(),
			'division'	=> $this->division()
		);
		$this->load->view('input',$data);
	}

	public function edit_data($id)
	{
		
	}

	public function getAdmin()
	{
		$query = "	SELECT 
						a.name,
						b.username,
						b.password
					FROM 
						ms_admin a
					JOIN 
						ms_login b ON b.id_user=a.id AND b.type='admin'
		";
		$data = $this->db->query($query)->result_array();

		$a = "<table>
		<tr>
			<td>Nama</td>
			<td>Username</td>
			<td>Password</td>
		</tr>";

		foreach ($data as $key => $value) {
			$a .= '<tr>
				<td>'.$value['name'].'</td>
				<td>'.$value['username'].'</td>
				<td>'.$value['password'].'</td>
			</tr>';
		}

		$a .= '</table>';

		echo $a;
	}

	public function getData()
	{
		$query = "	SELECT 
						a.name,
						a.email,
						a.id_role,
						a.id_role_app2,
						b.username,
						b.password,
						b.type_app,
						a.id
					FROM
						ms_admin a
					JOIN
						ms_login b ON b.id_user=a.id
					WHERE
						b.type = 'admin' AND a.del = 0 AND (a.id_role IS NOT NULL OR a.id_role_app2 IS NOT NULL OR a.id_role_app2 != 0) -- AND DATE(a.entry_stamp) = ?
					ORDER BY
						a.id desc";
		return $this->db->query($query,array(date('Y-m-d')))->result_array();
	}

	public function role_1()
	{
		return $this->db->get('tb_role')->result_array();
	}

	public function role_2()
	{
		return $this->db_perencanaan->get('tb_role')->result_array();
	}

	public function division()
	{
		return $this->db_perencanaan->where('del',0)->get('tb_division')->result_array();
	}

	public function save()
	{
		$save = $this->input->post();
		// print_r($save);die;

		$arr__ = array(
			'name'		=> $save['name'],
			'id_role'		=> $save['id_role'],
			'id_role_app2'	=> $save['id_role_app2'],
			'id_sbu'		=> 1,
			'id_division'	=> $save['id_division'],
			'email'		=> $save['email'],
			'entry_stamp'	=> date('Y-m-d H:i:s'),
			'del'			=> 0
		);

		$id_user 			= $this->getIdUsers($arr__);
		$arr__['del'] 	= 1;
		$id_user_server_eproc	= $this->getIdUsersServerEproc($arr__);
//		$id_user_server_eproc_gabungan = $this->getIdUsersServerEprocGabungan($arr__);

		$arr = array(
			//'id_user'		=> $id_user,
			'type_app'		=> $save['id_app'],
			'type'		=> 'admin',
			'username'		=> $save['username'],
			'password'		=> '123',
			'entry_stamp'	=>date('Y-m-d H:i:s'),
			//'del'			=> 1
		);
		
		$arr['id_user'] 	= $id_user;
		$this->insertLogin($arr);
		//$this->insertLoginServerEproc($arr);

		$arr['id_user'] = $id_user_server_eproc;
		$this->insertLoginServerEproc($arr);

		redirect('input');
	}

	public function getIdUsers($data)
	{
		$this->db->insert('ms_admin',$data);
		return $this->db->insert_id();
	}

	public function getIdUsersServerEproc($data)
	{
//print_r($data);die;
		$this->db_server_eproc->insert('ms_admin',$data);
		return $this->db_server_eproc->insert_id();
	}

	public function getIdUsersServerEprocGabungan($data)
	{
		$this->db_server_eproc_gabungan->insert('ms_admin',$data);
		return $this->db_server_eproc_gabungan->insert_id();
	}

	public function insertLogin($data)
	{
		return $this->db->insert('ms_login',$data);
	}

	public function insertLoginServerEproc($data)
	{
//print_r($data);die;
		return $this->db_server_eproc->insert('ms_login',$data);
	}

	public function insertLoginServerEprocGabungan($data)
	{
		return $this->db_server_eproc_gabungan->insert('ms_login',$data);
	}

	public function show_lampiran()
	{
		$query = " SELECT * FROM ms_fppbj WHERE del = 0 AND entry_stamp LIKE '%2020%' AND ((pr_lampiran != null OR pr_lampiran != '') OR (kak_lampiran != '' OR kak_lampiran != null)) ";

		$data = $this->db_perencanaan->query($query)->result_array();

		foreach ($data as $key => $value) {
			$this->db_perencanaan->where('id_fppbj',$value['id'])->update('ms_fkpbj',array(
				'pr_lampiran'	=>	$value['pr_lampiran'],
				'kak_lampiran'	=>	$value['kak_lampiran']
			));
		}

		$a = '<table border=1>
			<tr>
				<td>ID Pengadaan</td>
				<td>Nama Pengadaan</td>
				<td>Lampiran PR</td>
				<td>KAK Lampiran</td>
			</tr>';

		foreach ($data as $key => $value) {
			$a .= '<tr>
				<td>'.$value['id'].'</td>
				<td><a href="'.site_url('pemaketan/division/'.$value['id_division'].'/'.$value['id']).'">'.$value['nama_pengadaan'].'</a></td>
				<td>'.$value['pr_lampiran'].'</td>
				<td>'.$value['kak_lampiran'].'</td>
			</tr>';
		}

		$a .='</table>';

		echo $a;
	}
}