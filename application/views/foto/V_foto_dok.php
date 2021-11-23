<style type="text/css">
    #gmbr{
        width: 200px;
        height: 125px;
        cursor: pointer;
    }
</style>
<section class="content-header">
    <h1>Debitur <?= $nama_deb ?></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Report Care</a></li>
        <li><a href="<?= base_url('data/foto/') ?>">Foto-Dok</a></li>
        <li><a href="<?= base_url('data/list_debitur/'.$id_ver) ?>">Detail-Foto-Dok</a></li>
        <li class="active"><a href="#">Debitur-Foto-Dok</a></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
      	<div class="col-md-12">

		<div class="box box-primary box-solid" style="margin-top: 30px">
            <div class="box-header with-border"  style="background-color: #122E5D; color: white; ">
                  <i class="fa fa-image"><?= nbs(3) ?><h1 class="box-title" style="font-size: 15px;">Foto Dokumen</h1></i>
             </div>
             <div class="box-body" style="background-color: white;">
                
             <div class="row gambar">

                <?php foreach ($d_foto as $f): ?>
                    
                    <div class="col-md-4">
                        <div class="thumbnail">
                            <!-- <img class="img-responsive" id="gmbr" style="height: 250px; width: 100%;" src="http://localhost/vcare/images/<?php echo $f['foto'];?>"> -->
                            <img class="img-responsive" id="gmbr" style="height: 250px; width: 100%;" src="http://vcare.skdigital.id/images/<?php echo $f['foto'];?>">
                            <div class="caption text-center" style="background-color: #1ba334; font-weight: bold; color: white;">
                                <label><?= $f['jenis_dok'] ?></label>
                                <?php if ($f['tampak_asset']) : ?>
                                    <br>
                                    <label><?= $f['tampak_asset'] ?></label>
                                <?php endif ?>
                                
                            </div>
                        </div>
                    </div>

                <?php endforeach ?>

                </div>

             </div>
        </div>
        
        </div>
    </div>
</section>

<!-- Photoviewer -->
<link rel="stylesheet" href="<?= base_url() ?>assets/viewer/css/viewer.css">
<!-- jQuery 3 -->
<script src="<?= base_url() ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Photoviewer -->
<script src="<?= base_url() ?>assets/viewer/js/viewer.js"></script>

<script type="text/javascript">
	$('.gambar').viewer();
</script>