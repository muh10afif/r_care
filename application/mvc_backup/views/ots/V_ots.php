<style type="text/css">
	.a thead tr th {
		vertical-align: middle;
		text-align: center;
	}
	table.table-bordered{
	    border:1px solid #dee8fc;
	    margin-top:20px;
	}
	table.table-bordered > thead > tr > th{
	    border:1px solid #dee8fc;
	}
	table.table-bordered > tbody > tr > td{
	    border:1px solid #dee8fc;
	}
</style>
<section class="content-header">
        <h1>
          Report OTS </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i>Report Care</a></li>
        <li class="active"><a href="#">R-OTS</a></li>
        </ol>
      </section>

      <!-- Main content -->

<section class="content">
	<div class="row">
      	<div class="col-md-12">

		<div class="box box-primary box-solid">
			<div class="box-header with-border"  style="background-color: #122E5D; color: white; ">
                  <i class="fa fa-filter"><?= nbs(3) ?><h1 class="box-title" style="font-size: 15px;">Filter Data</h1></i>
             </div>

		<form method="POST" target="_self" id="tab" action="<?= base_url('r_ots') ?>">
      		<div class="box-body text-center" style="background-color: white;">
      			<div class="row">
      			<div class="col-md-12">
      			<div class="form-group col-md-4">
	                <div class="input-group date">
	                  <div class="input-group-addon">
	                    <i class="fa fa-calendar"></i>
	                  </div>
	                  <input type="text" class="form-control pull-right" name="tgl_awal" placeholder="Tanggal Awal Periode" id="datepicker" value="<?= ($p_tgl_awal != null) ? $p_tgl_awal : '' ?>">
	                </div>
	            </div>
	            <div class="form-group col-md-4">
	                <div class="input-group date">
	                  <div class="input-group-addon">
	                    <i class="fa fa-calendar"></i>
	                  </div>
	                  <input type="text" class="form-control pull-right" name="tgl_akhir" placeholder="Tanggal Akhir Periode" id="datepicker2" value="<?= ($p_tgl_akhir != null) ? $p_tgl_akhir : '' ?>">
	                </div>
	            </div>
	            <div class="col-md-4">
	            	<!-- cabang bank -->
	                <select class="form-control select2" name="wilayah" style="width: 100%;">  
	                	<option value="">-- Pilih Cabang --</option>
	                    <?php foreach ($wilayah->result_array() as $w): ?>
	                    	<option value="<?= $a = $w['cabang_bank'] ?>" <?= ($p_wilayah == $a) ? 'selected' : '' ?>><?= $w['cabang_bank'] ?></option>
	                    <?php endforeach ?>
                    </select>
	            </div>
	            </div>
	         </div>
	         <div class="row">
	         	<div class="col-md-12">
	         	<div class="col-md-4">
	            	<!-- nama karyawan verifikator -->
	                <select class="form-control select2" name="verifikator" style="width: 100%;">
	                	<option value="">-- Pilih Verifikator --</option>
	                    <?php foreach ($verifikator->result_array() as $v): ?>
	                    	<option value="<?= $c = $v['nama_lengkap'] ?>" <?= ($p_ver == $c) ? 'selected' : '' ?>><?= $v['nama_lengkap'] ?></option>
	                    <?php endforeach ?>
                    </select>
	            </div>
	            <div class="col-md-4">
	            	<!-- nama karyawan verifikator -->
	                <select class="form-control select2" name="bank" style="width: 100%;">
	                	<option value="">-- Pilih Bank --</option>
	                	<?php foreach ($bank->result_array() as $b): ?>
	                		<option value="<?= $e = $b['bank'] ?>" <?= ($p_bank == $e) ? 'selected' : ''; ?>><?= $b['bank'] ?></option>
	                	<?php endforeach ?>
                    </select>
	            </div>
	            <div class="col-md-4">
	                <select class="form-control select2" name="asuransi" style="width: 100%;">
	                	<option value="">-- Pilih Asuransi --</option>
	                	<?php foreach ($asuransi->result_array() as $d): ?>
	                		<option value="<?= $a = $d['id_asuransi'] ?>" <?= ($p_asuransi == $a) ? 'selected' : ''; ?> ><?= $d['asuransi'] ?></option>
	                	<?php endforeach ?>
                    </select>
	            </div>
	         </div>
	     	</div>
	      	</div>
	      	<div class="box-footer">
	      		<div class="col-md-12" align="center">
	      			<button class="btn" onclick="b()" name="cari" style=" background-color: #EDE90F; color: #122E5D; font-weight: bold; font-size: 15px; margin-top: 5px;"><i class="fa fa-send"></i><?= nbs(3) ?>Tampilkan</button><?= nbs(3) ?>
		      		<button class="btn" onclick="b()" name="all" style=" background-color: #122E5D; color: white; font-size: 15px; margin-top: 5px;"><i class="fa fa-refresh"></i><?= nbs(3) ?>Tampil Semua</button><?= nbs(3) ?>
	            	<button name="unduh" onclick="a()" class="btn" style=" background-color: #EDE90F; color: #122E5D;  font-weight: bold; font-size: 15px; margin-top: 5px;"><i class="fa fa-download"></i><?= nbs(3) ?>Export Data</button>
	           	</div>
	      			
		      		
	      	</div>
	      	</form>
      	</div>
     </div>
    </div>

	<div class="row">
		<div class="col-md-9">
			<div class="box box-primary box-solid">
			<div class="box-body table-responsive" style="background-color: white;">
				<?php if (isset($_POST['cari'])): ?>	
				<?= $this->session->flashdata('pesan'); ?>
				<?php endif ?>
				<table class="table table-striped table-hover table-bordered a" id="tabel">
					<thead style="background-color: #122E5D; color: white; ">
						<tr>
							<th>No</th>
							<th>Nama Debitur</th>
							<th>No Klaim / ID</th>
							<th>Cabang Bank</th>
							<th>Bank</th>
							<th>Alamat</th>
							<th>Hasil OTS</th>
							<th>POT</th>
						</tr>
					</thead>
					<tbody>
						<?php $no=0; foreach ($data_r_ots->result_array() as $d): $no++ ?>
						<tr>
							<td style="text-align: center;"><?= $no; ?></td>
							<td><?= $d['nama_debitur'] ?></td>
							<td><?= $d['no_klaim'] ?></td>
							<td><?= $d['cabang_bank'] ?></td>
							<td><?= $d['bank'] ?></td>
							<td><?= $d['alamat_deb'] ?></td>
							<td style="width: 150px;"><?= $d['keterangan'] ?></td>
							<td><?= $d['status_deb'] ?></td>
						</tr>
						<?php endforeach ?>
					</tbody>
				</table>
				
			</div>
		</div>
		</div>	
		<div class="col-md-3">
			<div class="box box-primary box-solid">

                <div class="box-header with-border"  style="background-color: #122E5D; color: white; ">
                  <i class="fa fa-star"><?= nbs(3) ?><h1 class="box-title" style="font-size: 15px;">Peringkat Verifikator</h1></i>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-striped table-hover">
                      <tbody>
                      <?php $no=1; foreach ($peringkat_ver->result_array() as $p): ?>
                      	<tr>
                          <td align="center" width="5px"><?= $no++ ?>.</td>
                          <td align="left"><?= $p['nama_lengkap'] ?></td>
                          <td style="font-size: 12px; width: 50%;"><?= "Rp. ".number_format($p['total_recoveries'],0,",",".") ?></td>
                       </tr> 
                      <?php endforeach ?>
                      </tbody>
                    </table>
                </div>

            </div>

            <div class="box-body" style="background-color: #EB1C24; color: white; font-size: 15px; margin-bottom: 5px;">
	            <table class="table">
	              <tr style="border-top: hidden;">
	                <td width="100px;">Total NOA</td>
	                <td>:</td>
	                <td style="font-weight: bold; font-size: 18px"><?= $total_noa ?></td>
	              </tr>
	            </table>
	        </div>
	        <div class="box-body" style="background-color: #EB1C24; color: white; font-size: 15px; margin-bottom: 5px;">
	            <table class="table">
	              <tr style="border-top: hidden;">
	                <td width="100px;">NOA OTS</td>
	                <td>:</td>
	                <td style="font-weight: bold; font-size: 18px;"><?= $total_ots ?></td>
	              </tr>
	            </table>
	        </div>
	        <div class="box-body" style="background-color: #EB1C24; color: white; font-size: 15px; margin-bottom: 5px;">
	            <table class="table">
	              <tr style="border-top: hidden;">
	                <td width="100px;">Persentase</td>
	                <td>:</td>
	                <td style="font-weight: bold; font-size: 18px">
	                	<?php if (($total_ots == 0) && ($total_noa == 0)): ?>
	                	<?= 0 ?>
	                	<?php else: ?>
	                	<?php $a = ($total_ots / $total_noa) * 100; echo number_format($a,2,',','.'); ?> %</td>
	                	<?php endif ?>
	              </tr>
	            </table>
	        </div>

	     </div>
	</div>
</div>




		
</section>

<!-- jQuery 3 -->
<script src="<?= base_url() ?>assets/bower_components/jquery/dist/jquery.min.js"></script>

<script type="text/javascript">
	function a() {
		var x = document.getElementById('tab');

		if (x.hasAttribute("target")) {
			x.setAttribute("target", "_blank");
		}
	}

	function b() {
		var x = document.getElementById('tab');

		if (x.hasAttribute("target")) {
			x.setAttribute("target", "_self");
		}
	}
</script>

     