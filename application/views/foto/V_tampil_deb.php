<style type="text/css">
	#tabel thead tr th {
		vertical-align: middle;
		text-align: center;
	}
</style>
<section class="content-header">
    <h1>List Debitur <?= $nama_kar ?></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Report Care</a></li>
        <li><a href="<?= base_url('data/foto') ?>">Foto-Dok</a></li>
        <li class="active"><a href="#">Detail-Foto-Dok</a></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
      	<div class="col-md-12">

		<div class="box box-primary box-solid" style="margin-top: 30px">
            <div class="box-header with-border"  style="background-color: #122E5D; color: white; ">
                  <i class="fa fa-users"><?= nbs(3) ?><h1 class="box-title" style="font-size: 15px;">Data Debitur</h1></i>
             </div>
             <div class="box-body table-responsive" style="background-color: white;">
                <table id="tabel" class="table table-bordered table-hover">
                    <thead style="background-color: #122E5D; color: white; ">
                        <tr>
                            <th>No</th>
                            <th width="25%">Nama</th>
                            <th>No Klaim</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach ($d_debitur as $v): ?>

                            <tr>
                                <td align="center"><?= $no++ ?></td>
                                <td><?= $v['nama_debitur'] ?></td>
                                <td><?= $v['no_klaim'] ?></td>
                                <td align="center"><a href="<?= base_url('data/foto_debitur/'.$v['id_debitur'].'/'.$id_ver) ?>"><button class="btn btn-success" <?= ($v['status'] == 'tidak') ? 'disabled' : '' ?>>Foto</button></a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
             </div>
        </div>
        
        </div>
    </div>
</section>