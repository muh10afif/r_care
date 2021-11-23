<style type="text/css">
	#tabel thead tr th {
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
          Report Eks Assets </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i>Report Care</a></li>
        <li class="active"><a href="#">R-Assets</a></li>
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
			
			<form method="POST" target="_self" id="tab" action="<?= base_url('r_eks_asset') ?>">
      		<div class="box-body text-center" style="background-color: white;">
      			<div class="form-group col-md-3">
	                <div class="input-group date">
	                  <div class="input-group-addon">
	                    <i class="fa fa-calendar"></i>
	                  </div>
	                  <input type="text" class="form-control pull-right" name="tgl_awal" placeholder="Tanggal Awal Periode" id="datepicker" value="<?= (!empty($p_tgl_awal) ? $p_tgl_awal : '') ?>">
	                </div>
	            </div>
	            <div class="form-group col-md-3">
	                <div class="input-group date">
	                  <div class="input-group-addon">
	                    <i class="fa fa-calendar"></i>
	                  </div>
	                  <input type="text" class="form-control pull-right" name="tgl_akhir" placeholder="Tanggal Akhir Periode" id="datepicker2" value="<?= (!empty($p_tgl_akhir) ? $p_tgl_akhir : '') ?>">
	                </div>
	            </div>
	            <div class="col-md-3">
	            	<!-- capem bank -->
	                <select class="form-control select2" name="jenis_asset" style="width: 100%;">  
	                	<option value="">-- Pilih Jenis Asset --</option>
	                	<?php foreach ($jenis_asset->result_array() as $j): ?>
	                		<option value="<?= $c = $j['jenis_asset'] ?>" <?= ($p_jns_asset == $c) ? 'selected' : ''; ?>><?= ucfirst($j['jenis_asset']) ?></option>
	                	<?php endforeach ?>
                    </select>
	            </div>
	            <div class="col-md-3">
	            	<!-- nama karyawan verifikator -->
	                <select class="form-control select2" name="bank" style="width: 100%;">
	                	<option value="">-- Pilih Bank --</option>
	                	<?php foreach ($bank->result_array() as $b): ?>
	                		<option value="<?= $e = $b['bank'] ?>" <?= ($p_bank == $e) ? 'selected' : ''; ?>><?= $b['bank'] ?></option>
	                	<?php endforeach ?>
                    </select>
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
				<table id="tabel" class="table table-striped table-hover table-bordered" >
					<thead style="background-color: #122E5D; color: white; ">
						<tr >
							<th>No</th>
							<th>Nama Debitur</th>
							<th>Cabang Bank</th>
							<th>Bank</th>
							<th>Asset / Agunan</th>
							<th>Status Proses</th>
							<th>Eksekutor</th>
						</tr>
					</thead>
					<tbody>
						<?php $no=1; foreach ($data_r_eks_asset->result_array() as $d): ?>
							<tr>
								<td><?= $no++ ?></td>
								<td><?= $d['nama_debitur'] ?></td>
								<td><?= $d['cabang_bank'] ?></td>
								<td><?= $d['bank'] ?></td>
								<td><?= $d['jenis_asset'] ?></td>
								<td><?= $d['status_asset'] ?></td>
								<td><?= $d['eksekutor'] ?></td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
				
			</div>
		</div>
		</div>	
		<div class="col-md-3">
			<div class="box box-primary box-solid">

                <!-- /.box-header -->
                <div class="box-body table-responsive" style="background-color: #122E5D; color: white; ">
                	<form method="POST" action="<?= base_url('r_eks_asset') ?>">
                	<div align="center">
                	<select class="form-control select2" name="wil">
	                    <option value="">-- Wilayah --</option>
	                    <?php foreach ($wilayah_ver->result_array() as $w): ?>
	                    	<option value="<?= $w['nama_lengkap'].'&&&'.$w['capem_bank'] ?>"><?= $w['nama_lengkap'].' '.$w['capem_bank'] ?></option>
	                    <?php endforeach ?>
                    </select>
                    <button class="btn" name="wilayah" style=" background-color: #EDE90F; color: #122E5D; margin-top: 10px; font-weight: bold;">Tampil</button>
                    </div>
                    </form>
                    <div class="box-body table-responsive" style="background-color: white;  margin-top: 20px;">
                    	<?php if (isset($_POST['wilayah'])): ?>
	                    	<?= $this->session->flashdata('pesan'); ?>
	                    <?php endif ?>
                    	<table width="100%">
                    		<tr style="border-top: hidden;">
                    			<td style="color: #122E5D; font-size: 18px; font-weight: bold; vertical-align: top;">Total Jumlah Asset</td>
                    		</tr>
                    		<tr style="border-top: hidden;">
                    		<td style="color: #EB1C24; font-size: 25px; font-weight: bold; text-align: right;">
                    			<?php if (isset($_POST['wilayah']) && !empty($wil)): ?>
	                    			<?php foreach ($data_ver as $d) { ?>
		                    			<?= $d['tot_jumlah_asset']  ?>
		                    		<?php }  ?> 
								<?php else: ?>
									<?= 0 ?>
								<?php endif ?>
                    		Unit</td>
                    		</tr>
                    	</table>
                    </div>
                    <div class="box-body table-responsive" style="background-color: white;  margin-top: 10px;">
                    	<table width="100%">
                    		<tr style="border-top: hidden;">
                    			<td style="color: #122E5D; font-size: 18px; font-weight: bold; vertical-align: top;">Total Hasil Penjualan</td>
                    		</tr>
                    		<tr style="border-top: hidden;">
                    		<td style="color: #EB1C24; font-size: 15px; font-weight: bold;">
                    			<?php if (isset($_POST['wilayah']) && !empty($wil)): ?>
	                    			<?php foreach ($data_ver as $d) { ?>
		                    			Rp. <?= number_format($d['tot_hasil_penjualan'],0,'.','.')  ?>
		                    		<?php }  ?> 
								<?php else: ?>
									Rp. <?= 0 ?>
								<?php endif ?>
                    		</td>
                    		</tr>
                    		<tr style="border-top: hidden;">
                    		<td style="color: #EB1C24; font-size: 25px; font-weight: bold; text-align: right;">
                    			<?php if (isset($_POST['wilayah']) && !empty($wil)): ?>
	                    			<?php foreach ($data_ver as $d) { ?>
		                    			<?= $d['tot_jumlah_asset_sdh']  ?>
		                    		<?php }  ?> 
								<?php else: ?>
									<?= 0 ?>
								<?php endif ?>
                    		Unit</td>
                    		</tr>
                    	</table>
                    </div>
                    <div class="box-body table-responsive" style="background-color: white;  margin-top: 10px;">
                    	<table width="100%">
                    		<tr style="border-top: hidden;">
                    			<td style="color: #122E5D; font-size: 18px; font-weight: bold; vertical-align: top;">Potensi Penjualan</td>
                    		</tr>
                    		<tr style="border-top: hidden;">
                    		<td style="color: #EB1C24; font-size: 25px; font-weight: bold; text-align: right;">
                    			<?php if (isset($_POST['wilayah']) && !empty($wil)): ?>
	                    			<?php foreach ($data_ver as $d) { ?>
		                    			Rp. <?= number_format($d['tot_hasil_penjualan_pot'],0,'.','.')  ?>
		                    		<?php }  ?> 
								<?php else: ?>
									Rp. <?= 0 ?>
								<?php endif ?>
                    		</td>
                    		</tr>
                    	</table>
                    </div>
                </div>

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

     