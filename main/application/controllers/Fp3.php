<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fp3 extends MY_Controller {

	public $form;
	public $modelAlias 	= 'fp3';
	public $alias 		= 'ms_fp3';
	public $module 		= 'kurs';

	public function __construct(){
		parent::__construct();		
		$this->load->model('Fp3_model','fp3');
		$this->load->model('Fppbj_model','fm');
		$this->load->model('Main_model','mm');
		$this->load->library('pdf');
		include_once APPPATH.'third_party/dompdf2/dompdf_config.inc.php';


		$this->form = array(
				'form' => array(
					array(
						'field'	=> 	'status',
						'type'	=>	'fp3',
						'label'	=>	'FP3',
					),
					array(
						'field'	=> 	'id_fppbj',
						'type'	=>	'dropdown',
						'label'	=>	'Nama Pengadaan (Lamaaaa)',
						'source'=>  $this->fp3->getFppbj(),
					),
					array(
						'field'	=> 	'nama_pengadaan',
						'type'	=>	'text',
						'label'	=>	'Nama Pengadaan (Baru)',
					),array(
						'field'	=> 	'metode_pengadaan_lama',
						'type'	=>	'text',
						'label'	=>	'Metode Pengadaan (Lama)'
					),array(
						'field'	=> 	'metode_pengadaan',
						'type'	=>	'dropdown',
						'label'	=>	'Metode Pengadaan (Baru)',
						'source'=>	$this->mm->getProcMethod(),
					),
					// array(
					// 	'field'	=> 	'idr_anggaran_lama',
					// 	'type'	=>	'currency',
					// 	'label'	=>	'Anggaran (Lama)',
					// ),
					// array(
					// 	'field'	=> 	'idr_anggaran',
					// 	'type'	=>	'currency',
					// 	'label'	=>	'Anggaran (Baru)',
					// ),
					array(
						'field'	=> 	array('jwpp_start_lama','jwpp_end_lama'),
						'type'	=>	'date_range',
						'label'	=>	'Jadwal Pengadaan (Lama)',
					),
					array(
						'field'	=> 	array('jwpp_start','jwpp_end'),
						'type'	=>	'date_range',
						'label'	=>	'Jadwal Pengadaan (Baru)',
					),array(
						'field'	=> 	'desc_lama',
						'type'	=>	'textarea',
						'label'	=>	'Keterangan (Lama)',
					),array(
						'field'	=> 	'desc',
						'type'	=>	'textarea',
						'label'	=>	'Keterangan (Baru)',
					),array(
						'field' => 'lampiran_persetujuan_lama',
						'type'  => 'file',
						'label' => 'Lampiran Persetujuan (Lama)',
						'upload_path'=> base_url('assets/lampiran/lampiran_persetujuan/'),
						'upload_url'=> site_url('fp3/upload_lampiran'),
						'allowed_types'=> '*'
					),array(
						'field' => 'lampiran_persetujuan',
						'type'  => 'file',
						'label' => 'Lampiran Persetujuan (Baru)',
						'upload_path'=> base_url('assets/lampiran/lampiran_persetujuan/'),
						'upload_url'=> site_url('fp3/upload_lampiran'),
						'allowed_types'=> '*'
					),
				),

				'successAlert'=>'Berhasil mengubah data!',
				'filter'=>array(
					array(
						'field'	=> 	'a|status',
						'type'	=>	'text',
						'label'	=>	'Status'
					),array(
						'field'	=> 	'a|id_fppbj',
						'type'	=>	'dropdown',
						'label'	=>	'Nama Pengadaan B/J',
						'source'=>  $this->fp3->getFppbj(),
						'rules' => 	'required',
					),array(
						'field'	=> 	'a|nama_pengadaan',
						'type'	=>	'text',
						'label'	=>	'Nama Pengadaan',
						'rules' => 	'required',
					),array(
						'field'	=> 	'a|metode_pengadaan',
						'type'	=>	'text',
						'label'	=>	'Metode Pengadaan',
						'rules' => 	'required',
					),array(
						'field'	=> 	'a|jadwal_pengadaan',
						'type'	=>	'dateTime',
						'label'	=>	'Jadwal Pengadaan',
						'rules' => 	'required',
					)
					
				)
			);
		$this->insertUrl = site_url('fp3/save/');
		$this->updateUrl = 'fp3/update/';
		$this->deleteUrl = 'fp3/delete/';
		$this->approveURL = 'fp3/approve/';
		$this->getData = $this->fp3->getData($this->form);
		$this->form_validation->set_rules($this->form['form']);
	}

	public function insert()
	{
		$this->form = $this->form; 
		foreach($this->form['form'] as $key => $element) {
			if($this->form['form'][$key]['type']=='date_range'){
				$_value = array();
				
				foreach ($this->form['form'][$key]['field'] as $keys => $values) {
					$_value[] = $data[$values];
					
				}
				$this->form['form'][$key]['value'] = $_value;
			}
			if ($this->form['form'][$key]['field'] == array('jwpp_start_lama','jwpp_end_lama')) {
				$this->form['form'][$key]['readonly'] = true;
			}
			if ($this->form['form'][$key]['field'] == 'metode_pengadaan_lama') {
				$this->form['form'][$key]['readonly'] = true;
			}
			if ($this->form['form'][$key]['field'] == 'idr_anggaran_lama') {
				$this->form['form'][$key]['readonly'] = true;
			}
			if ($this->form['form'][$key]['field'] == 'desc_lama') {
				$this->form['form'][$key]['readonly'] = true;
			}
			if ($this->form['form'][$key]['field'] == 'lampiran_persetujuan_lama') {
				$this->form['form'][$key]['readonly'] = true;
			}
		}

		$this->form['url'] = $this->insertUrl;
		$this->form['button'] = array(
			array(
				'type' => 'submit',
				'label' => 'Simpan',
			) ,
			array(
				'type' => 'cancel',
				'label' => 'Batal'
			)
		);
		echo json_encode($this->form);
	}

	public function get_data_fppbj($id)
	{
		echo json_encode($this->fp3->get_data_fppbj($id));
	}

	public function approve($id, $param_){
		$id_pic = $this->input->post()['id_pic'];
		$table = "ms_fp3";
		$fp3 = $this->fp3->selectData($id);
		$fppbj = $this->fm->selectData($fp3['id_fppbj']);
		
		$param_ = array('is_status' => 1, 'is_approved' => $param_);
		$data 	= $this->mm->approve($table, $id, $param_);

		$division = $this->get_email_division($this->session->userdata('admin')['id_division']);

		$to = '';
		foreach ($division as $key => $value) {
			$to .= ' '.$value['email'];
		}
		
		$subject = 'FP3 telah disetujui';
		$message = $fppbj['nama_pengadaan'].'telah di approve oleh '.$this->session->userdata('admin')['name'];
		$this->send_mail($to, $subject, $message, $link);

		redirect($_SERVER['HTTP_REFERER']);
		return $data;
	}
	
	public function getSingleData($id=null){
		// define status parameter
		$this->form = array(
				'form' => array(
					array(
						'field'	=> 	'status',
						'type'	=>	'fp3',
						'label'	=>	'FP3',
					),array(
						'field'	=> 	'nama_lama',
						'type'	=>	'text',
						'label'	=>	'Nama Pengadaan',
						'rules' => 	'',
					),array(
						'field'	=> 	'nama_pengadaan',
						'type'	=>	'text',
						'label'	=>	'Nama Pengadaan (Baru)',
					),array(
						'field'	=> 	'metode_pengadaan',
						'type'	=>	'dropdown',
						'label'	=>	'Metode Pengadaan (Baru)',
						'source'=>	$this->mm->getProcMethod(),
					),array(
						'field'	=> 	'idr_anggaran',
						'type'	=>	'currency',
						'label'	=>	'Anggaran',
					),array(
						'field'	=> 	array('jwpp_start','jwpp_end'),
						'type'	=>	'date_range',
						'label'	=>	'Jadwal Pengadaan (Baru)',
					),array(
						'field'	=> 	'desc',
						'type'	=>	'textarea',
						'label'	=>	'Keterangan',
					),array(
						'field' => 'lampiran_persetujuan',
						'type'  => 'file',
						'label' => 'Lampiran Persetujuan',
						'upload_path'=> base_url('assets/lampiran/fp3/'),
						'upload_url'=> site_url('upload_lampiran_persetujuan/upload_lampiran'),
						'allowed_types'=> '*',
						'rules' => 'required' ,
					),
				)
			);
		$admin = $this->session->userdata('admin');
		
		$dataFP3 = $this->fp3->selectData($id);
		$param_  = ($admin['id_role'] == 4) ? ($param_=1) : (($admin['id_role'] == 3) ? ($param_=2) : (($admin['id_role'] == 2) ? ($param_=3) : ''));
		$this->form['url'] 		= site_url($this->approveURL.$id.'/'.$param_);
		$this->form['reject'] 	= site_url('fp3/reject/'.$id.'/'.$param_);
		if ($admin['id_role'] == 2 || $admin['id_role'] == 3 || $admin['id_role'] == 4) {
			$btn_setuju = array(
				array(
						'type' 	=> 'export',
						'link'	=> $this->form['url'],
						'label' => '<i style="line-height:25px;" class="fas fa-thumbs-up"></i>&nbsp;Setujui Data'
					)
			);
			$btn_reject = array(
				array(
						'type' 	=> 'reject',
						'label' => '<i style="line-height:25px;" class="fas fa-thumbs-down"></i>&nbsp;Tolak Data'
					)
			);
			$btn_cancel = array(
				array(
							'type' => 'cancel',
							'label' => 'Tutup'
						)
			);
			if ($dataFP3['is_approved'] == 0 && $dataFP3['is_reject'] == 0 && $admin['id_role'] == 4) {
				$this->form['button'] = array_merge($btn_setuju,$btn_reject,$btn_cancel);
			} else if($dataFP3['is_approved'] == 1 && $dataFP3['is_reject'] == 0 && $admin['id_role'] == 3){
				$this->form['button'] = array_merge($btn_setuju,$btn_reject,$btn_cancel);
			} else if($dataFP3['is_approved'] == 2 && $dataFP3['is_reject'] == 0 && $admin['id_role'] == 2){
				$this->form['button'] = array_merge($btn_setuju,$btn_reject,$btn_cancel);
			}else{
				$this->form['button'] = $btn_cancel;
			}
			
		}else{
			$push = array(
				array(
					'type' => 'cancel',
					'label' => 'Tutup'
				)
			);
			$this->form['button'] = $push;
		}
		parent::getSingleData($id);
	}

	public function index(){
		$this->breadcrumb->addlevel(1, array(
			'url' => site_url('fp3'),
			'title' => 'FP3'
		));
		
		$this->header = 'FP3';
		$this->content = $this->load->view('fp3/list',null, TRUE);
		$this->script = $this->load->view('fp3/list_js', null, TRUE);
		parent::index();
	}

	public function save($data = null){
		$modelAlias = $this->modelAlias;
		// if ($this->validation()) {
			$save = $this->input->post();
			// print_r($save);die;
			$save['status'] 	 = 'ubah';
			$save['entry_stamp'] = timestamp();
			$save['idr_anggaran']= str_replace(',', '', $save['idr_anggaran']);
			$save['is_status']	 = 1;
			unset($save['fp3']);

			if ($this->$modelAlias->edit_to_fp3($save)) {
				$by_division = $this->get_division($this->session->userdata('admin')['id_division']);
				$division = $this->get_email_division($this->session->userdata('admin')['id_division']);

				$to_ = '';
				foreach ($division as $key => $value) {
					$to_ .= $value['email'].' ,';
				}
				$to = substr($to_,substr($to_),-2);
				$subject = 'FP3 baru telah dibuat.';
				$message = $save['nama_pengadaan'].' telah di buat oleh '.$by_division['name'];
				$this->send_mail($to, $subject, $message, $link);
				$data_note = array(
								'id_user' => $this->session->userdata('admin')['id_division'],
								'id_fppbj'=> $save['id_fppbj'],
								'value' => 'FP3 dengan nama pengadaan '.$save['nama_pengadaan'].' telah di buat oleh '.$by_division['name'],
								'entry_stamp'=> date('Y-m-d H:i:s'),
								'is_active' => 1
							);
				$this->db->insert('tr_note',$data_note);
				$this->session->set_flashdata('msg', $this->successMessage);
				$this->deleteTemp($save);
				echo json_encode(array('status'=>'success'));
			}
		// }
	}
	public function aktifkan($id) {
		if ($this->fp3->updateStatus($id,1)) {
			$return['status'] = 'success';
		}
		else {
			$return['status'] = 'error';
		}
		echo json_encode($return);
	}

	public function updateAktifkan($id){
		$this->formDelete['url'] = site_url('fp3/aktifkan/' . $id);
		$this->formDelete['button'] = array(
			array(
				'type' => 'delete',
				'label' => 'Aktifkan'
			) ,
			array(
				'type' => 'cancel',
				'label' => 'Batal'
			)
		);
		echo json_encode($this->formDelete);
	}
	public function batalkan($id) {	
		if ($this->fp3->updateStatus($id,2)) {
			$return['status'] = 'success';
		}
		else {
			$return['status'] = 'error';
		}
		echo json_encode($return);
	}

	public function updateBatalkan($id)
	{
		$this->formDelete['url'] = site_url('fp3/batalkan/'.$id);
		$this->formDelete['button'] = array(
			array(
				'type' => 'delete',
				'label' => 'Batalkan'
			),array(
				'type' => 'cancel',
				'label' => 'Cancel'
			)
		);
		echo json_encode($this->formDelete);
	}
	
		
	public function approve_($id, $param_){
		$table = "ms_fp3";
		// print_r($table);
		$param_ = array('is_status' => 1, 'is_approved' => $param_);
		$data 	= $this->mm->approve($table, $id, $param_);
		redirect($_SERVER['HTTP_REFERER']);
		return $data;
	}

	public function edit($id = null){

		$this->form = array(
				'form' => array(
					array(
						'field'	=> 	'fp3',
						'type'	=>	'fp3',
						'label'	=>	'FP3',
					),
					array(
						'field'	=> 	'nama_lama',
						'type'	=>	'text',
						'label'	=>	'Nama Pengadaan',
					),
					array(
						'field'	=> 	'nama_pengadaan',
						'type'	=>	'text',
						'label'	=>	'Nama Pengadaan (Baru)',
					),array(
						'field'	=> 	'metode_pengadaan',
						'type'	=>	'dropdown',
						'label'	=>	'Metode Pengadaan (Baru)',
						'source'=>	$this->mm->getProcMethod(),
					),array(
						'field'	=> 	array('jwpp_start','jwpp_end'),
						'type'	=>	'date_range',
						'label'	=>	'Jadwal Pengadaan (Baru)',
					),array(
						'field'	=> 	'desc',
						'type'	=>	'textarea',
						'label'	=>	'Keterangan',
					),array(
						'field' => 'lampiran_persetujuan',
						'type'  => 'file',
						'label' => 'Lampiran Persetujuan',
						'upload_path'=> base_url('assets/lampiran/lampiran_persetujuan/'),
						'upload_url'=> site_url('upload_lampiran_persetujuan/upload_lampiran'),
						'allowed_types'=> '*',
						'rules' => 'required' 
					),
				),
			);

		$modelAlias = $this->modelAlias;
		$data = $this->$modelAlias->selectData($id);
		
		foreach($this->form['form'] as $key => $element) {
			$this->form['form'][$key]['value'] = $data[$element['field']];
			if ($this->form['form'][$key]['field'] == 'nama_lama') {
				$this->form['form'][$key]['readonly'] = true;
			}
			if($this->form['form'][$key]['type']=='dateperiod'){
				$dateperiod = json_decode($getData[$value['field']]);
				$this->form['form'][$key]['value'] = date('d M Y', strtotime($dateperiod->start))." sampai ".date('d M Y', strtotime($dateperiod->end));
            }
            if($this->form['form'][$key]['type']=='date_range'){
				$_value = array();
				
				foreach ($this->form['form'][$key]['field'] as $keys => $values) {
					$_value[] = $data[$values];
					
				}
				$this->form['form'][$key]['value'] = $_value;
			}
		}

		$this->form['url'] = site_url($this->updateUrl . '/' . $id);
		$this->form['button'] = array(
			array(
				'type' => 'submit',
				'label' => 'Ubah'
			) ,
			array(
				'type' => 'cancel',
				'label' => 'Batal'
			)
		);
		echo json_encode($this->form);
	}

	public function update($id){
		$modelAlias = $this->modelAlias;
		// if ($this->validation()) {
			$save = $this->input->post();
			$lastData = $this->$modelAlias->selectData($id);
			if ($this->$modelAlias->update($id, $save)) {
				$this->session->set_userdata('alert', $this->form['successAlert']);
				$this->deleteTemp($save, $lastData);
				json_encode(array('status'=>'success'));
			}
		// }
	}
	
	public function form_download_fp3($id)
    {
    	$this->form = array(
    		'form'=> array(
    			array(
    				'field'=>'to',
    				'type'=>'text',
    				'label'=>'Kepada',
    			),
    			array(
    				'field'=>'pb',
    				'type'=>'text',
    				'label'=>'Pusat Biaya',
    			),
    			array(
    				'field'=>'no',
    				'type'=>'text',
    				'label'=>'Nomor',
    			),
    			array(
    				'field'=>'date',
    				'type'=>'date',
    				'label'=>'Tanggal',
    			),
    			array(
    				'field'=>'kadep_',
    				'type'=>'text',
    				'label'=>'Kolom TTD - Dept/Div',
				),
    			array(
    				'field'=>'kadep',
    				'type'=>'text',
    				'label'=>'Kolom TTD - Nama (min. setingkat Ka. Dept)',
				),
    			array(
    				'field'=>'kadiv_',
    				'type'=>'text',
    				'label'=>'Kolom TTD - Div/Dirut',
    			),
    			array(
    				'field'=>'kadiv',
    				'type'=>'text',
    				'label'=>'Kolom TTD - Nama (min. setingkat Ka. Divisi atau Direktur Utama
					untuk fungsi leher)',
    			)
    		)
    	);

    	$this->form['url'] = site_url('export/fp3/'.$id);
	 	$this->form['button'] = array(
	 		array(
	 			'type' => 'submit',
	 			'label' => 'Download',
	 		),
	 		array(
	 			'type' => 'cancel',
	 			'label' => 'Batal'
	 		)
	 	);
	 	echo json_encode($this->form);
    }
    public function reject($id, $param_){
		$id_pic = $this->input->post()['id_pic'];
		$table = "ms_fp3";
		$fp3 = $this->fp3->selectData($id);
		$fppbj = $this->fm->selectData($fp3['id_fppbj']);
		
		$save = $this->input->post();
		$save_keterangan['id_fppbj'] 	= $fp3['id_fppbj'];
		$save_keterangan['id_user'] 	= $fppbj['id_division'];
		$save_keterangan['is_active']   = 1;
		$save_keterangan['value']	    = $save['keterangan'];
		$save_keterangan['entry_stamp'] = date('Y-m-d H:i:s');

		$this->fm->insert_keterangan($save_keterangan);
		$param_ = array('is_status' => 1, 'is_reject' => 1,'is_approved' => $param_);
		$data 	= $this->mm->approve($table, $id, $param_);

		$division = $this->get_email_division($this->session->userdata('admin')['id_division']);

		$to = '';
		foreach ($division as $key => $value) {
			$to .= ' '.$value['email'];
		}
		
		$subject = $fppbj['nama_pengadaan'].' di tolak oleh '.$this->session->userdata('admin')['name'];
		$message = $save['keterangan'];
		$this->send_mail($to, $subject, $message, $link);

		redirect($_SERVER['HTTP_REFERER']);
		return $data;
	}
}
