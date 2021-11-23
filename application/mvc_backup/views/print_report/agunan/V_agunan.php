<section class="content-header">
        <h1>
          Agunan / Lain-lain </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i>Report Care</a></li>
        <li class="active"><a href="#">Print Report</a></li>
        <li class="active"><a href="#">Agunan / Lain-lain</a></li>
        </ol>
      </section>

      <!-- Main content -->

<section class="content">
  
	<div class="row">

    <div class="col-md-12">

		<div class="box box-primary box-solid">
			<div class="box-header with-border"  style="background-color: #122E5D; color: white; ">
                  <h1 class="box-title" style="font-size: 15px;">Filter Data</h1>
             </div>

			<form method="POST" target="_self" id="tab" action="<?= base_url('r_print_report/agunan') ?>">
      		<div class="box-body text-center" style="background-color: white;">
      			<div class="form-group col-md-3">
	                <div class="input-group date">
	                  <div class="input-group-addon">
	                    <i class="fa fa-calendar"></i>
	                  </div>
	                  <input type="text" class="form-control pull-right" name="tgl_awal" placeholder="Tanggal Awal Periode" id="datepicker">
	                </div>
	            </div>
	            <div class="form-group col-md-3">
	                <div class="input-group date">
	                  <div class="input-group-addon">
	                    <i class="fa fa-calendar"></i>
	                  </div>
	                  <input type="text" class="form-control pull-right" name="tgl_akhir" placeholder="Tanggal Akhir Periode" id="datepicker2">
	                </div>
	            </div>
	            <div class="col-md-3">
	            	<!-- capem bank -->
	                <select class="form-control select2" name="asuransi" style="width: 100%;">  
	                	<option value="">-- Pilih Asuransi --</option>
	                	<?php foreach ($asuransi->result_array() as $p): ?>
	                		<option value="<?= $p['asuransi'] ?>"><?= $p['asuransi'] ?></option>
	                	<?php endforeach ?>
                    </select>
	            </div>
	            <div class="col-md-3">
	            	<!-- nama karyawan verifikator -->
	                <select class="form-control select2" name="bank" style="width: 100%;">
	                	<option value="">-- Pilih Bank --</option>
	                	<?php foreach ($bank->result_array() as $b): ?>
	                		<option value="<?= $b['bank'] ?>"><?= $b['bank'] ?></option>
	                	<?php endforeach ?>
                    </select>
	            </div>
	      	</div>
	      	<div class="box-footer">
	      		<div class="col-md-12" align="right">
	      			<button class="btn" onclick="b()" name="cari" style=" background-color: #EDE90F; color: #122E5D; font-weight: bold; font-size: 15px;">Tampilkan</button><?= nbs(8) ?>
		      		<button class="btn" onclick="b()" name="all" style=" background-color: #122E5D; color: white; font-size: 15px;">Tampil Semua</button>
		      		</div>
	      	</div>
	      	</form>
      		
      	</div>
     </div>
    </div>

    <div class="row">
        <div class="col-md-12">
          <div class="box box-primary box-solid">
            <div class="box-header with-border"  style="background-color: #122E5D; color: white; ">
                  <h1 class="box-title" style="font-size: 15px;">Dokumen Upload</h1>
            </div>
              
              <form method="POST" target="_blank" id="tab" action="<?= base_url('r_print_report/print_agunan') ?>">
                <div class="box-body text-center" style="background-color: white;">
                  <?php if (isset($_POST['cari'])): ?>
                    <?= $this->session->flashdata('pesan'); ?>
                  <?php endif ?>
                    <div class="form-group col-md-5">
                        <select class="form-control select2" name="debitur">
                            <?php foreach ($data_agunan as $d): ?>
                                <option value="<?= $d['nama_debitur'] ?>"><?= $d['nama_debitur'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group col-md-5">
                        <select class="form-control select2" name="no_klaim">
                            <?php foreach ($data_agunan as $d): ?>
                                <option value="<?= $d['no_klaim'] ?>"><?= $d['no_klaim'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <button class="btn btn-success" onclick="a()" name="preview" type="submit">Preview</button>
                    </div>
                </div>
              </form>
          
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

     