<style type="text/css">
	#ad thead tr th {
		vertical-align: middle;
		text-align: center;
	}

</style>

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 align-self-center">
            <h4 class="page-title">Laporan Keuangan</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>">Report Care</a></li>
                        <li class="breadcrumb-item">Print Report</li>
						<li class="breadcrumb-item active" aria-current="page">Laporan Keuangan</li>
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

			<form method="POST" target="_blank" id="tab" action="<?= base_url('r_laporan') ?>" autocomplete="off"> 
			  
				<div class="card-body">
					<div class="d-flex justify-content-center">
						<div class="col-md-6">
							<div class="form-group">
								<div class="row">
									<div class="col-md-3">
										<label>Jenis Laporan</label>
									</div>
									<div class="col-md-9">
										<select class="select2 form-control custom-select" id="jenis" name="jenis_laporan" required="" style="width: 100%; height:36px;">
											<option value="">-- Pilih Laporan Keuangan --</option>
                                            <option value="1">Laporan biaya berdasarkan Invoice yang ditagihkan</option>
                                            <option value="2">Laporan biaya berdasarkan CASH IN</option>
                                            <option value="3">Rekap Outstanding Potensi</option>
                                            <option value="4">Rekap Pengeluaran Verifikator</option>
                                            <option value="5">Rekap Pengeluaran SPS</option>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group periode_bulan">
								<div class="row">
									<div class="col-md-3">
										<label>Periode</label>
									</div>
									<div class="col-md-9">
										<div class="input-daterange input-group" id="date-range">
											<input type="text" class="form-control" name="start" id="start" placeholder="Awal Periode"/>
											<div class="input-group-append">
												<span class="input-group-text bg-info b-0 text-white">s / d</span>
											</div>
											<input type="text" class="form-control" name="end" id="end" placeholder="Akhir Periode"/>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group periode_tanggal" hidden>
								<div class="row">
									<div class="col-md-3">
										<label>Periode</label>
									</div>
									<div class="col-md-9">
										<div class="input-daterange input-group" id="date-range-2">
											<input type="text" class="form-control" name="tgl_awal" id="tgl_awal" placeholder="Awal Periode"/>
											<div class="input-group-append">
												<span class="input-group-text bg-info b-0 text-white">s / d</span>
											</div>
											<input type="text" class="form-control" name="tgl_akhir" id="tgl_akhir" placeholder="Akhir Periode"/>
										</div>
									</div>
								</div>
							</div>
							
						</div>
					</div>
				</div>

				<div class="card-footer">

					<div class="col-md-12" align="right">
						<button type="submit" class="btn btn-success mr-2" onclick="a()" name="cari" id="cari">Tampilkan</button>
						<button class="btn btn-warning" id="reset" type="button">Reset</button>
					</div>

				</div>
			</form>
      		</div>
		</div>
	</div>

	<div id="isi_report" hidden>
		
	</div>

</div>

<!-- jQuery 3 -->
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

	$(document).ready(function(){

		// untuk hilangkan field verifikator jika bukan id jenis report 4
		$('#jenis').change(function(){
			if($('#jenis').val() == '4'){
				$('#verifikator').show();
				$('#verif').attr('required', true);
			}else {
				$('#verifikator').hide();
				$('#verif').removeAttr('required');
			}
		});

		// untuk mereset data
		$('#reset').click(function () {
			$('#jenis').select2('val', " ");
			$('#start').data('datepicker').setDate(null);
			$('#end').data('datepicker').setDate(null);
		})

		$('#jenis').on('change', function () {
			var jenis = $(this).val();

			if (jenis == 5 || jenis == 4) {
				$('.periode_bulan').hide();
				$('.periode_tanggal').removeAttr('hidden');
				$('#tgl_awal').attr('required', true);
				$('#tgl_akhir').attr('required', true);
				$('#start').removeAttr('required');
				$('#end').removeAttr('required');
			} else {
				$('.periode_tanggal').attr('hidden', true);
				$('.periode_bulan').show();
				$('#start').attr('required', true);
				$('#end').attr('required', true);
				$('#tgl_awal').removeAttr('required');
				$('#tgl_akhir').removeAttr('required');
			}
		})

	});

</script>