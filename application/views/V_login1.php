<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url()?>assets/img/BG.png">
    <title>R - Care</title>
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
     <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/dist/css/skins/_all-skins.min.css">
    
    <!--</head>-->

    <style type="text/css">
      #log:hover{
        box-shadow: 2px 1px 2px 1px #000;
        border-radius: 10px;
        margin-left: 5px;
      }
    </style>
    
  </head>

  <body style="background-color: #f4d549; ">

    <div class="container">
      <div class="row">
        <div class="col-md-6 col-xs-12" align="center">
          <img class="img-responsive" style="margin-top: 15%; padding: 20px" width="360px" src="<?php echo base_url()?>assets/img/BG.png">
          <!---->
        </div>
      <div class="col-md-6 col-xs-12" style="margin-top: 5%;text-align: center;">
        <form class="form-signin" action="<?php echo base_url().'login/cek_login'?>" method="post">
            
            <h1><b style="color:#122E5D; font-size: 80px; font-weight: bold; text-shadow: 2px 1px 2px #000;">Report Care</b></h1>
            <?= br(3) ?>
            <div class="row" style="text-align: center;">
              <div class="col-md-3 col-xs-2"></div>
              <div class="col-md-6 col-xs-8">
                <?php echo $this->session->flashdata('pesan'); ?>
                <label for="username" class="sr-only">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
                <br />
                <label for="password" class="sr-only">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                <br />
                <button class="btn btn-md" id="log" style="background-color: #1a305b;" type="submit"><b style="color: #f4d549">L O G I N</b></button>
              </div>
              <div class="col-md-3 col-xs-2"></div>
            </div>

            
            
            <br />
            <br />
            

            <!-- <a href="#" style="color:#1a305b; float: right;"><b><u>register</u></b></a> -->
            </div>
          </form>
      </div>
     </div>
    </div> <!-- /container -->

   
<!-- jQuery 3 -->
<script src="<?= base_url() ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?= base_url() ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

  <!--</body></html>-->

  </body>
</html>
