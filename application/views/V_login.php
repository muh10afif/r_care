<!DOCTYPE html>
<html lang="en">
<head>
	<title>R - Care</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url() ?>template/img/logo.png">	
	<!-- <link rel="icon" type="image/png" href="<?= base_url();?>template/login/images/icons/favicon.ico"/> -->
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?= base_url();?>template/login/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?= base_url();?>template/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?= base_url();?>template/login/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?= base_url();?>template/login/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="<?= base_url();?>template/login/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?= base_url();?>template/login/vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?= base_url();?>template/login/vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="<?= base_url();?>template/login/vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
<link rel="stylesheet" href="<?= base_url() ?>template/assets/swa/sweetalert2.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url();?>template/login/css/util.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url();?>template/login/css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100" style="background-image: url('<?= base_url();?>template/login/images/bg1.jpg');">
			<div class="wrap-login100 p-t-30 p-b-50">
				<span class="login100-form-title p-b-25">
                    <img src="<?= base_url() ?>template/img/logo_w.png" width="50%" alt="logo" />
				</span>
                <form class="login100-form validate-form p-b-33 p-t-5" id="form-login" method="post" autocomplete="off">

					<div class="wrap-input100 text-center" style="margin-top: -10px;">
                        <img src="<?= base_url() ?>template/img/logo-sps.png" width="50%" alt="logo" />
                        <h5 style="margin:10px; color:#122E5D; font-weight: bold; text-shadow: 1px 0px 0px #000;" class="mt-3">Mitra Terpercaya Penagihan dan Subrogasi Berbasis Digital</h5>
                        <h5 style="margin:10px; color:#122E5D; font-weight: bold; text-shadow: 1px 0px 0px #000;">Collection And Recoveries System</h5>
                        <h2 class="mb-2"><b style="color:#122E5D; font-size: 30px; font-weight: bold; text-shadow: 0.6px 0.5px 0.5px #000;">R - Care</b></h2>
                    </div>
                    
					<div class="wrap-input100 validate-input" data-validate = "Enter username">
						<input class="input100" type="text" name="username" placeholder="Username" id="username">
						<span class="focus-input100" data-placeholder="&#xe82a;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<input class="input100" type="password" name="password" placeholder="Password" id="password">
						<span class="focus-input100" data-placeholder="&#xe80f;"></span>
					</div>

					<div class="container-login100-form-btn m-t-32">
						<button class="login100-form-btn" type="submit" id="masuk">
							Login
						</button>
					</div>

				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	
<!--===============================================================================================-->
	<script src="<?= base_url();?>template/login/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="<?= base_url();?>template/login/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="<?= base_url();?>template/login/vendor/bootstrap/js/popper.js"></script>
	<script src="<?= base_url();?>template/login/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="<?= base_url();?>template/login/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="<?= base_url();?>template/login/vendor/daterangepicker/moment.min.js"></script>
	<script src="<?= base_url();?>template/login/vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="<?= base_url();?>template/login/vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
    <script src="<?= base_url() ?>template/assets/swa/sweetalert2.all.min.js"></script>
    <script src="<?= base_url();?>template/login/js/main.js"></script>

    <script>
        
    $(document).ready(function () {
        
        $('#form-login').on('submit', function () {

            var username    = $('#username').val();
            var password    = $('#password').val();

            if ((username == "") && (password == "")) {
                swal(
                    'Peringatan',
                    'Semua data harus terisi dahulu',
                    'warning'
                )

                return false;
            } else if (password == "" && username != "") {
                swal(
                    'Peringatan',
                    'Password harus terisi dahulu',
                    'warning'
                )

                return false;
            } else if (username == "" && password != "") {
                swal(
                    'Peringatan',
                    'Username harus terisi dahulu',
                    'warning'
                )

                return false;
            } else {
                // jalankan proses ajax kirim data
                $.ajax({
                    type        : "post",
                    url         : "<?= base_url('login/cek_login') ?>",
                    beforeSend  : function () {
                        swal({
                            title   : 'Menunggu',
                            html    : 'Memproses Data',
                            onOpen  : () => {
                                swal.showLoading();
                            }
                        })
                    },
                    data        : {username:username, password:password},
                    dataType    : 'JSON',
                    success     : function (data) {
                        
                        if (data.hasil == 'home') {

                            // swal({
                            //     title 	: 'Login Berhasil',
                            //     text 	: 'Selamat Datang Manager R-Care',
                            //     type 	: 'success',
                            //     timer 	: 1000,

                            //     showConfirmButton	: false
                            // }).then(function () {
                            //     var url = "<?= base_url('home') ?>";

                            //     window.location.href = url;
                            // })

                            var url = "<?= base_url('home') ?>";

                            window.location.href = url;

                        } else if(data.hasil == 'HomeSyariah') {

                            
                            var url = "<?= base_url('home/index/syariah') ?>";

                            window.location.href = url;

                        } else if (data.hasil == 'user_asuransi') {

                            var url = "<?= base_url('home/index/asuransi') ?>";

                            window.location.href = url;
                            
                        }  else if (data.hasil == 'kanwil_asuransi') {

                            var url = "<?= base_url('home/index/asuransi') ?>";

                            window.location.href = url;

                        } else if(data.hasil == 1) {

                            $('#password').val('');

                            swal({
                                title 	: 'Gagal',
                                text 	: 'Password yang dimasukkan salah!!',
                                type 	: 'error',
                                timer 	: 1000,

                                showConfirmButton 	: false
                            })

                            setTimeout(() => {
                                $('#password').focus();
                            }, 1300)  

                        } else if(data.hasil == 0) {

                            $('#username').val('');
                            $('#password').val('');

                            swal({
                                title 	: 'Gagal',
                                text 	: 'Username tidak ditemukan!!',
                                type 	: 'error',
                                timer 	: 1000,

                                showConfirmButton	: false
                            })

                            setTimeout(() => {
                                $('#username').focus();
                            }, 1300);

                        } else {

                            $('#username').val('');
                            $('#password').val('');

                            swal({
                                title 	: 'Gagal',
                                text 	: 'Anda tidak memiliki hak masuk sistem!!',
                                type 	: 'error',
                                timer 	: 1000,

                                showConfirmButton	: false
                            })

                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Error');
                    }							

                    
                })
                
                return false;

            }

        })

    })
    
    </script>