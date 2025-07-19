<?php
// Load environment variables before using them
if (!function_exists('env')) {
    require_once(dirname(dirname(__FILE__)) . '/env_loader.php');
}

$config['protocol'] = env('EMAIL_PROTOCOL', 'smtp');

$config['smtp_host'] = env('EMAIL_SMTP_HOST', 'tls://smtp.gmail.com');

$config['smtp_port'] = env('EMAIL_SMTP_PORT', '465');

$config['smtp_user'] = env('EMAIL_SMTP_USER', 'nusantararegas.smtp@gmail.com');

$config['smtp_pass'] = env('EMAIL_SMTP_PASS', 'jieouvzguchoqjro');

$config['mailtype'] = env('EMAIL_MAILTYPE', 'html');

$config['charset'] = env('EMAIL_CHARSET', 'iso-8859-1');

$config['wordwrap'] = TRUE;

$config['newline'] = "\r\n";

// Email enabled/disabled flag for development
$config['email_enabled'] = env('EMAIL_ENABLED', 'false') === 'true';
//=============================================

// $config['protocol'] = 'smtp';

// $config['smtp_host'] = 'ssl://mail.nusantararegas.com';

// $config['smtp_port'] = '587';

// $config['smtp_user'] = 'vms-noreply@nusantararegas.com';

// $config['smtp_pass'] = 'Nusantara1';

// $config['mailtype'] = 'html';

// $config['charset'] = 'iso-8859-1';

// $config['wordwrap'] = TRUE;
// $config['newline'] = "\r\n";

//****************************************************
// $config = Array(
    // 'protocol' => 'smtp',
    // 'smtp_host' => 'ssl://smtp.googlemail.com',
    // 'smtp_port' => 465,
    // 'smtp_user' => 'muarifgustiar@gmail.com',
    // 'smtp_pass' => 'muarifgustiaraliyudin',
    // 'mailtype'  => 'html', 
    // 'charset'   => 'iso-8859-1'
// );
// $this->load->library('email', $config);
// $this->email->set_newline("\r\n");