<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fppbj extends MY_Controller {

	public $form;
	public $modelAlias 	= 'fm';
	public $alias 		= 'ms_fppbj';
	public $module 		= 'kurs';
	public $admin		= '';

	public function __construct(){
		parent::__construct();
		include_once APPPATH.'third_party/dompdf2/dompdf_config.inc.php';
		$this->load->model('Fppbj_model','fm');
		$this->load->model('Main_model','mm');
		$this->admin = $this->session->userdata('admin');

		$this->formWizard = array(
			'step'=>array(
				'fppbj'=>array(
					'label'=>'Form FPPBJ',
					'form'=>array(
								array(
									'field'	=> 	'no_pr',
									'type'	=>	'text',
									'label'	=>	'No. PR',
									'value' =>	'ss'
								),array(
									'field'	=> 	'tipe_pr',
									'type'	=>	'dropdown',
									'label'	=>	'Tipe PR',
									'source'=>	array(0 => 'Pilih Dibawah Ini', 'direct_charge' => 'Direct Charges', 'services' => 'Services', 'user_purchase' => 'User Purchase'),
								),
								array(
									'field'	=> 	'nama_pengadaan',
									'type'	=>	'text',
									'label'	=>	'Nama Pengadaan',
									'rules' => 	'required',
								),array(
									'field'	=> 	'pengadaan',
									'type'	=>	'dropdown',
									'label'	=>	'Jenis Pengadaan',
									'source'=>	array(0 => 'Pilih Dibawah Ini', 'jasa' => 'Pengadaan Jasa', 'barang' => 'Pengadaan Barang'),
									'rules'	=>	'required'
								),array(
									'field'	=> 	'jenis_pengadaan',
									'type'	=>	'dropdown',
									'label'	=>	'Jenis Detail Pengadaan',
									'source'=>	array('' => 'Pilih Jenis Pengadaan Diatas'),
									'rules'	=>	'required'
								),array(
									'field'	=> 	'metode_pengadaan',
									'type'	=>	'dropdown',
									'label'	=>	'Metode Pengadaan',
									'source'=>	$this->mm->getProcMethod(),
									'rules'	=> 	'required'
								),array(
									'field'	=> 	'idr_anggaran',
									'type'	=>	'currency',
									'label'	=>	'Anggaran (IDR)',
								),array(
									'field'	=> 	'usd_anggaran',
									'type'	=>	'currency',
									'label'	=>	'Anggaran (USD)',
								),array(
									'field'	=> 	'year_anggaran',
									'type'	=>	'number',
									'label'	=>	'Tahun Anggaran',
									'rules' => 	'required'
								),array(
									'field'	=> 	'kak_lampiran',
									'type'	=>	'file',
									'label'	=>	'KAK / Spesifikasi Teknis',
									'upload_path'=> base_url('assets/lampiran/kak_lampiran/'),
									'upload_url'=> site_url('fkpbj/upload_lampiran'),
									'allowed_types'=> '*',
								),array(
									'field'	=> 	'hps',
									'type'	=>	'radio',
									'label'	=>	'Ketersediaan HPS',
									'source'=>	array(1 => 'Ada', 0 => 'Tidak Ada')
								),array(
									'field'	=> 	'lingkup_kerja',
									'type'	=>	'textarea',
									'label'	=>	'Lingkup Kerja',
									'rules' => 	'required'
								),array(
									'field'	=> 	'penggolongan_penyedia',
									'type'	=>	'dropdown',
									'label'	=>	'Penggolongan Penyedia Jasa (Usulan)',
									'source'=>	array(0 => 'Pilih Dibawah Ini', 'perseorangan' => 'Perseorangan', 'usaha_kecil' => 'Usaha Kecil(K)', 'usaha_menengah' => 'Usaha Menengah(M)', 'usaha_besar' => 'Usaha Besar(B)')
								)
								// ,array(
								// 	'field'	=> 	'penggolongan_CSMS',
								// 	'type'	=>	'dropdown',
								// 	'label'	=>	'Penggolongan CSMS (Sesuai Hasil Analisa Resiko)',
								// 	'source'=>	array(0 => 'Pilih Dibawah Ini', 'high' => 'High', 'medium' => 'Medium', 'low' => 'Low')
								// )
								,array(
									'field'	=> 	'jwpp',
									'type'	=>	'dateperiod',
									'label'	=>	'Jangka Waktu Penyelesaian Pekerjaan ("JWPP")',
									'rules' =>  'required'
								),array(
									'field'	=> 	'jwp',
									'type'	=>	'dateperiod',
									'label'	=>	'Masa Pemeliharaan'
								),array(
									'field'	=> 	'desc_metode_pembayaran',
									'type'	=>	'textarea',
									'label'	=>	'Metode Pembayaran (Usulan)',
								),array(
									'field'	=> 	'jenis_kontrak',
									'type'	=>	'dropdown',
									'label'	=>	'Jenis Kontrak (Usulan)',
									'source'=>	array(	''	 	=> 'Pilih Dibawah Ini',
														'po' 	=> 'Purchase Order (PO)',
														'GTC01' => 'GTC01 - Kontrak Jasa Konstruksi non EPC',
														'GTC02' => 'GTC02 - Kontrak Jasa Konsultan',
														'GTC03' => 'GTC03 - Kontrak Jasa Umum',
														'GTC04' => 'GTC04 - Kontrak Jasa Pemeliharaan',
														'GTC05' => 'GTC05 - Kontrak Jasa Pembuatan Software',
														'GTC06' => 'GTC06 - Kontrak Jasa Sewa Fasilitas dan Alat',
														'GTC07' => 'GTC07 - Kontrak Jasa Tenaga Kerja.',
														'spk'	=> 'SPK'
													)
								),array(
									'field'	=> 	'sistem_kontrak',
									'type'	=>	'multiple',
									'label'	=>	'Sistem Kontrak (Usulan)',
									'source'=>	array(	'lumpsum' 		=> 'Perikatan Harga - Lumpsum',
														'unit_price'	=> 'Perikatan Harga - Unit Price',
														'modified' 		=> 'Perikatan Harga - Modified (lumpsum + unit price)',
														'outline' 		=> 'Perikatan Harga - Outline Agreement',
														'turn_key' 		=> 'Delivery - Turn Key',
														'sharing' 		=> 'Delivery - Sharing Contract',
														'success_fee' 	=> 'Delivery - Success Fee',
														'stockless' 	=> 'Delivery - Stockless Purchasing',
														'on_call' 		=> 'Delivery - On Call Basic',
													)
								),array(
									'field'	=> 	'desc_dokumen',
									'type'	=>	'textarea',
									'label'	=>	'Keterangan',
								)
								// ,array(
								// 	'field'	=> 	'desc',
								// 	'type'	=>	'textarea',
								// 	'label'	=>	'Keterangan Lainnya',
								// )
								
							),
					'button'=>array(
						array(
							'type'=>'prev',
							'label'=>'Sebelumnya',
							'class'=>'btn-prev'
						),array(
							'type'=>'next',
							'label'=>'Lanjut',
							// 'class'=>'btn-to-jasa btn-to-swakelola btn-to-jasa-swakelola'
							'class'=>'btn-to'
						)
					)
				),
				'dpt'=>array(
					'label'=>'Rekomendasi DPT',
					'form'=>array(
								array(
									'field'	=> 	'type',
									'type'	=>	'checkbox',
									'label'	=>	'Daftar DPT',
									// 'rules' => 	'required',
									'full'=>true,
									'source'=>	array(
										'' => 'Pilih DPT'
									)
								),
								array(
									'field'		=> 'type_usulan',
									'type'		=> 'text',
									'label'		=> 'Usulan Non DPT'
								),							
							),
					'button'=>array(
						array(
							'type'=>'prev',
							'label'=>'Sebelumnya',
							'class'=>'btn-prev'
						),array(
							'type'=>'next',
							'label'=>'Lanjut',
							'class'=>'btn-next'
						)
					)
				)
			)
		);

		$this->form = $this->formWizard['step']['fppbj'];

		$this->insertUrl = site_url('fppbj/save/');
		$this->updateUrl = 'fppbj/update';
		$this->deleteUrl = 'fppbj/delete/';
		$this->getData = $this->fm->getData($this->form);
		$this->form_validation->set_rules($this->form['form']);
	}

	function save(){
		$modelAlias = $this->modelAlias;
		if ($this->validation()) {
			$save = $this->input->post();
			$save['idr_anggaran'] = str_replace(',', '', $save['idr_anggaran']);
			$save['id_division'] = $this->session->userdata('admin')['id_division'];
			$save['entry_stamp'] = timestamp();
			
			if ($this->$modelAlias->insert($save)) {
				$this->session->set_flashdata('msg', $this->successMessage);
				$this->deleteTemp($save);
				return true;
			}
		}
	}

	public function index(){
		$this->breadcrumb->addlevel(1, array(
			'url' => site_url('fppbj'),
			'title' => 'FPPBJ'
		));

		$this->header 	= 'FPPBJ';
		$this->content 	= $this->load->view('fppbj/list',null, TRUE);
		$this->script 	= $this->load->view('fppbj/list_js', null, TRUE);
		parent::index();
	}


	public function getSingleData($id=null){
		// define status parameter
		$admin = $this->session->userdata('admin');
		
		$param_  = ($admin['id_role'] == 4) ? ($param_=1) : '' ;
		$this->form['url'] 		= site_url($this->approveURL.$id.'/'.$param_);
		$this->form['button'] 	= array(
										# code...
										array(
											'type' 	=> 'export',
											'link'	=> $this->form['url'],
											'label' => '<i style="line-height:25px;" class="fas fa-thumbs-up"></i>&nbsp;Setujui Data'
										),
										array(
											'type' => 'cancel',
											'label' => 'Tutup'
										)
								);

		parent::getSingleData($id);
	}

	public function approve($id, $param_){
		$user = $this->fm->get_email($id);
		$email = array();

		$fppbj = $this->fm->selectDataFppbj($id);

		foreach ($user as $key => $value) {
			// echo $value['email'];
			array_push($email, $value['email']);
		}

			$email = implode(',',$email) ;

		// print_r($email);die;
		$id_pic = $this->input->post()['id_pic'];
		$table = "ms_fppbj";
		if ($this->session->userdata('admin') != 5) {
			$param_ = array('is_reject' => 0,'id_pic'=>$id_pic,'is_approved' => $param_);
		} else{
			$param_ = array('is_reject' => 0,'id_pic'=>$id_pic,'is_approved' => $param_,'is_approved_hse'=>1);
		}
		
		$data 	= $this->mm->approve($table, $id, $param_);

		$division = $this->get_email_division($this->session->userdata('admin')['id_division']);

		$to_ = '';
		foreach ($division as $key => $value) {
			$to_ .= $value['email'].' ,';
		}
		$to = substr($to_,substr($to_),-2);
		if ($fppbj['is_status'] == '0') {
			$subject = 'FPPBJ telah disetujui';
		} else if ($fppbj['is_status'] == '1') {
			$subject = 'FP3 telah disetujui';
		} else {
			$subject = 'FKPBJ telah disetujui';
		}
		$message = $fppbj['nama_pengadaan'].' telah di setujui oleh '.$this->session->userdata('admin')['name'];
		//$this->send_mail($to, $subject, $message, $link);

		// if($data){
		// 	// $to = $this->fm->get_email($id);
		// 	// $this->send_mail($to, $subject, $message);
		// }

		redirect($_SERVER['HTTP_REFERER']);
		return $data;
	}

	public function reject($id, $param_){
		$id_pic = $this->input->post()['id_pic'];
		$table = "ms_fppbj";
		$admin = $this->session->userdata('admin');
		$fppbj = $this->fm->selectData($id);

		// echo $fppbj['id_division'];

		$save = $this->input->post();
		$save_keterangan['entry_by']    = $admin['id_user'];
		$save_keterangan['id_fppbj']    = $id;
		$save_keterangan['id_user']     = $fppbj['id_division'];
		$save_keterangan['is_active']   = 1;
		$save_keterangan['value']	    = $save['keterangan'];
		$save_keterangan['entry_stamp'] = date('Y-m-d H:i:s');
		$save_keterangan['type']   		= 'reject';

		$param_ 	 = array('is_reject' => 1);

		$insert_desc = $this->fm->insert_keterangan($save_keterangan);
		$data 		 = $this->mm->approve($table, $id, $param_);

		$division = $this->get_email_division($this->session->userdata('admin')['id_division']);

		$to = '';
		foreach ($division as $key => $value) {
			$to .= ' '.$value['email'];
		}
		
		$subject = $fppbj['nama_pengadaan'].' di tolak';
		$message = $fppbj['nama_pengadaan'].' di tolak oleh '.$this->session->userdata('admin')['name'].'. <br> catatan : <br>'.$save['keterangan'];
		$this->send_note($fppbj['id_division'],$admin['id_user'],$message,$id);
		$this->send_mail($to, $subject, $message, $link);

		redirect($_SERVER['HTTP_REFERER']);
		return $data;
	}

	public function btnCallback($id,$param){
		$analisa_risiko = $this->db->where('id_fppbj', $id)->get('tr_analisa_risiko')->row_array();

		if ($this->admin['id_role'] == 4 && $this->admin['id_division'] == 5) {
			//echo "kondisi 1";die;
			$this->approveRisiko($id,$param);
		}else{
			if (isset($_POST['approve'])) {
				//echo "kondisi 2";die;
				$this->approve($id,$param);
			}else{
				$this->reject($id,$param);
			}
		}
	}

	public function approveRisiko($id,$param){

		if (isset($_POST['approve'])) {
			if ($this->session->userdata('admin')['id_division'] != 5) {
				$approve = array('is_approved'=>$param,'is_reject' => 1);	
			}else{
				$approve = array('is_approved'=>$param,'is_approved_hse' => 1,'is_reject' => 0);	
			}			
			$this->db->where('id', $id)->update('ms_fppbj', $approve);
			$this->db->where('id_fppbj', $id)->update('tr_analisa_risiko', array('is_approved' => 1));
		}else{
			$this->db->where('id', $id)->update('ms_fppbj', array('is_approved'=>$param,'is_reject' => 1));
			$this->db->where('id_fppbj', $id)->update('tr_analisa_risiko', array('is_approved' => 2));
		}
		
		redirect($_SERVER['HTTP_REFERER']);
	}

	public function edit($id = null){
		$modelAlias = $this->modelAlias;
		$data = $this->$modelAlias->selectData($id);
		
		foreach($this->form['form'] as $key => $element) {
			$this->form['form'][$key]['value'] = $data[$element['field']];
			if($this->form['form'][$key]['type']=='date_range'){
				$_value = array();
				
				foreach ($this->form['form'][$key]['field'] as $keys => $values) {
					$_value[] = $data[$values];
					
				}
				$this->form['form'][$key]['value'] = $_value;
			}

			if ($this->form['form'][$key]['field']=='nama_pengadaan') {
				$this->form['form'][$key]['readonly'] = true; 
			}
			if ($this->form['form'][$key]['field']=='metode_pengadaan') {
				$this->form['form'][$key]['readonly'] = true; 
			}
			if ($this->form['form'][$key]['field']=='jwpp') {
				$this->form['form'][$key]['readonly'] = true; 
			}
			if ($this->form['form'][$key]['field']=='jwp') {
				$this->form['form'][$key]['readonly'] = true; 
			}
		}
		$this->form['fppbj'] = $data;

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
		$fppbj = $this->fm->selectData($id);
		
		if ($this->validation()) {
			$param_  = ($admin['id_role'] == 4) ? ($param_=1) : (($admin['id_role'] == 6) ? ($param_=2) : (($admin['id_role'] == 2) ? ($param_=3) : ''));
			$save = $this->input->post();
			$save['is_approved'] 			 = $param_; 
			$save['is_reject'] 				 = 0;
			$save['id_division'] 	    	 = $fppbj['id_division'];
			$save['nama_pengadaan']   		 = $fppbj['nama_pengadaan'];
			$save['idr_anggaran']   		 = $save['idr_anggaran'];
			$save['year_anggaran']   		 = $save['year_anggaran'];
			$save['hps']   					 = $save['hps'];
			$save['lingkup_kerja']   		 = $save['lingkup_kerja'];
			$save['penggolongan_penyedia']   = $save['penggolongan_penyedia'];
			$save['desc_metode_pembayaran']  = $save['desc_metode_pembayaran'];
			$save['jenis_kontrak']   		 = $save['jenis_kontrak'];
			$save['sistem_kontrak']   		 = json_encode($save['sistem_kontrak']);
			$save['metode_pengadaan'] 		 = $fppbj['metode_pengadaan'];
			$save['jwpp'] 					 = $fppbj['jwpp'];
			$save['jwp'] 			    	 = $fppbj['jwp'];
			$save['entry_stamp']  			 = timestamp();
			$lastData = $this->$modelAlias->selectData($id);
			// die;
			$query = $this->$modelAlias->update($id, $save);

			if ($query) {

				$this->session->set_userdata('alert', $this->form['successAlert']);
				$this->deleteTemp($save, $lastData);
				json_encode(array('status' => 'success'));
			}
		}
	}

	public function validation(){
		$__validation = $this->formWizard['step'][$_POST['validation']]['form'];
		if($_POST['validation']=='fppbj'){
			
			$__val = array();
			foreach ($this->input->post() as $key => $value) {
				$__val[$key] = $__validation[$value];
			}
			// print_r($__val);
			$this->form_validation->set_rules($__val);
			$this->validation($__val);
		}else{

			$this->form_validation->set_rules($this->formWizard['step'][$_POST['validation']]['form']);
			$this->validation($__validation);
		}	
	}

	// public function update($id){
	// 	$modelAlias = $this->modelAlias;
	// 	$fppbj = $this->fm->selectData($id);
	// 	$admin = $this->session->userdata('admin');
	// 	$_form = array();
	// 		$i = 0;
	// 		foreach ($this->form['form'] as $key => $value) {
	// 			foreach ($value as $keys => $values) {
	// 				$_form[$i] = $values;  
	// 				$i++;
	// 			}

	// 		}
	// 		$this->form['form'] = $_form;
	// 	$this->form_validation->set_rules($this->form['form']);

	// 	if ($this->validation()) {
	// 		$param_  = ($admin['id_role'] == 4) ? ($param_=1) : (($admin['id_role'] == 6) ? ($param_=2) : (($admin['id_role'] == 2) ? ($param_=3) : ''));
	// 		$save = $this->input->post();
	// 		$save['is_approved'] = $param_; 
	// 		$save['is_reject'] = 0;
	// 		$save['id_fppbj'] 		= $id_fppbj;
	// 		$save['id_division'] 	    = $fppbj['id_division'];
	// 		$save['nama_pengadaan']   	= $fppbj['nama_pengadaan'];
	// 		$save['idr_anggaran']   	= $save['idr_anggaran'];
	// 		$save['year_anggaran']   	= $save['year_anggaran'];
	// 		$save['hps']   			= $save['hps'];
	// 		$save['lingkup_kerja']   	= $save['lingkup_kerja'];
	// 		$save['penggolongan_penyedia']   	= $save['penggolongan_penyedia'];
	// 		$save['desc_metode_pembayaran']   	= $save['desc_metode_pembayaran'];
	// 		$save['jenis_kontrak']   	= $save['jenis_kontrak'];
	// 		$save['sistem_kontrak']   = json_encode($save['sistem_kontrak']);
	// 		$save['metode_pengadaan'] = $fppbj['metode_pengadaan'];
	// 		$save['jwpp'] 			= $fppbj['jwpp'];
	// 		$save['jwp'] 			    = $fppbj['jwp'];
	// 		$save['is_status'] 		= 2;
	// 		$save['is_approved']  	= 0;
	// 		$save['entry_stamp']  	= timestamp();
	// 		$lastData = $this->$modelAlias->selectData($id);
	// 		if ($this->$modelAlias->update($id, $save)) {
	// 			$this->session->set_userdata('alert', $this->form['successAlert']);
	// 			$this->deleteTemp($save, $lastData);
	// 			json_encode(array('status' => 'success'));
	// 		}
	// 	}
	// }
	
}