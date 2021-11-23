<style>
    #tabel_ots tr th {
        text-align: center;
        vertical-align: middle;
        font-weight: bold;
    }   
    #tabel_ots_summary tr th {
        text-align: center;
        vertical-align: middle;
        font-weight: bold;
    } 
</style>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 align-self-center">
            <h4 class="page-title">Report OTS</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>">R-Care</a></li>
                        <li class="breadcrumb-item"><a href="#">R-OTS</a></li>
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
        <div class="col-12">
        <div class="card border-info shadow">
                <div class="card-header bg-info">
                    <h4 class="mb-0 text-white">Filter Data</h4></div>
                <form action="<?= base_url("r_ots/unduh_data/lihat/$level") ?>" method="POST" target="_blank">  
                    <div class="card-body">

                    <?php if ($id_cabang_as != ''): ?>

                        <div class="row">
                            
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
                            <!-- periode -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="mt-2">Status</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select class="select2 form-control custom-select" name="status" id="status" style="width: 100%; height:36px;">  
                                                <option value="a">-- Pilih Status --</option>
                                                <option value="1">Potensial</option>
                                                <option value="0">Non Potensial</option>
                                            </select>
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

                    <?php else : ?>

                        <div class="row">
                            <!-- periode -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="mt-2">Status</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select class="select2 form-control custom-select" name="status" id="status" style="width: 100%; height:36px;">  
                                                <option value="a">-- Pilih Status --</option>
                                                <option value="1">Potensial</option>
                                                <option value="0">Non Potensial</option>
                                            </select>
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

                    <?php endif; ?>
                    
                    </div>
                    <div class="card-footer">
                        <div class="col-md-12" align="right">
                            <button type="button" class="btn btn-success" id="filter">Tampilkan</button><?= nbs(3) ?>
                            <button type="button" class="btn btn-warning" id="reset">Reset</button><?= nbs(3) ?>
                            <button class="btn btn-info" id="unduh">Export Data</button>
                        </div>
                    </div>
                 </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">

                    <ul class="nav nav-tabs mt-2 mb-4">
						<li class=" nav-item"> <a href="#navpills-1" class="nav-link active" data-toggle="tab" aria-expanded="false"><h5>Report</h5></a> </li>
						<li class="nav-item"> <a href="#navpills-2" class="nav-link" data-toggle="tab" aria-expanded="false"><h5>Summary</h5></a> </li>
                    </ul>
                    
                    <div class="tab-content br-n pn">
                        <div id="navpills-1" class="tab-pane active table-responsive">
                            <table class="table table-bordered table-hover" id="tabel_ots" width="100%">
                                <thead class="bg-info text-white">
                                        <tr>
                                            <th>No</th>
                                            <th width="20%">Nama Debitur</th>
                                            <th>No Klaim</th>
                                            <th>Bank</th>
                                            <th>Cabang Bank</th>
                                            <th>Capem Bank</th>
                                            <th>Alamat</th>
                                            <?php if ($id_cabang_as == ''): ?>
                                            <th>Asuransi</th>
                                            <?php endif; ?>
                                            <th>Hasil</th>
                                            <th>Status</th>
                                        </tr>
                                </thead>
                            </table>
                        </div>
                        <div id="navpills-2" class="tab-pane table-responsive">
                            <div class="p-20">
                                <table class="table table-bordered table-hover" id="tabel_ots_summary" width="100%">
                                    <thead class="bg-info text-white">
                                        <tr>
                                            <th>No</th>
                                            <th>Verifikator</th>
                                            <th>NOA Kelolaan</th>
                                            <th>Jumlah OTS</th>
                                            <th>% OTS</th>
                                            <th>Bayar Bank / Recoveries Aksrindo</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
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

        var tabel_ots_summary = $('#tabel_ots_summary').DataTable({
            "processing"    : true,
            "serverSide"    : true,
            "order"         : [],
            "ajax"          : {
                "url"   : "<?= base_url("r_ots/tampil_data_ots_summary/$level") ?>",
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
                    data.status             = $('#status').val();
                    data.spk                = $('#spk').val();
                }
            },
            "columnDefs"    : [{
                "targets"       : [0],
                "orderable"     : false
            }]
        })

        // dataTables ots
        var tabel_ots = $('#tabel_ots').DataTable({
            "processing"        : true,
            "serverSide"        : true,
            "order"             : [],
            "ajax"              : {
                "url"   : "<?= base_url("r_ots/tampil_data_ots/$level") ?>",
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
                    data.status             = $('#status').val();
                    data.spk                = $('#spk').val();
                }
            },
            "columnDefs"        : [{
                "targets"   : [0],
                "orderable" : false
            }],
            "pageLength"    : 50

        })

        // aksi filter data
        $('#filter').click(function () {
            tabel_ots.ajax.reload(null, false);     
            tabel_ots_summary.ajax.reload(null, false);       
        })

        // aksi reset data filter
        $('#reset').click(function () {
            $('#asuransi').select2("val", 'a');
            $('#cabang_asuransi').select2("val",'a');
            $('#bank').select2("val",'a');
            $('#cabang_bank').select2("val",'a');
            $('#capem_bank').select2("val",'a');
            $('#verifikator').select2("val",'a');
            $('#status').select2("val",'a');
            $('#spk').select2("val",'a');
            $('#date-range-2 input').datepicker('setDate', null);

            tabel_ots.ajax.reload(null, false);
            tabel_ots_summary.ajax.reload(null, false);   
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