<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Aplikasi Sistem Kelogistikan</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/styles/normalize.css'); ?>" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url('assets/font/font-awesome/css/font-awesome.min.css'); ?>" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url('assets/styles/base.css'); ?>" type="text/css" media="screen" />
    <style>
        .login-form {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-login:hover {
            background: #0056b3;
        }
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .input-group {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }
        .form-control.with-icon {
            padding-left: 40px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-form">
            <h2 style="text-align: center; margin-bottom: 30px;">VMS eProc Login</h2>
            
            <?php if(isset($message) && $message): ?>
                <div class="alert alert-danger"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php echo form_open('main/check_secure', array('id' => 'login-form')); ?>
                <div class="form-group">
                    <div class="input-group">
                        <i class="fa fa-user input-icon"></i>
                        <input type="text" name="username" class="form-control with-icon" placeholder="Username" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="input-group">
                        <i class="fa fa-key input-icon"></i>
                        <input type="password" name="password" class="form-control with-icon" placeholder="Password" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn-login">LOGIN</button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>

    <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.min.js');?>"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#login-form').on('submit', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    beforeSend: function() {
                        $('.btn-login').prop('disabled', true).text('Logging in...');
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location = response.redirect_url;
                        } else {
                            $('.alert').remove();
                            $('.login-form').prepend('<div class="alert alert-danger">' + response.message + '</div>');
                        }
                    },
                    error: function() {
                        $('.alert').remove();
                        $('.login-form').prepend('<div class="alert alert-danger">An error occurred. Please try again.</div>');
                    },
                    complete: function() {
                        $('.btn-login').prop('disabled', false).text('LOGIN');
                    }
                });
            });
        });
    </script>
</body>

</html> 