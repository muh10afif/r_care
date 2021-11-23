<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url() ?>template/img/logo.png">
    <title>Report-Care</title>
    <!-- Custom CSS -->
    <link href="<?= base_url() ?>template/dist/css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url() ?>template/assets/swa/sweetalert2.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body style="background-color: #f7ffff">
    <div class="main-wrapper container-fluid">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-md-5 col-xs-12" align="center">
                <img class="img-fluid" style="margin-top: 15%; padding: 20px" width="450px" src="<?php echo base_url()?>template/img/logo.png">
            </div>
            <div class="col-md-7 col-xs-12 mt-2" style="text-align: center">
                <img class="img-fluid" src="<?= base_url('template/img/logo-sps.png') ?>" style="margin-top: 3%; padding: 15px" width="350px"><?= br(2) ?>
                <h3 style="color:#122E5D; font-weight: bold; text-shadow: 1px 0.5px 0px #000;">Mitra Terpercaya Penagihan dan Subrogasi Berbasis Digital</h3>
                <h4 style="color:#122E5D; font-weight: bold; text-shadow: 1px 0px 0px #000;">Collection And Recoveries System</h4><?= br() ?>
                <h2 style="color:#122E5D; font-weight: bold; text-shadow: 1px 0.5px 0px #000;">R - Care</h2>

                <div class="row mt-4 d-flex justify-content-center">
                <div class="col-md-4 shadow">
                <div class="card">
                    <div class="card-body">
                        <!-- Form -->
                            <div class="row">
                                <div class="col-12">
                                <form class="form-horizontal mt-4" id="form-login" autocomplete="off">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                                        </div>
                                        <input type="text" class="form-control form-control-lg" id="username" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                                    </div>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon2"><i class="ti-pencil"></i></span>
                                        </div>
                                        <input type="password" class="form-control form-control-lg" id="password" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1">
                                    </div>
                                    <div class="form-group text-center mt-4 mb-0">
                                        <div class="col-xs-12">
                                            <button class="btn waves-effect waves-light btn-info btn-block" type="submit">Log In</button>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                            </div>
                    </div> 
                </div>
               
                            
                
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper scss in scafholding.scss -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper scss in scafholding.scss -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right Sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right Sidebar -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="<?= base_url() ?>template/assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="<?= base_url() ?>template/assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="<?= base_url() ?>template/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?= base_url() ?>template/assets/swa/sweetalert2.all.min.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
    <script>
    $('[data-toggle="tooltip"]').tooltip();
    $(".preloader").fadeOut();
    // ============================================================== 
    // Login and Recover Password 
    // ============================================================== 
    $('#to-recover').on("click", function() {
        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
    });
    </script>
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

                            var url = "<?= base_url('home') ?>";

                            window.location.href = url;

                        } else if(data.hasil == 'HomeSyariah') {

                            var url = "<?= base_url('askSyariah/HomeSyariah') ?>";

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

</body>

</html>