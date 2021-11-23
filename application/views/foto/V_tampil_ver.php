<style type="text/css">
	#tabel_deb_foto thead tr th {
		text-align: center;
        vertical-align: middle;
        font-weight: bold;
	}
</style>

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 align-self-center">
            <h4 class="page-title">Foto Dokumen</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>">Report Care</a></li>
						<li class="breadcrumb-item active" aria-current="page">Foto Dokumen</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">

    <!-- Untuk filter data -->
    <div class="row form-1">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-info">
                    <h4 class="mb-0 text-white">Filter Data</h4>
                </div>
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
                        <button type="submit" class="btn btn-success" name="cari" id="cari">Tampilkan</button><?= nbs(3) ?>
                        <button type="submit" class="btn btn-warning" id="reset"  name="all">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Untuk list debitur -->
    <div class="row form-2">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-info">
					<h4 class="mb-0 text-white">Data Debitur</h4>	
                </div>
                <div class="card-body table-responsive">

                    <table class="table table-bordered table-hover" id="tabel_deb_foto" width="100%">
                        <thead class="bg-info text-white">
                            <tr>
                                <th>No</th>
                                <th>Id Care</th>
                                <th>No Klaim</th>
                                <th>Nama Debitur</th>
                                <th>Capem Bank</th>
                                <th>Cabang Asuransi</th>
                                <th>Alamat Debitur</th>
                                <th>Alamat Agunan</th>
                                <th>Sisa Hutang Bank</th>
                                <th>SHS Asuransi</th>
                                <th>Nilai Agunan</th>
                                <th>Status Agunan</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div>
    </div>

    <div id="form-detail-debitur" hidden> 
    </div>

    <div class="align-items-center col-md-2" id="kembali" hidden>
        <button class="btn btn-warning btn-round ml-auto">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </button>
    </div>

</div>

<script src="<?= base_url() ?>template/assets/libs/jquery/dist/jquery.min.js"></script>

<script>

$(document).ready(function () {

    var tabel_deb_foto = $('#tabel_deb_foto').DataTable({
        "processing"        : true,
        "serverSide"        : true,
        "order"             : [],
        "ajax"              : {
            "url"       : "<?= base_url('data/tampil_list_debitur_foto') ?>",
            "type"      : "POST",
            "data"      : function (data) {
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
        "columnDefs"    : [{
                "targets"       : [0],
                "orderable"     : false
            }]
    })

    // proses filter data
    $('#cari').click(function () {

        tabel_deb_foto.ajax.reload(null, false);
    })

    // proses reset data
    $('#reset').click(function () {
        $('#asuransi').select2("val", 'a');
        $('#cabang_asuransi').select2("val",'a');
        $('#bank').select2("val",'a');
        $('#cabang_bank').select2("val",'a');
        $('#capem_bank').select2("val",'a');
        $('#verifikator').select2("val",'a');
        $('#spk').select2("val",'a');
        $('#date-range-2 input').datepicker('setDate', null);

        tabel_deb_foto.ajax.reload(null, false);
    })

    // proses detail deb
    $('#tabel_deb_foto').on('click', '.detail-deb', function () {

        var id_debitur = $(this).data('id');

        $.ajax({
            url         : "<?= base_url('data/form_detail_deb') ?>",
            type        : "POST",
            beforeSend  : function () {
                swal({
                    title   : 'Menunggu',
                    html    : 'Memproses halaman',
                    onOpen  : () => {
                        swal.showLoading();
                    }
                })
            },
            data        : {id_debitur:id_debitur},
            success     : function (data) {
                swal.close();

                $('.form-1').hide();
                $('.form-2').hide();
                $('#form-detail-debitur').removeAttr('hidden');
                $('#form-detail-debitur').html(data);
                $('#kembali').removeAttr('hidden');

            }
        })
        
    })

    // kembali
    $('#kembali').on('click', function () {

        $.ajax({
            beforeSend  : function () {
                swal({
                    title   : 'Menunggu',
                    html    : 'Memproses halaman',
                    onOpen  : () => {
                        swal.showLoading();
                    }
                })
            },
            success     : function () {
                swal.close();

                tabel_deb_foto.ajax.reload(null, false); 

                $('.form-1').show();
                $('.form-2').show();
                $("#kembali").attr("hidden", true);
                $("#form-detail-debitur").attr("hidden", true);
            }
        })

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