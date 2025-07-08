<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

    private $eproc_db;

    public function __construct(){
        parent::__construct();
        $this->load->helper('string');
        $this->load->model('Main_model', 'mm');
    }

    public function index() {	
        $user = $this->session->userdata('user');
        $admin = $this->session->userdata('admin');

        if ($user) {
            redirect($this->config->item('redirect_dashboard'));
        } elseif ($admin) {
            $this->redirect_admin($admin);
        } else {
            $data['message'] = '';
            $this->load->view('login', $data);
        }
    }

    private function redirect_admin($admin){
        if (isset($admin['id_role']) && $admin['id_role'] == 6) {
            redirect($this->config->item('redirect_auction'));
        } elseif (isset($admin['app_type']) && $admin['app_type'] == 1) {
            redirect($this->config->item('url_eproc_pengadaan_admin'));
        } else {
            redirect($this->config->item('redirect_admin'));
        }
    }

    public function logout(){
        $this->session->sess_destroy();
        redirect(site_url());
    }

    public function check(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        if ($username && $password) {
            if ($this->mm->cek_login()) {
                // Login successful - check session immediately after login
                $user = $this->session->userdata('user');
                $admin = $this->session->userdata('admin');

                if ($user) {
                    // For users, redirect to dashboard module
                    redirect(site_url('dashboard'));
                } elseif ($admin) {
                    if (isset($admin['id_role']) && $admin['id_role'] == 6) {
                        redirect(site_url('auction'));
                    } else {
                        redirect(site_url('admin'));
                    }
                } else {
                    // No valid session found after login
                    $this->session->set_flashdata('error_msg', 'Terjadi kesalahan pada sistem login');
                    redirect(site_url());
                }
            } else {
                // Login failed
                $this->session->set_flashdata('error_msg', 'Username atau Password salah');
                redirect(site_url());
            }
        } else {
            // Missing username or password
            $this->session->set_flashdata('error_msg', 'Username dan Password harus diisi');
            redirect(site_url());
        }
    }

    /**
     * Secure login method with proper CSRF protection and JSON response
     */
    public function check_secure(){
        // Set JSON response headers
        $this->output->set_content_type('application/json');
        
        try {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            if (!$username || !$password) {
                throw new Exception('Username dan password harus diisi');
            }

            // Process login
            $result = $this->process_login_secure($username, $password);
            
            if ($result['success']) {
                $this->output->set_output(json_encode(array(
                    'success' => true,
                    'message' => 'Login berhasil',
                    'redirect_url' => $result['redirect_url']
                )));
            } else {
                $this->output->set_output(json_encode(array(
                    'success' => false,
                    'message' => $result['message']
                )));
            }
            
        } catch (Exception $e) {
            $this->output->set_output(json_encode(array(
                'success' => false,
                'message' => $e->getMessage()
            )));
        }
    }

    private function process_login_secure($username, $password) {
        $username = encode_php_tags($username);
        $query = "SELECT * FROM ms_login WHERE username = ?";
        $data = $this->db->query($query, array($username))->row_array();

        if (!$data) {
            return array('success' => false, 'message' => 'Username atau Password salah');
        }

        // Check if account is locked
        $now = date('Y-m-d H:i:s');
        if ($data['lock_time'] > date('Y-m-d H:i:s', strtotime("$now -9 hours"))) {
            return array('success' => false, 'message' => 'Akun telah di lock sementara, tunggu beberapa menit untuk login kembali');
        }

        // Verify password (assuming you have password verification)
        if ($this->verify_password($password, $data)) {
            return $this->login_user_secure($data);
        } else {
            return array('success' => false, 'message' => 'Username atau Password salah');
        }
    }

    private function verify_password($password, $user_data) {
        // Load the secure password library if not already loaded
        if (!isset($this->secure_password)) {
            $this->load->library('secure_password');
        }
        
        // Verify password using secure library (supports both bcrypt and legacy hashes)
        $is_valid = $this->secure_password->verify_password($password, $user_data['password']);
        
        if ($is_valid) {
            // Check if password needs rehashing (migration from legacy SHA-1 to bcrypt)
            if ($this->secure_password->needs_rehash($user_data['password'])) {
                log_message('info', 'Password migration required for user: ' . $user_data['username']);
                
                // Generate new secure bcrypt hash
                $new_hash = $this->secure_password->hash_password($password);
                
                if ($new_hash) {
                    // Update database with new secure hash
                    $this->db->where('id', $user_data['id'])
                             ->update('ms_login', array('password' => $new_hash));
                    
                    log_message('info', 'Password successfully migrated to bcrypt for user: ' . $user_data['username']);
                } else {
                    log_message('error', 'Failed to generate new secure hash for user: ' . $user_data['username']);
                }
            }
        }
        
        return $is_valid;
    }

    private function login_user_secure($data) {
        // Set session data
        $this->session->set_userdata('admin', $data);
        
        // Determine redirect URL based on user type
        if ($data['app_type'] == 1) {
            $redirect_url = $this->config->item('url_eproc_pengadaan_admin');
        } else {
            $redirect_url = $this->config->item('redirect_dashboard');
        }
        
        return array(
            'success' => true, 
            'message' => 'Login berhasil',
            'redirect_url' => $redirect_url
        );
    }

    private function process_login($username){
        $username = encode_php_tags($username);
        $query = "SELECT * FROM ms_login WHERE username = ?";
        $data = $this->db->query($query, array($username))->row_array();

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
            $this->process_user_login($data);
        }
    }

    private function process_user_login($data){
        if ($this->mm->cek_login()) {
            $user = $this->session->userdata('user');
            $admin = $this->session->userdata('admin');

            if ($user) {
                $this->generate_key($user, 'user', $this->config->item('redirect_dashboard'));
            } elseif ($admin) {
                $this->handle_admin_login($admin);
            }
        } else {
            $this->show_error_message("Username atau Password salah");
        }
    }

    private function handle_admin_login($admin){
        if (isset($admin['id_role']) && $admin['id_role'] == 6) {
            redirect($this->config->item('redirect_auction'));
        } else {
            redirect($this->config->item('redirect_admin'));
        }
    }

    private function generate_key($user_data, $type, $redirect_url){
        $key = $this->generate_unique_key();
        $data = $this->prepare_user_data($user_data, $type);
        $this->db->insert('ms_key_value', ['key' => $key, 'value' => json_encode($data), 'created_at' => date('Y-m-d H:i:s')]);
        redirect($redirect_url . "?key={$key}");
    }

    private function generate_unique_key(){
        return uniqid() . time() . random_string('alnum', 10);
    }

    private function prepare_user_data($user_data, $type){
        return (object) array_merge($user_data, ['type' => $type]);
    }

    private function show_error_message($message){
        $data['message'] = "<div class='alert alert-danger'>{$message}</div>";
        $this->load->view('login', $data);
    }

    // Legacy methods for backward compatibility
    public function login_user() {		
        $key = $this->input->get('key', TRUE);

        if (!$key || !$this->_isValidKey($key)) {
            redirect(site_url());
            return;
        }

        $data = $this->_getKeyData($key);
        if (!$data) {
            redirect(site_url());
            return;
        }

        $value = json_decode($data['value']);
        $this->_setUserSession($value);
        $this->_invalidateKey($key);

        $data['name'] = $value->name;
        $this->_loadView('redirect', $data);
    }

    public function login_admin() {
        $key = $this->input->get('key', TRUE);

        if (!$key || !$this->_isValidKey($key)) {
            redirect(site_url());
            return;
        }

        $data = $this->_getKeyData($key);
        if (!$data || $this->_isNotAdmin($data)) {
            redirect(site_url());
            return;
        }

        $value = json_decode($data['value']);
        $this->_setAdminSession($value);
        redirect(site_url($this->config->item('redirect_auction')));
    }

    public function showUser() {
        $dptUsers = $this->_getVendorUsers(2);
        $waitingUsers = $this->_getVendorUsers(1);

        $this->_generateExcel($dptUsers, 'Daftar User Vendor (DPT)');
        $this->_generateExcel($waitingUsers, 'Daftar User Vendor (Daftar Tunggu)');
	}

    public function login__() {
        $this->load->model('main_model');

        if ($this->input->post('username') && $this->input->post('password')) {
            if ($this->main_model->cek_login()) {
                $this->_handleLoginRedirect();
            } else {
                $this->_setFlashMessageAndRedirect('Data tidak dikenal. Silahkan login kembali!');
            }
        } else {
            $this->_setFlashMessageAndRedirect('Isi form dengan benar!');
        }
    }

    // Private Helper Methods
    private function _isValidKey($key) {
        return !empty($key);
    }

    private function _getKeyData($key) {
        return $this->db->where('key', $key)
                        ->where('deleted_at', NULL)
                        ->get('ms_key_value')
                        ->row_array();
    }

    private function _invalidateKey($key) {
        $this->db->where('key', $key)
                 ->update('ms_key_value', ['deleted_at' => date('Y-m-d H:i:s')]);
    }

    private function _setUserSession($value) {
        $sessionData = [
            'id_user' 		=> $value->id_user,
            'name'			=> $value->name,
            'id_sbu'		=> $value->id_sbu,
            'vendor_status'	=> $value->vendor_status,
            'is_active'		=> $value->is_active,
            'app'			=> 'vms'
        ];
        $this->session->set_userdata('user', $sessionData);
    }

    private function _setAdminSession($value) {
        $sessionData = [
            'id_user' 		=> $value->id_user,
            'name'			=> $value->name,
            'id_sbu'		=> $value->id_sbu,
            'id_role'		=> $value->id_role,
            'role_name'		=> $value->role_name,
            'sbu_name'		=> $value->sbu_name,
            'app'			=> $value->app
        ];
        $this->session->set_userdata('admin', $sessionData);
    }

    private function _isNotAdmin($data) {
        $value = json_decode($data['value']);
        return $value->id_role != 6;
    }

    private function _loadView($view, $data = []) {
        $item['content'] = $this->load->view($view, $data, TRUE);
        $this->load->view('template', $item);
    }

    private function _getVendorUsers($status) {
        $query = "SELECT a.name, b.username, b.password, a.vendor_status
                  FROM ms_vendor a
                  JOIN ms_login b ON a.id = b.id_user AND type = 'user'
                  WHERE a.del = 0 AND a.vendor_status = {$status}";
        return $this->db->query($query)->result_array();
    }

    private function _generateExcel($data, $title) {
        $output = '<table border="1">
                    <thead>
                        <tr><th colspan="4">'.$title.'</th></tr>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Password</th>
                        </tr>
                    </thead>
                    <tbody>';

        $no = 1;
        foreach ($data as $value) {
            $output .= '<tr>
                        <td>'.$no.'</td>
                        <td>'.$value['name'].'</td>
                        <td>'.$value['username'].'</td>
                        <td>'.$value['password'].'</td>
                        </tr>';
            $no++;
        }

        $output .= '</tbody></table><br><br>';

        header('Content-type: application/ms-excel');
        header('Content-Disposition: attachment; filename="Daftar User VMS.xls"');
        echo $output;
    }

    private function _handleLoginRedirect() {
        if ($this->session->userdata('user')) {
            $data = $this->session->userdata('user');
            $this->_loadView('redirect', $data);
        } elseif ($this->session->userdata('admin')) {
            $adminData = $this->session->userdata('admin');
            $redirectUrl = ($adminData['id_role'] == 6) ? $this->config->item('redirect_auction') : $this->config->item('redirect_admin');
            redirect(site_url($redirectUrl));
        }
    }

    private function _setFlashMessageAndRedirect($message) {
        $this->session->set_flashdata('error_msg', $message);
        redirect(site_url());
    }

    public function test_session()
    {
        $user = $this->session->userdata('user');
        $admin = $this->session->userdata('admin');
        
        echo "<h2>Session Test</h2>";
        echo "<h3>User Session:</h3>";
        echo "<pre>" . print_r($user, true) . "</pre>";
        echo "<h3>Admin Session:</h3>";
        echo "<pre>" . print_r($admin, true) . "</pre>";
        echo "<h3>All Session Data:</h3>";
        echo "<pre>" . print_r($this->session->all_userdata(), true) . "</pre>";
        
        if ($user) {
            echo "<p><a href='" . site_url('dashboard') . "'>Go to Dashboard</a></p>";
        }
        if ($admin) {
            echo "<p><a href='" . site_url('admin') . "'>Go to Admin</a></p>";
        }
    }
}