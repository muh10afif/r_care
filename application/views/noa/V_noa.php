<style type="text/css">
	.table thead tr th {
		text-align: center;
        vertical-align: middle;
        font-weight: bold;
	}
    #resume tr td {
        font-weight: bold;
    }
    th { font-size: 11px; }
    td { font-size: 11px; }
</style>

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 align-self-center">
            <h4 class="page-title">Report NOA</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>">Report Care</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Report NOA</li>
                        <input type="hidden" id="id_cabang_as" value="<?= $id_cabang_as ?>">
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

    <!-- Untuk filter data -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-info">
                    <h4 class="mb-0 text-white">Filter Data</h4>
                </div>
                <form action="<?= base_url("r_noa/unduh_data/lihat/$level") ?>" target="_blank" method="POST">
                    <div class="card-body">

                    <?php if ($id_cabang_as != ''): ?>

                        <div class="row">
                            <!-- periode -->
                            <!-- <div class="col-md-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3 text-right">
                                            <label class="mt-2">Periode</label>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="input-daterange input-group" id="date-range-2">
                                                <input type="text" class="form-control" name="tgl_awal" id="start" placeholder="Awal Periode" readonly/>
                                                <div class="input-group-append">
                                                    <span class="input-group-text bg-info b-0 text-white">s / d</span>
                                                </div>
                                                <input type="text" class="form-control" name="tgl_akhir" id="end" placeholder="Akhir Periode" readonly/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4"></div> -->
                            <!-- bank -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3 ">
                                            <label class="mt-2">Bank</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select class="select2 form-control custom-select" name="bank" id="bank" style="width: 100%; height:36px;">  
                                                <option value="a">-- Pilih Bank --</option>
                                                <?php foreach ($bank as $b): ?>
                                                    <option value="<?= $b['id_bank'] ?>"><?= $b['bank'] ?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- cabang bank -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3 ">
                                            <label class="mt-2">Cabang Bank</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select class="select2 form-control custom-select" name="cabang_bank" id="cabang_bank" style="width: 100%; height:36px;">  
                                                <option value="a">-- Pilih Cabang Bank --</option>
                                                
                                            </select>
                                            <div id="loading_cab_bank" style="margin-top: 10px;" align='center'>
                                                <img src="<?= base_url('template/img/loading.gif') ?>" width="18"> <small>Loading...</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- capem bank -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3 ">
                                            <label class="mt-2">Capem Bank</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select class="select2 form-control custom-select" name="capem_bank" id="capem_bank" style="width: 100%; height:36px;">  
                                                <option value="a">-- Pilih Capem Bank --</option>
                                                
                                            </select>
                                            <div id="loading_cap_bank" style="margin-top: 10px;" align='center'>
                                                <img src="<?= base_url('template/img/loading.gif') ?>" width="18"> <small>Loading...</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- asuransi -->
                            <div class="col-md-4" hidden>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3 ">
                                            <label class="mt-2">Asuransi</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select class="select2 form-control custom-select" name="asuransi" id="asuransi" style="width: 100%; height:36px;">  
                                                <option value="a">-- Pilih Asuransi --</option>
                                                <?php foreach ($asuransi as $a): ?>
                                                    <option value="<?= $a['id_asuransi'] ?>"><?= $a['asuransi'] ?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- cabang asuransi -->
                            <div class="col-md-4" hidden>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3 ">
                                            <label class="mt-2">Cabang Asuransi</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select class="select2 form-control custom-select" name="cabang_asuransi" id="cabang_asuransi" style="width: 100%; height:36px;">  
                                                <option value="a">-- Pilih Cabang Asuransi --</option>
                                                
                                            </select>
                                            <div id="loading_cab_as" style="margin-top: 10px;" align='center'>
                                                <img src="<?= base_url('template/img/loading.gif') ?>" width="18"> <small>Loading...</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- verfikator -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3 ">
                                            <label class="mt-2">Verifikator</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select class="select2 form-control custom-select" name="verifikator" id="verifikator" style="width: 100%; height:36px;">  
                                                <option value="a">-- Pilih Verifikator --</option>
                                                <?php foreach ($verifikator as $v): ?>
                                                    <option value="<?= $v['id_karyawan'] ?>"><?= $v['nama_lengkap'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div id="loading_ver" style="margin-top: 10px;" align='center'>
                                                <img src="<?= base_url('template/img/loading.gif') ?>" width="18"> <small>Loading...</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- periode -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="mt-2">Periode</label>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" name="tanggal_akhir" id="end" placeholder="Periode" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="icon-calender"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            

                        </div>

                    <?php else : ?>

                        <div class="row">
                            <!-- periode -->
                            <!-- <div class="col-md-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3 text-right">
                                            <label class="mt-2">Periode</label>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="input-daterange input-group" id="date-range-2">
                                                <input type="text" class="form-control" name="tgl_awal" id="start" placeholder="Awal Periode" readonly/>
                                                <div class="input-group-append">
                                                    <span class="input-group-text bg-info b-0 text-white">s / d</span>
                                                </div>
                                                <input type="text" class="form-control" name="tgl_akhir" id="end" placeholder="Akhir Periode" readonly/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4"></div> -->
                            <!-- bank -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3 ">
                                            <label class="mt-2">Bank</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select class="select2 form-control custom-select" name="bank" id="bank" style="width: 100%; height:36px;">  
                                                <option value="a">-- Pilih Bank --</option>
                                                <?php foreach ($bank as $b): ?>
                                                    <option value="<?= $b['id_bank'] ?>"><?= $b['bank'] ?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- cabang bank -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3 ">
                                            <label class="mt-2">Cabang Bank</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select class="select2 form-control custom-select" name="cabang_bank" id="cabang_bank" style="width: 100%; height:36px;">  
                                                <option value="a">-- Pilih Cabang Bank --</option>
                                                
                                            </select>
                                            <div id="loading_cab_bank" style="margin-top: 10px;" align='center'>
                                                <img src="<?= base_url('template/img/loading.gif') ?>" width="18"> <small>Loading...</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- capem bank -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3 ">
                                            <label class="mt-2">Capem Bank</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select class="select2 form-control custom-select" name="capem_bank" id="capem_bank" style="width: 100%; height:36px;">  
                                                <option value="a">-- Pilih Capem Bank --</option>
                                                
                                            </select>
                                            <div id="loading_cap_bank" style="margin-top: 10px;" align='center'>
                                                <img src="<?= base_url('template/img/loading.gif') ?>" width="18"> <small>Loading...</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- asuransi -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3 ">
                                            <label class="mt-2">Asuransi</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select class="select2 form-control custom-select" name="asuransi" id="asuransi" style="width: 100%; height:36px;">  
                                                <option value="a">-- Pilih Asuransi --</option>
                                                <?php foreach ($asuransi as $a): ?>
                                                    <option value="<?= $a['id_asuransi'] ?>"><?= $a['asuransi'] ?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- cabang asuransi -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3 ">
                                            <label class="mt-2">Cabang Asuransi</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select class="select2 form-control custom-select" name="cabang_asuransi" id="cabang_asuransi" style="width: 100%; height:36px;">  
                                                <option value="a">-- Pilih Cabang Asuransi --</option>
                                                
                                            </select>
                                            <div id="loading_cab_as" style="margin-top: 10px;" align='center'>
                                                <img src="<?= base_url('template/img/loading.gif') ?>" width="18"> <small>Loading...</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- verfikator -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3 ">
                                            <label class="mt-2">Verifikator</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select class="select2 form-control custom-select" name="verifikator" id="verifikator" style="width: 100%; height:36px;">  
                                                <option value="a">-- Pilih Verifikator --</option>
                                                <?php foreach ($verifikator as $v): ?>
                                                    <option value="<?= $v['id_karyawan'] ?>"><?= $v['nama_lengkap'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div id="loading_ver" style="margin-top: 10px;" align='center'>
                                                <img src="<?= base_url('template/img/loading.gif') ?>" width="18"> <small>Loading...</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="row">
                                    <div class="col-md-3  ">
                                        <label class="mt-2">SPK</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="select2 form-control custom-select" name="spk" id="spk" style="width: 100%; height:36px;">  
                                            <option value="a">-- Pilih SPK --</option>
                                            <?php foreach ($spk as $a): ?>
                                                <option value="<?= $a['id_spk'] ?>"><?= $a['no_spk'] ?></option>
                                            <?php endforeach;?>
                                            <option value="null">No SPK</option>
                                        </select>
                                    </div>
                                </div>
                            </div>                            

                        </div>

                    <?php endif; ?>

                    </div>
                    <div class="card-footer">
                        <div class="col-md-12" align="right">
                            <button type="submit" class="btn btn-success" name="cari" id="cari">Tampilkan</button><?= nbs(3) ?>
                            <button type="submit" class="btn btn-warning" id="reset"  name="all">Reset</button><?= nbs(3) ?>
                            <button type="submit" class="btn btn-info" id="reset"  name="all">Export Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Untuk list debitur -->
    <div class="row">
        <div class="col-md-9">
            <div class="card shadow">
				<div class="card-body">
					<ul class="nav nav-tabs mt-2 mb-4">
						<li class=" nav-item"> <a href="#navpills-1" class="nav-link active" data-toggle="tab" aria-expanded="false"><h5>Report</h5></a> </li>
						<li class="nav-item"> <a href="#navpills-2" class="nav-link" data-toggle="tab" aria-expanded="false"><h5>Summary</h5></a> </li>
					</ul>

					<div class="tab-content br-n pn">
                        <div id="navpills-1" class="tab-pane active table-responsive">
							<table class="table table-sm table-bordered table-hover" id="tabel_noa" width="100%">
								<thead class="bg-info text-white">
									<tr>
										<th>No</th>
										<th>Nama Debitur</th>
										<th>No Klaim</th>
										<th>Bank</th>
										<th>Cabang Bank</th>
                                        <?php if ($id_cabang_as == '') : ?>
										<th>Asuransi</th>
                                        <?php endif; ?>
										<th>Subrogasi</th>
										<th>Recoveries</th>
										<th>SHS</th>
										<th>Status</th>
									</tr>
								</thead>
							</table>
                        </div>
                        <div id="navpills-2" class="tab-pane table-responsive">
                            <div class="p-20">
                                <table class="table table-bordered table-hover" id="tabel_noa_sum" width="100%">
                                    <thead class="bg-info text-white">
                                        <tr>
                                            <th>No</th>
                                            <th>Verifikator</th>
                                            <th>NOA Kelolaan</th>
                                            <th>SHS</th>
                                            <th>Recoveries</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
						</div>
					</div>
				</div>

            </div>
		</div>
		<div class="col-md-3">
			<div class="card border-left border-info shadow">
				<div class="card-body">
					<table class="table table-sm table-hover" width="100%" id="resume">
						<thead class="bg-info text-white">
							<tr>
								<th colspan="3">Resume</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Total NOA</td>
								<td>:</td>
								<td align="right" id="tot_noa"><?= $tot_noa ?></td>
							</tr>
							<tr>
								<td>Sudah OTS</td>
								<td>:</td>
								<td align="right" id="tot_sudah_ots"><?= $tot_sudah_ots ?></td>
							</tr>
							<tr>
								<td>Belum OTS</td>
								<td>:</td>
								<td align="right" id="tot_blm_ots"><?= $tot_blm_ots ?></td>
							</tr>
							<tr>
								<td>Potensial</td>
								<td>:</td>
								<td align="right" id="tot_potensial"><?= $tot_potensial ?></td>
							</tr>
							<tr>
								<td>Non Potensial</td>
								<td>:</td>
								<td align="right" id="tot_non_potensial"><?= $tot_non_potensial ?></td>
							</tr>
							<tr>
								<td>Total Subrogasi</td>
								<td>:</td>
								<td align="right" id="tot_subro_as"><?= $tot_subro_as ?></td>
                            </tr>
                            
                            <?php if ($id_cabang_as == '') : ?>
                            <tr>
                                <td>Total Tagihan</td>
                                <td>:</td>
                                <td align="right" id="tot_tagihan"><?= $tot_tagihan ?></td>
                            </tr>
                            <?php endif; ?>
							
							<tr>
								<td>Total Recoveries</td>
								<td>:</td>
								<td align="right" id="tot_recov"><?= $tot_recov ?></td>
							</tr>
							<tr>
								<td>Total SHS</td>
								<td>:</td>
								<td align="right" id="tot_shs"><?= $tot_shs ?></td>
                            </tr>

                            <?php if ($id_cabang_as == '') : ?>
							<tr>
								<td>Total Saldo Tagihan</td>
								<td>:</td>
								<td align="right" id="tot_saldo_tagihan"><?= $tot_saldo_tagihan ?></td>
                            </tr>
                            <?php endif; ?>
                            
						</tbody>
					</table>
				</div>
			</div>
			
		</div>
    </div>

</div>

<script src="<?= base_url() ?>template/assets/libs/jquery/dist/jquery.min.js"></script>

<script>

$(document).ready(function () {

    var tabel_noa = $('#tabel_noa').DataTable({
        "processing"        : true,
        "serverSide"        : true,
        "order"             : [],
        "ajax"              : {
            "url"   : "<?= base_url("r_noa/tampil_r_noa/$level") ?>",
            "type"  : "POST",
            "data"  : function (data) {
                data.asuransi           = $('#asuransi').val();
                data.cabang_asuransi    = $('#cabang_asuransi').val();
                data.bank               = $('#bank').val();
                data.cabang_bank        = $('#cabang_bank').val();
                data.capem_bank         = $('#capem_bank').val();
                data.tanggal_awal       = $('#start').val();
                data.tanggal_akhir      = $('#end').val();
                data.verifikator        = $('#verifikator').val();
                data.spk                = $('#spk').val();
            }
        },
        "columnDefs"        : [{

            <?php if ($id_cabang_as == '') : ?>
                "targets"   : [0,6,7,8],
                "orderable" : false
            <?php else : ?>
                "targets"   : [0,5,6,7],
                "orderable" : false
            <?php endif ?>

        }],
        "pageLength"    : 50

    })

    var tabel_noa_sum = $('#tabel_noa_sum').DataTable({
        "processing"        : true,
        "serverSide"        : true,
        "order"             : [],
        "ajax"              : {
            "url"   : "<?= base_url("r_noa/tampil_r_noa_as/$level") ?>",
            "type"  : "POST",
            "data"  : function (data) {
                data.asuransi           = $('#asuransi').val();
                data.cabang_asuransi    = $('#cabang_asuransi').val();
                data.bank               = $('#bank').val();
                data.cabang_bank        = $('#cabang_bank').val();
                data.capem_bank         = $('#capem_bank').val();
                data.tanggal_awal       = $('#start').val();
                data.tanggal_akhir      = $('#end').val();
                data.verifikator        = $('#verifikator').val();
                data.spk                = $('#spk').val();
            }
        },
        "columnDefs"        : [{
            "targets"   : [0],
            "orderable" : false
        }]

    })

	// aksi filter data
    $('#cari').click(function () {

        var asuransi           = $('#asuransi').val();
        var cabang_asuransi    = $('#cabang_asuransi').val();
        var bank               = $('#bank').val();
        var cabang_bank        = $('#cabang_bank').val();
        var capem_bank         = $('#capem_bank').val();
        var tanggal_awal       = $('#start').val();
        var tanggal_akhir      = $('#end').val();
        var verifikator        = $('#verifikator').val();
        var spk                 = $('#spk').val();

        $.ajax({
            url         : "<?= base_url('r_noa/ambil_data_total') ?>",
            type        : "POST",
            data        : {asuransi:asuransi, cabang_asuransi:cabang_asuransi, bank:bank, cabang_bank:cabang_bank, capem_bank:capem_bank, tanggal_awal:tanggal_awal, tanggal_akhir:tanggal_akhir, verifikator:verifikator, spk:spk},
            dataType    : "JSON",
            success     : function (data) {
                tabel_noa.ajax.reload(null, false); 
                tabel_noa_sum.ajax.reload(null, false);

                $('#tot_noa').html(data.tot_noa);
                $('#tot_sudah_ots').html(data.tot_sudah_ots);
                $('#tot_blm_ots').html(data.tot_blm_ots);
                $('#tot_potensial').html(data.tot_potensial);
                $('#tot_non_potensial').html(data.tot_non_potensial);
                $('#tot_subro_as').html(data.tot_subro_as);
                $('#tot_tagihan').html(data.tot_tagihan);
                $('#tot_recov').html(data.tot_recov);
                $('#tot_shs').html(data.tot_shs);
                $('#tot_saldo_tagihan').html(data.tot_saldo_tagihan);
            
            }
        })

        return false;
            
    })

    // proses reset data
    $('#reset').click(function () {

        $.ajax({
            url         : "<?= base_url('r_noa/ambil_data_total') ?>",
            type        : "POST",
            data        : {asuransi:'a', cabang_asuransi:'a', bank:'a', cabang_bank:'a', capem_bank:'a', tanggal_awal:'', tanggal_akhir:'', verifikator:'a', spk:'a'},
            dataType    : "JSON",
            success     : function (data) {
                
                $('#tot_noa').html(data.tot_noa);
                $('#tot_sudah_ots').html(data.tot_sudah_ots);
                $('#tot_blm_ots').html(data.tot_blm_ots);
                $('#tot_potensial').html(data.tot_potensial);
                $('#tot_non_potensial').html(data.tot_non_potensial);
                $('#tot_subro_as').html(data.tot_subro_as);
                $('#tot_tagihan').html(data.tot_tagihan);
                $('#tot_recov').html(data.tot_recov);
                $('#tot_shs').html(data.tot_shs);
                $('#tot_saldo_tagihan').html(data.tot_saldo_tagihan);

                $('#asuransi').select2("val", 'a');
                $('#cabang_asuransi').select2("val",'a');
                $('#bank').select2("val",'a');
                $('#cabang_bank').select2("val",'a');
                $('#capem_bank').select2("val",'a');
                $('#verifikator').select2("val",'a');
                $('#spk').select2("val",'a');
                $('#end').datepicker('setDate', null);
                $('#date-range-2 input').datepicker('setDate', null);

                tabel_noa.ajax.reload(null, false); 
                tabel_noa_sum.ajax.reload(null, false);
            
            }
        })

        return false;

    })


	// linked combobox
    $('#loading_cab_as').hide();
    $('#loading_cab_bank').hide();
    $('#loading_cap_bank').hide();
    $('#loading_ver').hide();

    $('#asuransi').on('change', function () {
        var id_asuransi  = $("#asuransi").val();
        var id_cabang_as = '<?= $id_cabang_as ?>';

        $('#cabang_asuransi').next('.select2-container').hide();
        $('#loading_cab_as').show();

        $.ajax({
            url         : "<?= base_url('home/ambil_cabang_asuransi') ?>",
            type        : "POST",
            beforeSend 	: function (e) {
                if (e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charshet=UTF-8");
                }				
            },
            data        : {id_asuransi:id_asuransi, id_cabang_as:id_cabang_as},
            dataType    : "JSON",
            success     : function (data) {
                $('#loading_cab_as').hide();
                $('#cabang_asuransi').next('.select2-container').show();
                $('#cabang_asuransi').html(data.cabang_as);
            },
            error 		: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    })

    $('#bank').change(function () {
        var id_bank = $(this).find('option:selected').val();

        $('#cabang_bank').next('.select2-container').hide();
        $('#loading_cab_bank').show();

        $.ajax({
            url         : "<?= base_url('home/ambil_cabang_bank') ?>",
            type        : "POST",
            beforeSend 	: function (e) {
                if (e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charshet=UTF-8");
                }				
            },
            data        : {id_bank:id_bank},
            dataType    : "JSON",
            success     : function (data) {
                $('#loading_cab_bank').hide();
                $('#cabang_bank').next('.select2-container').show();
                $('#cabang_bank').html(data.cabang_bank);

                $('#capem_bank').html(data.option1);
            },
            error 		: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    })

    // mencari capem bank
    $('#cabang_bank').change(function () {
        var id_cabang_bank = $(this).find('option:selected').val();

        $('#capem_bank').next('.select2-container').hide();
        $('#loading_cap_bank').show();

        $.ajax({
            url         : "<?= base_url('home/ambil_capem_bank') ?>",
            type        : "POST",
            beforeSend  : function (e) {
                if (e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charshet=UTF-8");
                }
            },
            data        : {id_cabang_bank:id_cabang_bank},
            dataType    : "JSON",
            success     : function (data) {
                $('#loading_cap_bank').hide();
                $('#capem_bank').next('.select2-container').show();
                $('#capem_bank').html(data.capem_bank);
            },
            error 		: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    })

    // mencari verfikator
    $('#capem_bank').change(function () {
        var id_capem_bank = $(this).find('option:selected').val();

        $('#verifikator').next('.select2-container').hide();
        $('#loading_ver').show();

        $.ajax({
            url         : "<?= base_url('home/ambil_verifikator') ?>",
            type        : "POST",
            beforeSend  : function (e) {
                if (e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charshet=UTF-8");
                }
            },
            data        : {id_capem_bank:id_capem_bank},
            dataType    : "JSON",
            success     : function (data) {
                $('#loading_ver').hide();
                $('#verifikator').next('.select2-container').show();
                $('#verifikator').html(data.ver);
            },
            error 		: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
        
    })
	
})

</script>