<?php

/**
 * 
 */
class Input_email extends CI_Controller
{
	public $eproc_db;
	public $default_db;
	function __construct()
	{
		parent::__construct();
		$this->eproc_db = $this->load->database('eproc',true);
		$this->default_db = $this->load->database('default',true);
	}

	public function index()
	{
		$query = " SELECT 
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
						b.type = 'admin' AND (a.id_role IS NOT NULL OR a.id_role_app2 IS NOT NULL) -- AND DATE(a.entry_stamp) = ?
					ORDER BY
						a.id desc ";
		$query = $this->eproc_db->query($query)->result_array();

		$tb = '<table border=1>
			<thead>
				<tr>
					<th>Nama</th>
					<th>Email</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>';

		foreach ($query as $key => $value) {
			$tb .= '<tr>
				<td>'.$value['name'].'</td>
				<td>'.$value['email'].'</td>
				<td>
				<a href="'.site_url('Input_email/edit/'.$value['id']).'"> Edit</a> ||
				<a href="'.site_url('Input_email/delete/'.$value['id']).'"> Hapus</a> 
				</td>
			</tr>';
		}

		$tb .= '</tbody>
		</table>';

		echo $tb;
	}

	public function edit($id)
	{
		$data = $this->eproc_db->where('id',$id)->get('ms_admin')->row_array();

		$f = '<!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<title>EDIT</title>
			<link rel="stylesheet" href="">
		</head>
		<body>
			<form action="'.site_url('input_email/update/'.$id).'" method="post" accept-charset="utf-8">
				Email : <input type="email" name="email" value="'.$data['email'].'"> <br>
				<button> Update</button>
			</form>
		</body>
		</html>';

		echo $f;
	}

	public function update($id)
	{
		// echo $this->input->post('email')." - ".$id;die;
		$arr = array(
			'email' => $this->input->post('email'),
			'del'	=> 0
		);

		$this->eproc_db->where('id',$id)->update('ms_admin',$arr);

		redirect('input_email');
	}

	public function delete($id)
	{
		$arr = array('del' => 1);

		$this->eproc_db->where('id',$id)->update('ms_admin',$arr);

		redirect('input_email');
	}

	public function update_tr_email_blast()
	{
		$data = $this->default_db->where('del',0)->get('ms_fppbj')->result_array();

		foreach ($data as $key => $value) {
			$this->edit_tr_email_blast($value['id'],$value['jwpp_start'],$value['metode_pengadaan']);
		}

		echo "Sukses";
	}

	public function auto_done_fp3()
	{
		$arr = array(
			'edit_stamp'	=>	date('Y-m-d H:i:s'),
			'is_approved'	=>	3
		);

		$data = $this->default_db->where('del',0)->where('is_status',1)->get('ms_fppbj')->result_array();

		foreach ($data as $key => $value) {
			$this->default_db->where('id',$value['id'])->update('ms_fppbj',$arr);
			$this->default_db->where('id_fppbj',$value['id'])->update('ms_fp3',$arr);
		}

		echo "Sukses!";
	}

	public function auto_done_fkpbj()
	{
		$arr = array(
			'edit_stamp'	=>	date('Y-m-d H:i:s'),
			'is_approved'	=>	3
		);

		$data = $this->default_db->where('del',0)->where('is_status',2)->get('ms_fppbj')->result_array();

		foreach ($data as $key => $value) {
			$this->default_db->where('id',$value['id'])->update('ms_fppbj',$arr);
			$this->default_db->where('id_fppbj',$value['id'])->update('ms_fkpbj',$arr);
		}

		echo "Sukses!";
	}

	public function edit_tr_email_blast($id,$jwpp_start,$metode)
	{
			$metode_day	= 0;

			$get_metode = $this->default_db->where('id',$metode)->get('tb_proc_method')->row_array();

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
			
			$prevDate 		= date('Y-m-d', strtotime($yellow__.'-7 days'));

			$this->edit_date_periode($id,$prevDate,$yellow__,1);
			$this->edit_date_periode($id,$yellow__,$yellow___,2);		
	}

	public function edit_date_periode($id,$begin,$end,$type)
	{
		$begin = new DateTime($begin);
		$end = new DateTime($end);

		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($begin, $interval, $end);
		
		$this->default_db->where('id_pengadaan',$id)->where('type',$type)->delete('tr_email_blast');
		
		foreach ($period as $dt) {
			// echo $dt->format("Y-m-d").'<br>';die;
			$data = array(
				'id_pengadaan'	=> $id,
				'date_alert'	=> $dt->format("Y-m-d"),
				'type'			=> $type
			);
			$this->default_db->insert('tr_email_blast',$data);
		}
	}
}