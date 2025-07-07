<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller{
	public $id_client;

	public $_sideMenu;

	public $breadcrumb;

	public $header;

	public $content;

	public $script;

	public $form;

	public $activeMenu;

	public $successMessage = '<div class="alert alert-success temp">Sukses</div>';

	public $isClientMenu;
	protected $session_security;
    protected $input_security;

	function __construct(){
		parent::__construct();
		$this->load->library('session');
		$this->_sideMenu = array();
		$this->load->library('breadcrumb', array());
		$this->form_validation->set_error_delimiters('', '');
		if ($this->uri->segment(1) != '') {
			if (!$this->session->userdata('admin')) {
				redirect(site_url());
			}
		}
		$this->load->library('session');
	}

	function index($id = null){

	/*
	| -------------------------------------------------------------------
	|  Basic Structure of pages
	| -------------------------------------------------------------------
	*/
		$this->isAdmin();
		$this->breadcrumb = $this->breadcrumb->generate();
		$this->load->library('sideMenu', $this->_sideMenu);
		
		$user 	= $this->session->userdata('user');
		$admin 	= $this->session->userdata('admin');
		$data = array(
			'user' 			=> ($user) ? $user['name'] : $admin['name'],
			'sideMenu' 		=> $this->sidemenu->generate($this->activeMenu) ,
			'breadcrumb' 	=> $this->breadcrumb,
			'header' 		=> $this->header,
			'content' 		=> $this->content,
			'script' 		=> $this->script
		);
		$this->parser->parse('template/base', $data);
	}

	function formFilter(){
		$return['button'] = array(
			array(
				'type' => 'button',
				'label' => 'Filter',
				'class' => 'btn-filter'
			) ,
			array(
				'type' => 'reset',
				'label' => 'Reset'
			)
		);
		$return['form'] = $this->form['filter'];
		echo json_encode($return);
	}

	function isAdmin(){
		$admin = $this->session->userdata('admin');
		if ($this->session->userdata('admin')) {
			
			/*
			| -------------------------------------------------------------------
			|  Structure of your side menu
			| -------------------------------------------------------------------
			*/
			$this->_sideMenu = array(
				array(
					'group' => 'dashboard',
					'title' => 'Dashboard',
					'icon' => 'home',
					'url' => site_url() ,
					'role' => array(
						1,
						2,
						3,
						4,
						5,
						6,
						7,
						8,
						9
					)
				),
				array(
					'title' => 'Pengadaan Barang/Jasa',
					'icon' => 'cubes',
					'url' => base_url('pemaketan') ,
					'group' => 'pemaketan',
					'url' => '#',
					'role' => array(
						1,
						2,
						3,
						4,
						5,
						6,
						7,
						8,
						9
					) ,
					'list' => array(
						array(
							'url' => base_url('perencanaan/rekap') ,
							'title' => 'Rekap Perencanaan',
							'role' => array(
								1,
								2,
								3,
								6
							),
						),
						array(
							'url' => base_url('pemaketan') ,
							'title' => 'Perencanaan Pengadaan',
							'role' => array(
								1,
								2,
								3,
								6,
								7,
								8,
								9
							),
						),
						array(
							'url' => base_url('pemaketan/division/'.$admin['id_division']),
							'title' => 'Perencanaan Pengadaan',
							'role' => array(
								4,
								5,
							)
						)
					)
				) ,
				array(
					'title' => 'FP3',
					'icon' => 'table',
					'group' => 'FP3',
					'url' => base_url('fp3') ,
					'role' => array(
						2,
						3,
						4,
						5,
						7,
						8,
						9
					) ,
				) ,
				array(
					'title' => 'Master',
					'icon' => 'database',
					'url' => '#',
					'role' => array(
						1
					) ,
					'list' => array(
						array(
							'url' => site_url('master/kurs') ,
							'title' => 'Kurs',
							'role' => array(
								1
							)
						),
						array(
							'url' => site_url('master/user') ,
							'title' => 'User',
							'role' => array(
								1
							)
						)
					)
				) ,
			);
		}
	}

	public function validation($form = null){

		ob_start();

		$_r = false;
		if ($form == null) {
			$form = $this->form['form'];
			$this->form_validation->set_rules($this->form['form']);
		}

		if ($this->form_validation->run() == FALSE) {
			
			$return['status'] = 'error';
			foreach($form as $value) {
				if ($value['type'] == 'file') {
					$return['file'][$value['field']] = $this->session->userdata($value['field']);
				}

				if ($value['type'] == 'date_range') {
					$return['form'][$value['field'][0]] = form_error($value['field'][0] . '_start');
					$return['form'][$value['field'][1]] = form_error($value['field'][1] . '_start');
				}
				else {
					$return['form'][$value['field']] = form_error($value['field']);
				}
			}

			$_r = false;
		}
		else {
			$return['status'] = 'success';
			$_r = true;
		}

		echo json_encode($return);
		return $_r;
	}

	public function getData($id = null)
	{
		$config['query'] = $this->getData;
		$return = $this->tablegenerator->initialize($config);
		echo json_encode($return);
	}

	public function insert()
	{
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
	public function insertStep(){
		$this->formWizard['url'] = $this->insertUrl;
		$this->formWizard['button'] = array(
			array(
				'type' => 'submit',
				'label' => 'Simpan',
			) ,
			array(
				'type' => 'cancel',
				'label' => 'Batal'
			)
		);
		echo json_encode($this->formWizard);
	}

	public function save($data = null)
	{
		$modelAlias = $this->modelAlias;
		if ($this->validation()) {
			$save = $this->input->post();
			$save['entry_stamp'] = timestamp();
			if ($this->$modelAlias->insert($save)) {
				$this->session->set_flashdata('msg', $this->successMessage);
				$this->deleteTemp($save);
				return true;
			}
		}
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
		if ($this->validation()) {
			$save = $this->input->post();
			$lastData = $this->$modelAlias->selectData($id);
			if ($this->$modelAlias->update($id, $save)) {
				$this->session->set_userdata('alert', $this->form['successAlert']);
				$this->deleteTemp($save, $lastData);
			}
		}
	}
	 public function getSingleData($id){
        $user  = $this->session->userdata('user');
        $modelAlias = $this->modelAlias;
        $getData   = $this->$modelAlias->selectData($id);
		// print_r($getData);
        foreach($this->form['form'] as $key => $value){
			$this->form['form'][$key]['readonly'] = TRUE;
			$getData[$value['field']] = ($getData[$value['field']]) ? $getData[$value['field']] : "-" ;
            $this->form['form'][$key]['value'] = $getData[$value['field']];
           
            if($value['type']=='date_range'){
                foreach($value['field'] as $keyField =>$rowField){
                    $this->form['form'][$key]['value'][] = $getData[$rowField];
                }
            }
            if($value['type']=='dateperiod'){
				$dateperiod = json_decode($getData[$value['field']]);
				$this->form['form'][$key]['value'] = date('d M Y', strtotime($dateperiod->start))." sampai ".date('d M Y', strtotime($dateperiod->end));
            }
            if($value['type']=='money'){
                    $this->form['form'][$key]['value'] = number_format($getData[$value['field']]);
            }
            if($value['type']=='currency'){
                    $this->form['form'][$key]['value'] = number_format($getData[$value['field']],2);
            }
            if($value['type']=='money_asing'){
                $this->form['form'][$key]['value'][] = $getData[$value['field'][0]];
                $this->form['form'][$key]['value'][] = number_format($getData[$value['field'][1]]);
            }
        }

        echo json_encode($this->form);
    }
	public function approveOvertimeUser($id)
	{
		$modelAlias = $this->modelAlias;
		$save = $this->input->post();
		$save['edit_stamp'] = timestamp();
		return $this->$modelAlias->update($id, $save);
	}

	public function delete($id)
	{
		$modelAlias = $this->modelAlias;
		if ($this->$modelAlias->delete($id)) {
			$return['status'] = 'success';
		}
		else {
			$return['status'] = 'error';
		}

		echo json_encode($return);
	}

	public function remove($id)
	{
		$this->formDelete['url'] = site_url($this->deleteUrl . $id);
		$this->formDelete['button'] = array(
			array(
				'type' => 'delete',
				'label' => 'Hapus'
			) ,
			array(
				'type' => 'cancel',
				'label' => 'Batal'
			)
		);
		echo json_encode($this->formDelete);
	}

	public function upload_lampiran()
	{
		
		foreach($_FILES as $key => $row) {
			if(is_array($row['name'])){
				foreach ($row['name'] as $keys => $values) {
					$file_name = $row['name'] = $key . '_' . name_generator($_FILES[$key]['name'][$keys]);
					 $_FILES['files']['name']= $file_name;
			        $_FILES['files']['type']= $_FILES[$key]['type'][$keys];
			        $_FILES['files']['tmp_name']= $_FILES[$key]['tmp_name'][$keys];
			         $_FILES['files']['error']= $_FILES[$key]['error'][$keys];
			         $_FILES['files']['size']= $_FILES[$key]['size'][$keys];
					
					$config['upload_path'] = './assets/lampiran/temp/';
					$config['allowed_types'] = $_POST['allowed_types'];
					$this->load->library('upload');
					$this->upload->initialize($config);

					if (!$this->upload->do_upload('files')) {
						$return['status'] = 'error';
						$return['message'] = $this->upload->display_errors('', '');
					}
					else {
						$return['status'] = 'success';
						$return['upload_path'] = base_url('assets/lampiran/temp/' . $file_name);
						$return['file_name'] = $file_name;
					}

					echo json_encode($return);
				}
				
			}else{
				$file_name = $_FILES[$key]['name'] = $key . '_' . name_generator($_FILES[$key]['name']);
				$config['upload_path'] = './assets/lampiran/temp/';
				$config['allowed_types'] = $_POST['allowed_types'];
				$this->load->library('upload');
				$this->upload->initialize($config);
				if (!$this->upload->do_upload($key)) {
					$return['status'] = 'error';
					$return['message'] = $this->upload->display_errors('', '');
				}
				else {
					$return['status'] = 'success';
					$return['upload_path'] = base_url('assets/lampiran/temp/' . $file_name);
					$return['file_name'] = $file_name;
				}

				echo json_encode($return);
			}
			
		}
	}

	public function do_upload($field, $db_name = ''){
		$file_name = $_FILES[$db_name]['name'] = $db_name . '_' . name_generator($_FILES[$db_name]['name']);
		$config['upload_path'] = './assets/lampiran/' . $db_name . '/';
		$config['allowed_types'] = 'pdf|jpeg|jpg|png|gif';
		$this->load->library('upload');
		$this->upload->initialize($config);
		if (!$this->upload->do_upload($db_name)) {
			$_POST[$db_name] = $file_name;
			$this->form_validation->set_message('do_upload', $this->upload->display_errors('', ''));
			return false;
		}
		else {
			$this->session->set_userdata($db_name, $file_name);
			$_POST[$db_name] = $file_name;
			return true;
		}
	}

	public function deleteTemp($save, $lastData = null)
	{
		
		foreach($this->form['form'] as $key => $value) {

			if ($value['type'] == 'file') {
				if ($lastData != null && ($save[$value['field']] != $lastData[$value['field']])) {

					if ($lastData[$value['field']] != '') {
						unlink('./assets/lampiran/' . $value['field'] . '/' . $lastData[$value['field']]);
					}
				}

				if ($save[$value['field']] != '') {
					if (file_exists('./assets/lampiran/temp/' . $save[$value['field']])) {
						rename('./assets/lampiran/temp/' . $save[$value['field']], './assets/lampiran/' . $value['field'] . '/' . $save[$value['field']]);
					}
				}
			}
		}
	}


	public function send_note($to, $from, $value,$id_fppbj,$document){
		return $this->db->insert('tr_note', array('entry_by' => $from, 'id_user' => $to, 'value' => $value, 'document' => $document,'id_fppbj' => $id_fppbj,'is_active'=>1));
	}

	public function send_mail($to, $subject, $message, $link="#"){	
		/* 
		| -------------------------------------------------------------------
		| Push Mail Notification 
		| -------------------------------------------------------------------
		| These function send email notification to user.
		|
		|	$to 		= {direct to email user};
		|	$subject	= {title email};
		|	$messagege	= {notification message};
		|	$link		= {link to application (if exist)};
		|
		*/
		
		$admin = $this->session->userdata('admin');
		// print_r($this->session->userdata('admin'));
		/*if ($to == '') {
			$to		= "arinal.dzikrul@dekodr.co.id";
		}*/
		//$to		= "arinal.dzikrul@dekodr.co.id";
		// $subject = "Pembuatan Usulan Komag Baru";
		// $message = "<b>".$admin['name']."</b> Telah membuat usulan komag baru.";
		$link = "location.href='asd';";
		$config = Array(
			'protocol' 	=> 'smtp',
			'smtp_host' => 'mail.nusantararegas.com',
			'smtp_port' => 587,
			'smtp_user' => 'no-reply@nusantararegas.com',
			'smtp_pass' => 'Nus@nt@r@h3b4t',
			'mailtype'  => 'html', 
			'charset'   => 'utf-8',
			'smtp_crypto' => 'tls',
			'crlf' => "\r\n",
				// 'charset'   => 'iso-8859-1',
		);
        $this->load->library('email');

        $this->email->initialize($config);
		$this->email->set_newline("\r\n");
		
		// Set to, from, message
		$this->email->from('no-reply@nusantararegas.com', 'System aplikasi kelogistikan');

		$html = '<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
						<meta http-equiv="content-type" content="text/html; charset=utf-8">
						<meta name="viewport" content="width=device-width, initial-scale=1.0;">
						<meta name="format-detection" content="telephone=no"/>
				
						<style>
							/* Reset styles */ 
							body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important;}
							body, table, td, div, p, a { -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; }
							table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse !important; border-spacing: 0; }
							img { border: 0; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
							#outlook a { padding: 0; }
							.ReadMsgBody { width: 100%; } .ExternalClass { width: 100%; }
							.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }
				
							/* Rounded corners for advanced mail clients only */ 
							@media all and (min-width: 560px) {
								.container { border-radius: 8px; -webkit-border-radius: 8px; -moz-border-radius: 8px; -khtml-border-radius: 8px;}
							}
				
							/* Set color for auto links (addresses, dates, etc.) */ 
							a, a:hover {
								color: #127DB3;
							}
							.footer a, .footer a:hover {
								color: #999999;
							}
				
						</style>
				
						<!-- MESSAGE SUBJECT -->
						<title>Aplikasi Sistem Kelogistikan</title>
				
					</head>
				
					<!-- BODY -->
					<body topmargin="0" rightmargin="0" bottommargin="0" leftmargin="0" marginwidth="0" marginheight="0" width="100%" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%; height: 100%; -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%;
						background-color: #F0F0F0;
						color: #000000;"
						bgcolor="#F0F0F0"
						text="#000000">
				
					<!-- SECTION / BACKGROUND -->
					<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%;" class="background"><tr><td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;" bgcolor="#F0F0F0">
				
						<!-- WRAPPER -->
						<table border="0" cellpadding="0" cellspacing="0" align="center"
							width="560" style="border-collapse: collapse; border-spacing: 0; padding: 0; width: inherit;
							max-width: 560px;" class="wrapper">
				
							<tr>
								<td align="center" valign="top" style="display: none; border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
									padding-top: 20px;
									padding-bottom: 20px;">
								</td>
							</tr>
				
						<!-- End of WRAPPER -->
						</table>
				
						<!-- WRAPPER / CONTEINER -->
						<table border="0" cellpadding="0" cellspacing="0" align="center"
							bgcolor="#FFFFFF"
							width="560" style="margin-top: 1.75rem; border-collapse: collapse; border-spacing: 0; padding: 0; width: inherit;
							max-width: 560px;" class="container">
				
							<!-- HEADER -->
							<!-- Set text color and font family ("sans-serif" or "Georgia, serif") -->
							<tr>
								<td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 24px; font-weight: bold; line-height: 130%;
									padding-top: 25px;
									color: #000000;
									font-family: sans-serif;" class="header">
										<img src="'.base_url("assets/images/NUSANTARA-REGAS-2.png").'" alt="" style="height: 35px; float: left;">
								</td>
							</tr>
				
							<!-- LINE -->
							<tr>
								<td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
									padding-top: 25px;" class="line"><hr
									color="#E0E0E0" align="center" width="100%" size="1" noshade style="margin: 0; padding: 0;" />
								</td>
							</tr>
				
							<!-- PARAGRAPH -->
							<tr>
								<td align="left" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 14px; font-weight: 400; line-height: 160%;
									padding-top: 25px; 
									color: #000000;
									font-family: sans-serif;" class="paragraph">
								</td>
							</tr>
							<tr>
								<td align="left" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 14px; font-weight: 400; line-height: 160%;
									padding-top: 25px; 
									color: #000000;
									font-family: sans-serif;" class="paragraph">
										'.$message.'
								</td>
							</tr>
							<tr>
								<td align="left" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 14px; font-weight: 400; line-height: 160%;
									padding-top: 25px; 
									color: #000000;
									font-family: sans-serif;" class="paragraph">
										...
								</td>
							</tr>
				
							<!-- BUTTON -->
							<tr>
							<!--
								<td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
									padding-top: 25px;
									padding-bottom: 5px;" class="button"><a
									href="https://github.com/konsav/email-templates/" target="_blank" style="text-decoration: underline;">
										<table border="0" cellpadding="0" cellspacing="0" align="center" style="width: 100%; min-width: 120px; border-collapse: collapse; border-spacing: 0; padding: 0;"><tr><td align="center" valign="middle" style="padding: 12px 24px; margin: 0; text-decoration: none; border-collapse: collapse; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;"
											bgcolor="#1784c7"><a target="_blank" style="text-decoration: none!important;
											color: #FFFFFF; font-family: sans-serif; font-size: 14px; font-weight: 400; line-height: 120%;"
											href="https://github.com/konsav/email-templates/">
												Klik disini
											</a>
									</td></tr></table></a>
								</td>
								-->
							</tr>
				
							<!-- LINE -->
							<tr>	
								<td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
									padding-top: 25px;" class="line"><hr
									color="#E0E0E0" align="center" width="100%" size="1" noshade style="margin: 0; padding: 0;" />
								</td>
							</tr>
				
							<!-- PARAGRAPH -->
							<!-- Set text color and font family ("sans-serif" or "Georgia, serif"). Duplicate all text styles in links, including line-height -->
							<tr>
								<td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 14px; font-weight: 400; line-height: 160%;
									padding-top: 20px;
									padding-bottom: 25px;
									color: #a0a0a0;
									font-family: sans-serif;" class="paragraph">
										&#169; 2019 Nusantara Regas. All Rights Reserved.<br>Wisma Nusantara- lt. 19 Jl. M.H. Thamrin No.59 Jakarta 10350-Indonesia
								</td>
							</tr>
				
						<!-- End of WRAPPER -->
						</table>
				
						<!-- WRAPPER -->
						<table border="0" cellpadding="0" cellspacing="0" align="center"
							width="560" style="border-collapse: collapse; border-spacing: 0; padding: 0; width: inherit;
							max-width: 560px;" class="wrapper">
				
							<!-- FOOTER -->
							<tr>
								<td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: 400; line-height: 150%;
									padding-top: 20px;
									padding-bottom: 20px;
									color: #999999;
									font-family: sans-serif;" class="footer">
										Email ini dikirim secara otomatis oleh Aplikasi Kelogistikan Nusantara Regas
								</td>
							</tr>
				
						<!-- End of WRAPPER -->
						</table>
				
						</table>
				
						<!-- End of SECTION / BACKGROUND -->
						</td>
						</tr>
					</table>
				
					</body>
				</html>';

		
		
		// print_r($html);die;
		$this->email->to('revanza.raytama@nusantararegas.com');
		//'ayu@nusantararegas.com','amathul@nusantararegas.com','haryo.priantomo@nusantararegas.com'
		// $this->email->cc('amathul@nusantararegas.com,haryo.priantomo@nusantararegas.com');
		$this->email->bcc('revanza.raytama@nusantararegas.com'); 
        $this->email->subject($subject);
        $this->email->message($html);

		$result = $this->email->send();
		
		/*if ($result) {
			// $to, $from, $value, $document
			$this->send_note($to, $from, $message, '');
		}else{
			print_r($this->email->print_debugger());	
		}*/
		
		return $result;
	}

	public function get_email_division($division)
	{
		$query = "SELECT email FROM ms_user WHERE id_division = ".$division;
		$query = $this->db->query($query)->result_array();
		return $query;
	}

	public function get_division($division)
	{
		$query = "SELECT * FROM tb_division WHERE id = ".$division;
		$query = $this->db->query($query)->row_array();
		return $query;
	}
	
	function sendMailEproc($to,$sub,$message){
		
		$config = Array(
			'protocol' 	=> 'smtp',
			'smtp_host' => 'mail.nusantararegas.com',
			'smtp_port' => 587,
			'smtp_user' => 'no-reply@nusantararegas.com',
			'smtp_pass' => 'Nus@nt@r@h3b4t',
			'mailtype'  => 'html', 
			'charset'   => 'utf-8',
			'smtp_crypto' => 'tls',
			'crlf' => "\r\n",
				// 'charset'   => 'iso-8859-1',
		);
        $this->load->library('email');

        $this->email->initialize($config);
		$this->email->set_newline("\r\n");
		
		// Set to, from, message
		$this->email->from('no-reply@nusantararegas.com', 'System aplikasi kelogistikan');
		$this->email->to($to);
		//'ayu@nusantararegas.com','amathul@nusantararegas.com','haryo.priantomo@nusantararegas.com'
		$this->email->cc('amathul@nusantararegas.com,haryo.priantomo@nusantararegas.com');
		$this->email->bcc('revanza.raytama@nusantararegas.com'); 
        $this->email->subject($subject);
        $this->email->message($html);
		$this->email->send();
		
		echo "Berhasil";
	}
}
