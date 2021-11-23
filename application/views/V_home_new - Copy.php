<style>
    .table tr th {
        text-align: center;
        vertical-align: middle;
        font-weight: bold;
    }   
    #gbr_ver {
        display: block;
        max-width:200px;
        max-height:185px;
        width: auto;
        height: auto;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2)
    }
    div.polaroid {
        width: 80%;
        margin-bottom: 0px;
    }

    div.nama_ver {
        text-align: center;
        padding: 8px;
        margin-top: -10px;
    }
</style>

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 align-self-center">
            <h4 class="page-title">Dashboard</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>">R-Care</a></li>
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
        <div class="card border-info shadow">
                <div class="card-header bg-info">
					<h4 class="mb-0 text-white">Filter Data</h4></div>
                    <div class="card-body">
                    <div class="row">
                        <!-- periode -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 text-right">
                                        <label class="mt-2">Periode</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="input-daterange input-group" id="date-range-2">
                                            <input type="text" class="form-control" name="tgl_awal" id="start" placeholder="Tanggal Awal Periode" readonly/>
                                            <div class="input-group-append">
                                                <span class="input-group-text bg-info b-0 text-white">s / d</span>
                                            </div>
                                            <input type="text" class="form-control" name="tgl_akhir" id="end" placeholder="Tanggal Akhir Periode" readonly/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-4"></div>
                        <!-- bank -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 text-right">
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
                                    <div class="col-md-3 text-right">
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
                                    <div class="col-md-3 text-right">
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
                                    <div class="col-md-3 text-right">
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
                                    <div class="col-md-3 text-right">
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
                                    <div class="col-md-3 text-right">
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
						<button type="button" class="btn btn-sm btn-success" id="filter">Tampilkan</button><?= nbs(3) ?>
                        <button type="button" class="btn btn-sm btn-warning" id="reset">Reset</button>
					</div>
                </div>
            </div>
        </div>
    </div>

    <div id="list_awal">

        <div class="row">
            <!-- kiri -->
            <div class="col-md-4">
                <div class="card shadow bg-success">
                    <div class="card-body mb-0" data-toggle="modal" data-target="#baik" >
                        <div class="d-flex no-block align-items-center">
                            <div class="text-white">
                                <h3><?= count($data_recov_baik) ?></h3>
                                <h6>Cabang/Capem Baik</h6>
                            </div>
                            <div class="ml-auto">
                                <span class="text-white display-6"><i class="ti-thumb-up"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- modal baik -->

                    <div id="baik" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="vcenter">Data Capem Baik</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-bordered table-hover" id="tabel_ots" width="100%">
                                        <thead class="bg-info text-white">
                                            <tr>
                                                <th>No</th>
                                                <th>Cabang Bank</th>
                                                <th>Capem Bank</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no=0; foreach ($data_recov_baik as $b): $no++; ?>
                                                <tr>
                                                    <td align="center"><?= $no ?></td>
                                                    <td><?= $b['cabang_bank'] ?></td>
                                                    <td><?= $b['capem_bank'] ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>

                <!-- akhir modal baik -->
                <div class="card shadow bg-warning">
                    <div class="card-body" data-toggle="modal" data-target="#kurang">
                        <div class="d-flex no-block align-items-center">
                            <div  class="text-white">
                                <h3><?= count($data_recov_kurang) ?></h3>
                                <h6>Cabang/Capem Kurang</h6>
                            </div>
                            <div class="ml-auto">
                                <span class="text-white display-6"><i class="ti-thumb-down"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal kurang -->

                <div id="kurang" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="vcenter">Data Capem Kurang</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-bordered table-hover" id="tabel_ots" width="100%">
                                        <thead class="bg-info text-white">
                                            <tr>
                                                <th>No</th>
                                                <th>Cabang Bank</th>
                                                <th>Capem Bank</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no=0; foreach ($data_recov_kurang as $b): $no++; ?>
                                                <tr>
                                                    <td align="center"><?= $no ?></td>
                                                    <td><?= $b['cabang_bank'] ?></td>
                                                    <td><?= $b['capem_bank'] ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>

                <!-- akhir modal kurang -->
            </div>
            <!-- tengah -->
            <div class="col-md-6">
                <div class="card border-bottom border-success shadow">
                    <div class="card-body">
                        <div class="d-flex no-block align-items-center">
                            <div>
                                <h3>Rp. <?= number_format($recov_cabang_b['total_recoveries'],0,'.','.') ?></h3>
                                <h6 class="text-success">Recoveries Terbesar</h6>
                            </div>
                            <div class="ml-auto">
                                <h5><span class="text-success"><?= $recov_cabang_b['cabang_bank'] ?></span></h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-bottom border-warning shadow">
                    <div class="card-body">
                        <div class="d-flex no-block align-items-center">
                            <div>
                                <h3>Rp. <?= number_format($recov_cabang_k['total_recoveries'],0,'.','.') ?></h3>
                                <h6 class="text-warning">Recoveries Terkecil</h6>
                            </div>
                            <div class="ml-auto">
                            <h5><span class="text-warning"><?= $recov_cabang_k['cabang_bank'] ?></span></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2" align="center">
                <div class="polaroid">
                <?php if (!empty($data_ver['file_foto'])): ?>
                    <img src="<?= base_url("assets/img/pp.png") ?>" id="gbr_ver" alt="image">
                    <div class="nama_ver">
                    <p><h4><?= $data_ver['nama'] ?></h4></p>
                    </div>
                <?php else: ?>
                    <img src="<?= base_url("assets/img/user-default.png") ?>" id="gbr_ver" alt="image">
                    <div class="nama_ver">
                    <p><h4><?= $data_ver['nama'] ?></h4></p>
                    </div>
                <?php endif;?>
                </div>
            </div>

        </div>
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="card border-left border-info shadow">
                    <div class="card-body">
                        <div class="d-flex no-block align-items-center">
                            <div>
                            <h4><span class="text-info">NOA</span></h4>
                            </div>
                            <div class="ml-auto">
                                <h3 class="text-info"><?= $total_noa ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-left border-success shadow">
                    <div class="card-body">
                        <div class="d-flex no-block align-items-center">
                            <div>
                                <h4><span class="text-success">NOA Sudah OTS</span></h4>
                            </div>
                            <div class="ml-auto">
                                <h3 class="text-success"><?= $total_sdh_ots ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-left border-dark shadow">
                    <div class="card-body">
                        <div class="d-flex no-block align-items-center">
                            <div>
                                <h4><span class="text-dark">Persentase OTS</span></h4>
                            </div>
                            <div class="ml-auto">
                                
                                <?php if ($total_noa == 0): ?>
                                    <h3 class="text-dark">0.00 %</h3>
                                <?php else: ?>
                                    <h3 class="text-dark"><?php $a = ($total_sdh_ots / $total_noa) * 100; echo number_format($a, '2',',','.') ?> %</h3>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="card shadow">
                    <div class="card-header bg-info">
                        <h4 class="card-title text-white">Recoveries Bank</h4>
                    </div>
                    <div class="card-body">
                        <div id="donut-chart-bank"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-info">
                        <h4 class="card-title text-white">Chart Recoveries Bank</h4>
                    </div>
                    <div class="card-body">
                        <div id="area-chart-bank"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info shadow">
                    <div class="card-body">
                        <div class="d-flex no-block align-items-center">
                            <div class="text-white">
                                <h5>Rp. <?= number_format($tag_bank = $tagihan_bank['total_tagihan'], 2,',','.') ?></h5>
                                <h6>Tagihan Bank</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card bg-info shadow">
                    <div class="card-body">
                        <div class="d-flex no-block align-items-center">
                            <div class="text-white">
                                <h5>Rp. <?= number_format($rec_bank = $recov_bank['total_recoveries'], 2,',','.') ?></h5>
                                <h6>Recoveries Bank</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card bg-info shadow">
                    <div class="card-body">
                        <div class="d-flex no-block align-items-center">
                            <div class="text-white">
                                <?php if ($tag_bank == 0): ?>
                                    <h5>0,00 %</h5>
                                <?php else: ?>
                                    <h5><?php $persen_recov = ($rec_bank / $tag_bank) * 100; echo number_format($persen_recov, '2', ',', '.') ?> %</h5>
                                <?php endif; ?>
                                
                                <h6>% Recoveries Bank</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="card shadow">
                    <div class="card-header bg-dark">
                        <h4 class="card-title text-white">Recoveries Asuransi</h4>
                    </div>
                    <div class="card-body">
                        
                        <div id="donut-chart-asuransi"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-dark">
                        <h4 class="card-title text-white">Chart Recoveries Asuransi</h4>
                    </div>
                    <div class="card-body">
                        <div id="area-chart-asuransi"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-dark shadow">
                    <div class="card-body">
                        <div class="d-flex no-block align-items-center">
                            <div class="text-white">
                                <h5>Rp. <?= number_format($shs_as = $shs_asuransi['total_shs'], 2,',','.') ?></h5>
                                <h6>SHS Asuransi</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card bg-dark shadow">
                    <div class="card-body">
                        <div class="d-flex no-block align-items-center">
                            <div class="text-white">
                                <h5>Rp. <?= number_format($recov_as = $recov_asuransi['total_recoveries'], 2,',','.') ?></h5>
                                <h6>Recoveries Asuransi</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card bg-dark shadow">
                    <div class="card-body">
                        <div class="d-flex no-block align-items-center">
                            <div class="text-white">
                                <?php if ($shs_as == 0): ?>
                                    <h5>0,00 %</h5>
                                <?php else: ?>
                                    <h5><?php $persen_recov_as = ($recov_as / $shs_as) * 100; echo number_format($persen_recov_as, '2', ',', '.') ?> %</h5>
                                <?php endif; ?>
                                <h6>% Recoveries Asuransi</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div id="list_baru" hidden>

    </div>
