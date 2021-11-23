<style type="text/css">
	#ad thead tr th {
		vertical-align: middle;
		text-align: center;
	}

</style>

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 align-self-center">
            <h4 class="page-title">All Report</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>">Report Care</a></li>
						<li class="breadcrumb-item">Print Report</li>
						<li class="breadcrumb-item active" aria-current="page">All Report</li>
                    </ol>
                </nav>
			</div>
			<?php if ($akses == 'kanwil_asuransi') : ?>
                <a href="<?= base_url('home/index/asuransi') ?>"><button type="button" class="btn waves-effect waves-light btn-success float-right" style="margin-top: -40px;">Home</button></a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="container-fluid">

	<?php if ($akses == 'kanwil_asuransi') : ?>

		<div class="row">
			<div class="col-12">
				<div class="card shadow bg-orange text-white">
					<div class="card-body pb-0">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<div class="row">
										<div class="col-md-4">
											<label>Cabang Asuransi :</label>
										</div>
										<div class="col-md-8">
											<b><?= $nm_cabang['cabang_asuransi'] ?></b>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<div class="row">
										<div class="col-md-4">
											<label>No SPK :</label>
										</div>
										<div class="col-md-8">
											<b><?= $no_spk['no_spk'] ?></b>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<div class="row">
										<div class="col-md-4">
											<label>Tanggal Akhir :</label>
										</div>
										<div class="col-md-8">
											<b><?= nice_date($no_spk['tgl_akhir'], 'd-F-Y') ?></b>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	<?php endif; ?>

	<div class="row">
		<div class="col-md-12">
			<div class="card border-info shadow">

				<div class="card-header bg-info">
					<h4 class="mb-0 text-white">Filter Data</h4>	
				</div>

			<form method="POST" target="_blank" id="tab" action="<?= base_url('r_semua') ?>" autocomplete="off"> 

				<input type="hidden" name="id_spk" value="<?= $id_spk ?>">
				<input type="hidden" name="id_cabang_as" value="<?= $id_cabang_as2 ?>">
			  
				<div class="card-body">
					<div class="d-flex justify-content-center">
						<div class="col-md-6">
							<!-- <div class="form-group">
								<div class="row">
									<div class="col-md-3">
										<label>SPK</label>
									</div>
									<div class="col-md-9">
										<select class="select2 form-control custom-select" name="spk" id="spk" style="width: 100%; height:36px;" required="">  
											<option value="">-- Pilih SPK --</option>
											<?php foreach ($spk as $a): ?>
												<option value="<?= $a['id_spk'] ?>"><?= $a['no_spk'] ?></option>
											<?php endforeach;?>
											<option value="null">No SPK</option>
										</select>
									</div>
								</div>
							</div> -->
							<div class="form-group">
								<div class="row">
									<div class="col-md-3">
										<label>Jenis Report</label>
									</div>
									<div class="col-md-9">
										<select class="select2 form-control custom-select" id="jenis" name="jenis_report" required="" style="width: 100%; height:36px;">
											<option value="">-- Pilih Jenis Report --</option>
											<?php if ($id_cabang_as != ''): ?>
												<option value="3">Laporan Debitur yang sudah Dikunjungi</option>
												<option value="5">Laporan Recoveries</option>
												<option value="2">Laporan Validasi Agunan</option>
											<?php else : ?>
												<option value="1">Rekap OTS dan Recoveries Berdasarkan Hasil OTS Verfikator</option>
												<option value="2">Laporan Validasi Agunan</option>
												<option value="3">Laporan Debitur yang sudah Dikunjungi</option>
												<option value="4">Report Achievment Recoveries & OTS By Verfikator </option>
											<?php endif ?>

											
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-3">
										<label>Periode</label>
									</div>
									<div class="col-md-9">
										<div class="input-daterange input-group" id="date-range">
											<input type="text" class="form-control" name="start" id="start" placeholder="Awal Periode" required=""/>
											<div class="input-group-append">
												<span class="input-group-text bg-info b-0 text-white">s / d</span>
											</div>
											<input type="text" class="form-control" name="end" id="end" placeholder="Akhir Periode" required=""/>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group" id="verifikator">
								<div class="row">
									<div class="col-md-3">
										<label>Verifikator</label>
									</div>
									<div class="col-md-9">
										<select class="form-control select2" name="verifikator" id="verif" style="width: 100%;">
											<option value="">-- Pilih Verifikator --</option>
											<?php foreach ($verifikator->result_array() as $v): ?>
												<option value="<?= $c = $v['id_karyawan'] ?>"><?= $v['nama_lengkap'] ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="card-footer">

					<div class="col-md-12" align="right">
						<button type="submit" class="btn btn-success" onclick="a()" name="cari" id="cari">Tampilkan</button><?= nbs(3) ?>
						<!-- <div class="btn-group">
							<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Unduh Data
							</button>
							<div class="dropdown-menu animated flipInY">
								<a class="dropdown-item btn btn-info" type="button" target="_blank" href="<?= base_url('r_semua/unduh_data/pdf') ?>" id="unduh_pdf">PDF</a>
								<a class="dropdown-item btn btn-info" type="button" target="_blank" href="<?= base_url('r_semua/unduh_data/excel') ?>" id="unduh_excel">EXCEL</a>
							</div>
						</div><?= nbs(3) ?> -->
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

		$('#verifikator').hide();

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
			$('#jenis').select2('val', 'a');
			$('#verif').select2('val', 'a');
			$('#start').data('datepicker').setDate(null);
			$('#end').data('datepicker').setDate(null);
		})

		// aksi button cari
		$('#cari1').click(function () {
			
			var jenis 		= $('#jenis').val();
			var tgl_awal	= $('#start').val();
			var tgl_akhir 	= $('#end').val();
			var verifikator = $('#verif').val();

			if (jenis == 'a') {
				
				swal({
                    title               : "Peringatan",
                    text                : 'Jenis report pilih dahulu!',
                    buttonsStyling      : false,
                    confirmButtonClass  : "btn btn-warning",
                    type                : 'warning'
                }); 

                return false;

			} else if (tgl_awal == '') {

				swal({
                    title               : "Peringatan",
                    text                : 'Tanggal Awal Periode pilih dahulu!',
                    buttonsStyling      : false,
                    confirmButtonClass  : "btn btn-warning",
                    type                : 'warning'
                }); 

                return false;

			} else if (tgl_akhir == '') {

				swal({
                    title               : "Peringatan",
                    text                : 'Tanggal Akhir Periode pilih dahulu!',
                    buttonsStyling      : false,
                    confirmButtonClass  : "btn btn-warning",
                    type                : 'warning'
                }); 

                return false;

			} else {

				$.ajax({
                    type        : "post",
                    url         : "<?= base_url('r_semua/aksi_report') ?>",
                    beforeSend  : function () {
                        swal({
                            title   : 'Menunggu',
                            html    : 'Memproses Data',
                            onOpen  : () => {
                                swal.showLoading();
                            }
                        })
                    },
                    data        : {jenis:jenis, tgl_awal:tgl_awal, tgl_akhir:tgl_akhir, verifikator:verifikator},
                    success     : function (data) {
                        
						var url = "<?= base_url('r_semua/aksi_report') ?>";

						window.location.href = url;

                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Error');
                    }							

                    
                })
                
                return false;

			}

		})

	});

</script>