<style type="text/css">
	.table thead tr th {
		text-align: center;
        vertical-align: middle;
        font-weight: bold;
	}
</style>

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 align-self-center">
            <h4 class="page-title">Report Eks Asset</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>">Report Care</a></li>
						<li class="breadcrumb-item active" aria-current="page">Report Eks Asset</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">

    <!-- Untuk filter data -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-info">
                    <h4 class="mb-0 text-white">Filter Data</h4>
                </div>
                <form action="<?= base_url('r_eks_asset/unduh_data/lihat') ?>" target="_blank" method="POST">
                    <div class="card-body">
                        <div class="row">
                            <!-- periode -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3 ">
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
                            <div class="col-md-4"></div>
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
                            

                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="col-md-12" align="right">
                            <button type="button" class="btn btn-success" id="filter">Tampilkan</button><?= nbs(3) ?>
                            <button type="button" class="btn btn-warning" id="reset" >Reset</button><?= nbs(3) ?>
                            <button type="submit" class="btn btn-info">Export Data</button>
                        </div>
                    </div>  
                </form>
            </div>
        </div>
    </div>

    <!-- Untuk list debitur -->
    <div class="row">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-info">
					<h4 class="mb-0 text-white">List Debitur</h4>	
                </div>
                <div class="card-body table-responsive">

                    <table class="table table-bordered table-hover" id="tabel_eks_asset" width="100%">
                        <thead class="bg-info text-white">
                            <tr>
                                <th>No</th>
                                <th>Id Care</th>
                                <th>No Klaim</th>
                                <th>Nama Debitur</th>
                                <th>Capem Bank</th>
                                <th>Cabang Asuransi</th>
                                <th>Asset Status Proses</th>
                                <th>Eksekutor</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        
		<div class="col-md-2">
			<div class="card border-left border-info shadow">
				<div class="card-body">
					<div class="d-flex no-block align-items-center">
						<div>
							<span class="text-info display-6"><i class="ti-home"></i></span>
						</div>
						<div class="ml-auto">
							<h6 align="right" id="tot_jumlah_asset"><?= $tot_jumlah_asset ?> Unit</h6>
							<h6 class="text-info">Total Jumlah Asset</h6>
						</div>
					</div>
				</div>
			</div>
			<div class="card border-left border-info shadow">
				<div class="card-body">
					<div class="d-flex no-block align-items-center">
						<div>
							<span class="text-info display-6"><i class="ti-money"></i></span>
						</div>
						<div class="ml-auto">
							<h6 align="right" id="tot_hasil_penjualan">Rp. <?= number_format($tot_hasil_penjualan,0,'.','.')  ?></h6>
							<h6 align="right" id="tot_jumlah_asset_sdh" style="margin-top:20px;"><?= $tot_jumlah_asset_sdh  ?> Unit</h6>
							<h6 align="right" class="text-info">Total Hasil Penjualan</h6>
						</div>
					</div>
				</div>
			</div>
			<div class="card border-left border-info shadow">
				<div class="card-body">
					<div class="d-flex no-block align-items-center">
						<div>
							<span class="text-info display-6"><i class="ti-stats-up"></i></span>
						</div>
						<div class="ml-auto">
							<h6 align="right" id="tot_hasil_penjualan_pot">Rp. <?= number_format($tot_hasil_penjualan_pot,0,'.','.')  ?></h6>
							<h6 align="right" class="text-info">Potensi Penjualan</h6>
						</div>
					</div>
				</div>
            </div>
            
		</div>
    </div>

</div>

<script src="<?= base_url() ?>template/assets/libs/jquery/dist/jquery.min.js"></script>

<script>

$(document).ready(function () {

    var tabel_eks_asset = $('#tabel_eks_asset').DataTable({
        "processing"        : true,
        "serverSide"        : true,
        "order"             : [],
        "ajax"              : {
            "url"   : "<?= base_url("r_eks_asset/tampil_eks_asset") ?>",
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
    $('#filter').click(function () {

        var asuransi           = $('#asuransi').val();
        var cabang_asuransi    = $('#cabang_asuransi').val();
        var bank               = $('#bank').val();
        var cabang_bank        = $('#cabang_bank').val();
        var capem_bank         = $('#capem_bank').val();
        var tanggal_awal       = $('#start').val();
        var tanggal_akhir      = $('#end').val();
        var verifikator        = $('#verifikator').val();
        var spk                = $('#spk').val();

        $.ajax({
            url         : "<?= base_url('R_eks_asset/ambil_data_total') ?>",
            type        : "POST",
            data        : {asuransi:asuransi, cabang_asuransi:cabang_asuransi, bank:bank, cabang_bank:cabang_bank, capem_bank:capem_bank, tanggal_awal:tanggal_awal, tanggal_akhir:tanggal_akhir, verifikator:verifikator, spk:spk},
            dataType    : "JSON",
            success     : function (data) {
                tabel_eks_asset.ajax.reload(null, false);  

                $('#tot_jumlah_asset').html(data.tot_jumlah_asset);
                $('#tot_hasil_penjualan').html(data.tot_hasil_penjualan);
                $('#tot_jumlah_asset_sdh').html(data.tot_jumlah_asset_sdh);
                $('#tot_hasil_penjualan_pot').html(data.tot_hasil_penjualan_pot);
               
            }
        })
        
        return false;
              
    })

	// proses reset data
    $('#reset').click(function () {

        $.ajax({
            url         : "<?= base_url('R_eks_asset/ambil_data_total') ?>",
            type        : "POST",
            data        : {asuransi:'a', cabang_asuransi:'a', bank:'a', cabang_bank:'a', capem_bank:'a', tanggal_awal:'', tanggal_akhir:'', verifikator:'a', spk:'a'},
            dataType    : "JSON",
            success     : function (data) {
                
                $('#tot_jumlah_asset').html(data.tot_jumlah_asset);
                $('#tot_hasil_penjualan').html(data.tot_hasil_penjualan);
                $('#tot_jumlah_asset_sdh').html(data.tot_jumlah_asset_sdh);
                $('#tot_hasil_penjualan_pot').html(data.tot_hasil_penjualan_pot);

                $('#asuransi').select2("val", 'a');
                $('#cabang_asuransi').select2("val",'a');
                $('#bank').select2("val",'a');
                $('#cabang_bank').select2("val",'a');
                $('#capem_bank').select2("val",'a');
                $('#verifikator').select2("val",'a');
                $('#spk').select2("val",'a');
                $('#date-range-2 input').datepicker('setDate', null);

                tabel_eks_asset.ajax.reload(null, false);  
               
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
        var id_asuransi = $("#asuransi").val();

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
            data        : {id_asuransi:id_asuransi},
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
