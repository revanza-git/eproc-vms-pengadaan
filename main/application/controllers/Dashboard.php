<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public $admin;

	public function __construct(){
		parent::__construct();
		
		$this->admin = $this->session->userdata('admin');
		$this->load->model('Main_model', 'mm');

	}

	public function index($id=null){
		$admin = $this->session->userdata('admin');
		// print_r($admin);die;
		$data 						= '';
		$data['notification']		= $this->mm->notification($admin['id_division']);
		$data['total_notif']		= count($data['notification']->result_array());
		$data['fppbj']				= $this->mm->get_fppbj();
		$data['total_fppbj_semua']	= $this->mm->get_total_fppbj_semua();
		$data['fppbj_pending']		= $this->mm->get_fppbj_pending();
		$data['fppbj_selesai']		= $this->mm->get_fppbj_selesai();
		$data['fppbj_reject']		= $this->mm->get_fppbj_reject();
		$data['pending_kadiv']		= $this->mm->get_pending_kadiv();
		$data['pending_admin_hsse'] = $this->mm->get_pending_admin_hsse();
		$data['pending_admin_pengendalian']= $this->mm->get_pending_admin_pengendalian();
		$data['pending_kadept_proc']= $this->mm->get_pending_kadept_proc();
		$data['pending_dirut']		= $this->mm->get_pending_dirut();
		$data['pending_dirke']		= $this->mm->get_pending_dirke();
		$data['pending_dirsdm']		= $this->mm->get_pending_dirsdm();
		$data['done_dirut']			= $this->mm->get_done_dirut();
		$data['done_dirke']			= $this->mm->get_done_dirke();
		$data['done_dirsdm']		= $this->mm->get_done_dirsdm();
		$data['reject_dirut']		= $this->mm->get_reject_dirut();
		$data['reject_dirke']		= $this->mm->get_reject_dirke();
		$data['reject_dirsdm']		= $this->mm->get_reject_dirsdm();
		$data['total_fppbj_direktur'] = $this->mm->get_total_fppbj_directure();
		$data['total_fppbj_dirke']  = $this->mm->get_total_fppbj_dirke();
		$data['total_fppbj_dirut']  = $this->mm->get_total_fppbj_dirut();
		$data['total_fppbj_dirsdm'] = $this->mm->get_total_fppbj_dirsdm();
		$data['total_pending_dir']  = $this->mm->total_pending_dir();
		
		// $data['graph']	= $this->mm->rekapPerencanaanGraph($year);
		
		$this->header = 'Selamat Datang '.$this->admin['name'];
		$this->content = $this->parser->parse('dashboard/dashboard_admin', $data, TRUE);
		$this->script = $this->load->view('dashboard/dashboard_js', $data, TRUE);

		parent::index();
	}
	
	public function search_data(){
		echo json_encode($this->mm->search_data());
	}

	public function delete($id)
	{
		if ($this->mm->delete($id)) {
			$return['status'] = 'success';
		}
		else {
			$return['status'] = 'error';
		}

		echo json_encode($return);
	}

	public function delete_notif($id)
	{
		$this->formDelete['url'] = site_url('dashboard/delete/' . $id);
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

	function mail(){
		$this->send_mail();
	}
}