</div>

<script>

$(document).ready(function () {

    $('#filter').on('click', function () {

        var asuransi           = $('#asuransi').val();
        var cabang_asuransi    = $('#cabang_asuransi').val();
        var bank               = $('#bank').val();
        var cabang_bank        = $('#cabang_bank').val();
        var capem_bank         = $('#capem_bank').val();
        var tanggal_awal       = $('#start').val();
        var tanggal_akhir      = $('#end').val();
        var verifikator        = $('#verifikator').val();
        
        $.ajax({
            url         : "<?= base_url('home/filter_home') ?>",
            type        : "POST",
            beforeSend  : function () {
                swal({
                    title   : 'Menunggu',
                    html    : 'Memproses data',
                    onOpen  : () => {
                        swal.showLoading();
                    }
                })
            },
            data        : {asuransi:asuransi, cabang_asuransi:cabang_asuransi, bank:bank, cabang_bank:cabang_bank, capem_bank:capem_bank, tanggal_awal:tanggal_awal, tanggal_akhir:tanggal_akhir, verifikator:verifikator},
            success     : function (data) {
                swal.close();

                $('#list_awal').hide();
                $('#list_baru').removeAttr('hidden');
                $('#list_baru').html(data);
            }
        })

        return false;

    })

    Morris.Donut({
        element: 'donut-chart-asuransi',
        data: [

            <?php foreach ($pie_asuransi as $p) : ?>

                {
                label: "<?= $p['cabang_asuransi'] ?>",
                value: <?= $p['total_recoveries'] ?>,
                },

            <?php endforeach ?>
            ],

        resize: true,

        <?php $a = ['#42f57e', '#f56954', '#417051', '#4500f2', '#f00e52', '#996877', '#7ae60e', '#27e60e','#b1e4f0', '#550fb8']; ?>

        <?php $warna = array(); ?>
        <?php foreach ($pie_asuransi as $wr): ?>
            <?php array_push($warna, random_element($a)) ?>
        <?php endforeach;?>

        colors: [ <?php foreach ($warna as $w): ?> '<?= $w ?>', <?php endforeach;?> ]
    });

    Morris.Donut({
        element: 'donut-chart-bank',
        data: [

            <?php foreach ($pie_bank as $p) : ?>

                {
                label: "<?= $p['cabang_bank'] ?>",
                value: <?= $p['total_recoveries'] ?>,
                },

            <?php endforeach ?>
            ],

        resize: true,

        <?php $a = ['#42f57e', '#f56954', '#417051', '#4500f2', '#f00e52', '#996877', '#7ae60e', '#27e60e','#b1e4f0', '#550fb8']; ?>

        <?php $warna = array(); ?>
        <?php foreach ($pie_bank as $wr): ?>
            <?php array_push($warna, random_element($a)) ?>
        <?php endforeach;?>

        colors: [ <?php foreach ($warna as $w): ?> '<?= $w ?>', <?php endforeach;?> ]
    });

    Morris.Area({
        element: 'area-chart-bank',
        data: [

        <?php foreach ($bulan as $b): ?>
            {
                <?php $dt = [ 'asuransi'        => 'a',
                            'cabang_asuransi'   => 'a',
                            'bank'              => 'a',
                            'cabang_bank'       => 'a',
                            'capem_bank'        => 'a',
                            'tanggal_awal'      => '',
                            'tanggal_akhir'     => '',
                            'verifikator'       => 'a',
                            'syariah'           => $this->session->userdata('level')
                            ]; ?>

                <?php $nm = $this->M_home->get_recov_bank_area($dt, $b['id'])->result_array(); ?>
                period: '<?= $b['id'] ?>',
                <?php foreach ($nm as $n): ?>
                    <?= str_replace(" ", '_', $n['cabang_bank']) ?> : <?= $n['total_recoveries'] ?> ,
                <?php endforeach; ?>
            },
            
        <?php endforeach; ?>
        
        ],
        xkey: 'period',
        ykeys: [ <?php foreach ($bulan as $b): ?>
            <?php $dt = [ 'asuransi'        => 'a',
                    'cabang_asuransi'   => 'a',
                    'bank'              => 'a',
                    'cabang_bank'       => 'a',
                    'capem_bank'        => 'a',
                    'tanggal_awal'      => '',
                    'tanggal_akhir'     => '',
                    'verifikator'       => 'a',
                    'syariah'           => $this->session->userdata('level')
                    ]; ?>

                <?php $nm = $this->M_home->get_recov_bank_area($dt, $b['id'])->result_array(); ?>
                <?php foreach ($nm as $n => $v): ?>
        '<?= str_replace(" ", '_', $v['cabang_bank']) ?>',
                <?php endforeach; ?>
            
        <?php endforeach; ?>],
        labels: [<?php foreach ($bulan as $b): ?>
            <?php $dt = [ 'asuransi'        => 'a',
                    'cabang_asuransi'   => 'a',
                    'bank'              => 'a',
                    'cabang_bank'       => 'a',
                    'capem_bank'        => 'a',
                    'tanggal_awal'      => '',
                    'tanggal_akhir'     => '',
                    'verifikator'       => 'a',
                    'syariah'           => $this->session->userdata('level')
                    ]; ?>

                <?php $nm = $this->M_home->get_recov_bank_area($dt, $b['id'])->result_array(); ?>
                <?php foreach ($nm as $n => $v): ?>
        '<?= str_replace(" ", '_', $v['cabang_bank']) ?>',
                <?php endforeach; ?>
        <?php endforeach; ?>],
        pointSize: 3,
        fillOpacity: 0,
        pointStrokeColors:['#55ce63', '#2962FF', '#2f3d4a','#2962FF', '#2f3d4a'],
        behaveLikeLine: true,
        gridLineColor: '#e0e0e0',
        lineWidth: 3,
        hideHover: 'auto',
        lineColors: ['#55ce63', '#2962FF', '#2f3d4a','#2962FF', '#2f3d4a'],
        resize: true
        
    });

    Morris.Area({
        element: 'area-chart-asuransi',
        data: [

        <?php foreach ($bulan as $b): ?>
            {
                <?php $dt = [ 'asuransi'        => 'a',
                    'cabang_asuransi'   => 'a',
                    'bank'              => 'a',
                    'cabang_bank'       => 'a',
                    'capem_bank'        => 'a',
                    'tanggal_awal'      => '',
                    'tanggal_akhir'     => '',
                    'verifikator'       => 'a',
                    'syariah'           => $this->session->userdata('level')
                    ]; ?>

                <?php $nm = $this->M_home->get_recov_asuransi_area($dt, $b['id'])->result_array(); ?>
                period: '<?= $b['id'] ?>',
                <?php foreach ($nm as $n): ?>
                    <?= str_replace(" ", '_', $n['cabang_asuransi']) ?>: <?= $n['total_recoveries'] ?> ,
                <?php endforeach; ?>
            },
            
        <?php endforeach; ?>
        
        ],
        xkey: 'period',
        ykeys: [ <?php foreach ($bulan as $b): ?>
            <?php $dt = [ 'asuransi'        => 'a',
                    'cabang_asuransi'   => 'a',
                    'bank'              => 'a',
                    'cabang_bank'       => 'a',
                    'capem_bank'        => 'a',
                    'tanggal_awal'      => '',
                    'tanggal_akhir'     => '',
                    'verifikator'       => 'a',
                    'syariah'           => $this->session->userdata('level')
                    ]; ?>

                <?php $nm = $this->M_home->get_recov_asuransi_area($dt, $b['id'])->result_array(); ?>
                <?php foreach ($nm as $n => $v): ?>
        '<?= str_replace(" ", '_', $v['cabang_asuransi']) ?>',
                <?php endforeach; ?>
            
        <?php endforeach; ?>],
        labels: [<?php foreach ($bulan as $b): ?>
            <?php $dt = [ 'asuransi'        => 'a',
                    'cabang_asuransi'   => 'a',
                    'bank'              => 'a',
                    'cabang_bank'       => 'a',
                    'capem_bank'        => 'a',
                    'tanggal_awal'      => '',
                    'tanggal_akhir'     => '',
                    'verifikator'       => 'a',
                    'syariah'           => $this->session->userdata('level')
                    ]; ?>

                <?php $nm = $this->M_home->get_recov_asuransi_area($dt, $b['id'])->result_array(); ?>
                <?php foreach ($nm as $n => $v): ?>
        '<?= str_replace(" ", '_', $v['cabang_asuransi']) ?>',
                <?php endforeach; ?>
        <?php endforeach; ?>],
        pointSize: 3,
        fillOpacity: 0,
        pointStrokeColors:['#55ce63', '#2962FF', '#2f3d4a','#2962FF', '#2f3d4a'],
        behaveLikeLine: true,
        gridLineColor: '#e0e0e0',
        lineWidth: 3,
        hideHover: 'auto',
        lineColors: ['#55ce63', '#2962FF', '#2f3d4a','#2962FF', '#2f3d4a'],
        resize: true
        
    });


    // aksi reset data filter
    $('#reset').click(function () {
        $('#asuransi').select2("val", 'a');
        $('#cabang_asuransi').select2("val",'a');
        $('#bank').select2("val",'a');
        $('#cabang_bank').select2("val",'a');
        $('#capem_bank').select2("val",'a');
        $('#date-range-2 input').datepicker('setDate', null);

        $('#list_awal').show();
        $('#list_baru').attr('hidden', true);
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