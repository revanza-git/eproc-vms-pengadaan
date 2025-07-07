<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

    private $eproc_db;

    public function __construct(){
        parent::__construct();
        $this->load->library(['pdf']);
        $this->load->helper('string');
        include_once APPPATH.'third_party/dompdf2/dompdf_config.inc.php';

        $this->load->model('Main_model', 'mm');
        $this->eproc_db = $this->load->database('eproc', true);
    }

    public function index(){
        $user = $this->session->userdata('user');
        $admin = $this->session->userdata('admin');

        if ($user) {
            redirect($this->config->item('url_eproc_pengadaan_dashboard'));
        } elseif ($admin) {
            $this->redirect_admin($admin);
        } else {
            $data['message'] = '';
            $this->load->view('template/layout-login-nr', $data);
        }
    }

    private function redirect_admin($admin){
        if ($admin['app_type'] == 1) {
            redirect($this->config->item('url_eproc_pengadaan_admin'));
        } else {
            redirect($this->config->item('url_eproc_nusantararegas_dashboard'));
        }
    }

    public function logout(){
        $this->session->sess_destroy();
        redirect($this->config->item('url_eproc_nusantararegas'));
    }

    public function check(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        if ($username && $password) {
            $this->process_login($username);
        }
    }

    private function process_login($username){
        $username = encode_php_tags($username);
        $query = "SELECT * FROM ms_login WHERE username = ?";
        $data = $this->eproc_db->query($query, array($username))->row_array();

        if ($data) {
            $this->handle_login($data);
        } else {
            $this->show_error_message("Username atau Password salah");
        }
    }

    private function handle_login($data){
        $now = date('Y-m-d H:i:s');

        if ($data['lock_time'] > date('Y-m-d H:i:s', strtotime("$now -9 hours"))) {
            $this->show_error_message("Akun telah di lock sementara, tunggu beberapa menit untuk login kembali");
        } else {
            $this->login_user($data);
        }
    }

    private function login_user($data){
        if ($this->mm->cek_login()) {
            $user = $this->session->userdata('user');
            $admin = $this->session->userdata('admin');

            if ($user) {
                $this->generate_key($user, 'user', $this->config->item('url_login_user'));
            } elseif ($admin) {
                $this->handle_admin_login($admin);
            }
        } else {
            $this->show_error_message("Username atau Password salah");
        }
    }

    private function handle_admin_login($admin){
        $key_url = ($admin['app_type'] == 1)
            ? $this->config->item('url_login_admin')
            : $this->config->item('url_from_eks');

        $this->generate_key($admin, 'admin', $key_url);
    }

    private function generate_key($user_data, $type, $redirect_url){
        $key = $this->generate_unique_key();
        $data = $this->prepare_user_data($user_data, $type);

        $this->eproc_db->insert('ms_key_value', ['key' => $key, 'value' => json_encode($data)]);
        redirect("$redirect_url?key=$key");
    }

    private function generate_unique_key(){
        return random_string('unique') . random_string('unique') . random_string('unique') . random_string('unique');
    }

    private function prepare_user_data($user_data, $type){
        return array_merge($user_data, ['type' => $type]);
    }

    private function show_error_message($message){
        $data['message'] = "<div class='notification is-danger'>$message</div>";
        $this->load->view('template/layout-login-nr', $data);
    }

    public function check_c1($name, $division, $id_user, $id_role, $id_division, $email, $photo_profile){
        $session_data = compact('name', 'division', 'id_user', 'id_role', 'id_division', 'email', 'photo_profile');
        $this->session->set_userdata('admin', $session_data);
        redirect('dashboard');
    }

    public function check___(){
        $result = $this->mm->check($this->input->post());

        if ($result) {
            echo "<script type='text/javascript'>alert('Login Berhasil');</script>";
            redirect('dashboard');
        } else {
            $this->show_error_message("Username atau Password salah");
        }
    }

    public function custom_query(){
        $this->mm->custom_query();
    }

    public function update_status(){
        $id_fppbj = $this->input->get('id_fppbj');
        $param_ = $this->input->get('param_');
        
        $this->mm->update_status('ms_fppbj', $id_fppbj, $param_);
        echo $id_fppbj;
    }

    public function search(){
        $q = $this->input->get('q');
        $data = $this->mm->search($q);

        echo json_encode($data);
    }

    public function get_dpt_csms($csms){
        $data = $this->eproc_db->select('ms_vendor.name vendor, ms_vendor.id id_vendor, tb_csms_limit.end_score score, tb_csms_limit.value csms')
            ->where('ms_csms.id_csms_limit', $csms)
            ->where('ms_vendor.vendor_status', 2)
            ->join('ms_csms', 'ms_vendor.id = ms_csms.id_vendor')
            ->join('tb_csms_limit', 'tb_csms_limit.id = ms_csms.id_csms_limit')
            ->get('ms_vendor')
            ->result_array();

        echo json_encode($data);
    }

    public function get_dpt(){
        $search = $this->input->post('search');
        $sql = "SELECT name, id FROM ms_vendor WHERE del = 0 AND name LIKE ?";
        $query = $this->db->query($sql, array('%'.$search.'%'));

        echo json_encode($query->result_array());
    }

    public function rekapPerencanaanGraph($year){
        return $this->mm->rekapPerencanaanGraph($year);
    }

    public function view_calendar() {
        $this->load->view('timeline/calendar');
    }
}