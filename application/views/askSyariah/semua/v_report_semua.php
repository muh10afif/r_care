<style type="text/css">
	#ad thead tr th {
		vertical-align: middle;
		text-align: center;
	}

</style>
<section class="content-header">
	<h1>
	  All Report </h1>
	<ol class="breadcrumb">
	  <li><a href="#"><i class="fa fa-dashboard"></i>Report Care</a></li>
	<li class="active"><a href="#">All Report</a></li>
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

      		<form method="POST" target="_self" id="tab" action="<?= base_url('askSyariah/R_semuaSyariah') ?>">
      		<div class="box-body text-center" style="background-color: white;">
      			<div class="col-md-3">
      				<select class="form-control select2" id="jenis" name="jenis_report" required="">
      					<option value="">-- Pilih Jenis Report --</option>
      					<option value="1">Rekap OTS dan Recoveries Berdasarkan Hasil OTS Verfikator</option>
      					<option value="2">Laporan OTS</option>
      					<option value="3">Laporan Debitur yang sudah Dikunjungi</option>
      					<option value="4">Report Achievment Recoveries & OTS By Verfikator </option>
      				</select>
      			</div>
      			<div class="form-group col-md-3">
	                <div class="input-group date">
	                  <div class="input-group-addon">
	                    <i class="fa fa-calendar"></i>
	                  </div>
	                  <input type="text" class="form-control pull-right" name="bulan_awal" placeholder="Tanggal Awal Periode" id="datepicker3" required="">
	                </div>
	            </div>
	            <div class="form-group col-md-3">
	                <div class="input-group date">
	                  <div class="input-group-addon">
	                    <i class="fa fa-calendar"></i>
	                  </div>
	                  <input type="text" class="form-control pull-right" name="bulan_akhir" placeholder="Tanggal Akhir Periode" id="datepicker4" required="">
	                </div>
	            </div>
	            <div class="col-md-3" id="verifikator">
	            	<!-- nama karyawan verifikator -->
	                <select class="form-control select2" name="verifikator" style="width: 100%;">
	                	<option value="0">-- Pilih Verifikator --</option>
	                    <?php foreach ($verifikator->result_array() as $v): ?>
	                    	<option value="<?= $c = $v['id_karyawan'] ?>"><?= $v['nama_lengkap'] ?></option>
	                    <?php endforeach ?>
                    </select>
	            </div>
	      	</div>
	      	<div class="box-footer">

	            <div class="col-md-12" align="center">
	      			<button class="btn" onclick="a()" name="cari" style=" background-color: #EDE90F; color: #122E5D; font-weight: bold; font-size: 15px; margin-top: 5px;"><i class="fa fa-send"></i><?= nbs(3) ?>Tampilkan</button><?= nbs(3) ?>
		      		<button class="btn" onclick="b()" name="all" style=" background-color: #122E5D; color: white; font-size: 15px; margin-top: 5px;"><i class="fa fa-refresh"></i><?= nbs(3) ?>Tampil Semua</button>
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

<!-- <script type='text/javascript'>
$(window).load(function(){
$("#jenis").change(function() {
			console.log($("#jenis option:selected").val());
			if ($("#jenis option:selected").val() == '4') {
				$('#verifikator').prop('hidden', 'true');
			} else {
				$('#verifikator').prop('hidden', false);
			}
		});
});
</script> -->

<script>
$(document).ready(function(){
	$('#verifikator').hide();
	$('#jenis').change(function(){
	if($('#jenis').val()=='4'){
		$('#verifikator').show();
	}else {
		$('#verifikator').hide();

	}
	});
});
</script>