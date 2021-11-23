<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 align-self-center">
            <h4 class="page-title">Dokumen Upload</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>">Report Care</a></li>
						<li class="breadcrumb-item">Print Report</li>
						<li class="breadcrumb-item active" aria-current="page">Dokumen Upload</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">

	<div class="row">
		<div class="col-md-12">
			<div class="card border-info shadow">
				<div class="card-header bg-info">
					<h4 class="mb-0 text-white">Filter Data</h4>	
				</div>

				<form method="POST" action="<?= base_url('r_print_report/dokumen_upload') ?>" autocomplete="off">

					<div class="card-body">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<div class="row">
										<div class="col-md-3 text-right">
											<label class="mt-2">Bank</label>
										</div>
										<div class="col-md-9">
											<select class="form-control select2" name="bank" style="width: 100%;">
												<option value="">-- Pilih Bank --</option>
												<?php foreach ($bank->result_array() as $b): ?>
													<option value="<?= $b['bank'] ?>"><?= $b['bank'] ?></option>
												<?php endforeach ?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<div class="row">
										<div class="col-md-3 text-right">
											<label class="mt-2">Asuransi</label>
										</div>
										<div class="col-md-9">
											<select class="form-control select2" name="asuransi" style="width: 100%;">  
												<option value="">-- Pilih Asuransi --</option>
												<?php foreach ($asuransi->result_array() as $p): ?>
													<option value="<?= $p['asuransi'] ?>"><?= $p['asuransi'] ?></option>
												<?php endforeach ?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<div class="row">
										<div class="col-md-3 text-right">
											<label class="mt-2">Periode</label>
										</div>
										<div class="col-md-9">
											<div class="input-daterange input-group" id="date-range-2">
												<input type="text" class="form-control" name="tgl_awal" id="tgl_awal" placeholder="Awal Periode" readonly/>
												<div class="input-group-append">
													<span class="input-group-text bg-info b-0 text-white">s / d</span>
												</div>
												<input type="text" class="form-control" name="tgl_akhir" id="tgl_akhir" placeholder="Akhir Periode" readonly/>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="col-md-12" align="right">
							<button type="submit" class="btn btn-success" name="cari" id="cari">Tampilkan</button><?= nbs(3) ?>
							<button type="submit" class="btn btn-warning" id="reset"  name="all">Reset</button>
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card border-info shadow">
				<div class="card-header bg-info">
					<h4 class="mb-0 text-white">Data Debitur</h4>	
				</div>

				<form method="POST" target="_blank" id="tab" action="<?= base_url('r_print_report/print_dokumen_upload') ?>">
					<div class="card-body">
						<?php if (isset($_POST['cari'])): ?>
							<?= $this->session->flashdata('pesan'); ?>
						<?php endif ?>
						<div class="row">
							<div class="form-group col-md-5">
								<label>Nama Debitur</label>
								<select class="form-control select2" name="debitur" id="debitur" style="width: 100%; height:36px;">
									<option value="">-- Pilih Debitur --</option>
									<?php foreach ($data_dokumen as $d): ?>
										<option value="<?= $d['id_debitur'] ?>"><?= $d['nama_debitur'] ?></option>
									<?php endforeach ?>
								</select>
							</div>
							
							<div class="form-group col-md-5">
								<label>No Klaim</label>
								<select class="form-control select2" name="no_klaim" id="no_klaim" style="width: 100%; height:36px;">
									<option value="">-- Pilih No Klaim --</option>
								</select>
								
								<div id="loading" style="margin-top: 10px">
									<img src="<?= base_url('template/img/loading.gif') ?>" width="18"> <small>Loading...</small>
								</div>
							</div>

							<div class="form-group col-md-2 mt-4" align="center">
								<button class="btn btn-success" onclick="a()" name="preview" type="submit">Preview</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

</div>

<script src="<?= base_url() ?>template/assets/libs/jquery/dist/jquery.min.js"></script>

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

<script>

$(document).ready(function () {
	
	$('#loading').hide();

	$('#debitur').change(function () {
		var tgl_awal 	= $('[name="tgl_awal"]').val();
		var tgl_akhir	= $('[name="tgl_akhir"]').val();
		var asuransi 	= $('[name="asuransi"]').val();
		var bank 		= $('[name="bank"]').val();
		var id_debitur 	= $('[name="debitur"]').val();

		$('#no_klaim').next('.select2-container').hide();
		$('#loading').show();

		$.ajax({
			type 		: "POST",
			url 		: "<?= base_url('r_print_report/list_no_klaim_2') ?>",
			data 		: {id_debitur:id_debitur, tgl_awal:tgl_awal, tgl_akhir:tgl_akhir, asuransi:asuransi, bank:bank},
			beforeSend 	: function (e) {
				if (e && e.overrideMimeType) {
					e.overrideMimeType("application/json; charshet=UTF-8");
				}				
			},
			success 	: function (data) {
				$('#loading').hide();

				$('#no_klaim').html(data.list_noklaim);

				$('#no_klaim').next('.select2-container').show();
			},
			error 		: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);				
			}

		})

		return false;
	})

})

</script>

     