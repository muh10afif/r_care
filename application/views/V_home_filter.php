<div class="row">
    <!-- kiri -->
    <div class="col-md-4">
        <div class="card shadow bg-success">
            <div class="card-body mb-0" data-toggle="modal" data-target="#baik1" >
                <div class="d-flex no-block align-items-center">
                    <div class="text-white">
                        <h3>Cabang/Capem Baik</h3>
                    </div>
                    <div class="ml-auto">
                        <span class="text-white display-6"><i class="ti-thumb-up"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal baik -->

            <div id="baik1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
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
            <div class="card-body" data-toggle="modal" data-target="#kurang1">
                <div class="d-flex no-block align-items-center">
                    <div  class="text-white">
                        <h3>Cabang/Capem Kurang</h3>
                    </div>
                    <div class="ml-auto">
                        <span class="text-white display-6"><i class="ti-thumb-down"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- modal kurang -->

        <div id="kurang1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
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
                        <h3>Rp. <?= number_format($recov_cabang_b['tot_nominal_as'],0,'.','.') ?></h3>
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
                        <h3>Rp. <?= number_format($recov_cabang_k['tot_nominal_as'],0,'.','.') ?></h3>
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
                            <h5>0,00 %</h5>
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
            <div class="card shadow" style="height: 100%">
                <div class="card-header bg-info">
                    <h4 class="card-title text-white">Recoveries Bank</h4>
                </div>
                <div class="card-body">
                    <div id="donut-chart-bank2"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 crt_bank" <?= ($id_cabang_as != '') ? 'hidden' : '' ?>>
            <div class="card shadow" style="height: 100%">
                <div class="card-header bg-info">
                    <h4 class="card-title text-white">Chart Recoveries Bank</h4>
                </div>
                <div class="card-body">
                    <div id="area-chart-bank2"></div>

                    <!-- <div>
                        <canvas id="line-chart" height="150"></canvas>
                    </div> -->
                </div>
            </div>
        </div>
        <div class="col-md-3 crt_bank" <?= ($id_cabang_as != '') ? 'hidden' : '' ?>>
            <div class="card bg-info shadow">
                <div class="card-body">
                    <div class="d-flex no-block align-items-center">
                        <div class="text-white">
                            <h5>Rp. <?= number_format($tag_bank = $tagihan_bank, 2,',','.') ?></h5>
                            <h6>Tagihan Bank</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card bg-info shadow">
                <div class="card-body">
                    <div class="d-flex no-block align-items-center">
                        <div class="text-white">
                            <h5>Rp. <?= number_format($rec_bank = $recov_bank, 2,',','.') ?></h5>
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

        <!-- menampilkan resume -->
        <div class="col-md-9" <?= ($id_cabang_as != '') ? '' : 'hidden' ?>>
            <div class="card border-info shadow" style="height: 100%">
                <div class="card-header bg-info">
                    <h4 class="card-title text-white">Resume</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-sm table-hover" width="100%" id="resume">
                        <tbody>
                            <tr>
                                <td width='20%'>Potensial</td>
                                <td>:</td>
                                <td align="right" id="tot_potensial"><?= $tot_potensial ?></td>
                            </tr>
                            <tr>
                                <td colspan="3"><br></td>
                            </tr>
                            <tr>
                                <td width='20%'>Non Potensial</td>
                                <td>:</td>
                                <td align="right" id="tot_non_potensial"><?= $tot_non_potensial ?></td>
                            </tr>
                            <tr>
                                <td colspan="3"><br></td>
                            </tr>
                            <tr>
                                <td width='20%'>Total Subrogasi</td>
                                <td>:</td>
                                <td align="right" id="tot_subro_as"><?= $tot_subro_as ?></td>
                            </tr>
                            <tr>
                                <td colspan="3"><br></td>
                            </tr>
                            <tr>
                                <td width='20%'>Total Recoveries</td>
                                <td>:</td>
                                <td align="right" id="tot_recov"><?= $tot_recov ?></td>
                            </tr>
                            <tr>
                                <td colspan="3"><br></td>
                            </tr>
                            <tr>
                                <td width='20%'>Total SHS</td>
                                <td>:</td>
                                <td align="right" id="tot_shs"><?= $tot_shs ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
            
    </div>

    <!-- no spk 

    // asuransi 
    // cab asuransi 

    tgl mulai 
    tgl akhir

    status

    halaman report 

    no spk 

    tgl mulai 
    tgl akhir -->

<div class="row mt-3">
    <div class="col-lg-3">
        <div class="card shadow" style="height: 100%">
            <div class="card-header bg-dark">
                <h4 class="card-title text-white">Recoveries Asuransi</h4>
            </div>
            <div class="card-body">
                
                <div id="donut-chart-asuransi2"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow" style="height: 100%">
            <div class="card-header bg-dark">
                <h4 class="card-title text-white">Chart Recoveries Asuransi</h4>
            </div>
            <div class="card-body">
                <div id="area-chart-asuransi2"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-dark shadow">
            <div class="card-body">
                <div class="d-flex no-block align-items-center">
                    <div class="text-white">
                        <h5>Rp. <?= number_format($shs_as = $shs_asuransi, 2,',','.') ?></h5>
                        <h6>SHS Asuransi</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="card bg-dark shadow">
            <div class="card-body">
                <div class="d-flex no-block align-items-center">
                    <div class="text-white">
                        <h5>Rp. <?= number_format($recov_as = $recov_asuransi , 2,',','.') ?></h5>
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


<!-- Select2 -->
<script src="<?= base_url() ?>template/assets/libs/select2/dist/js/select2.full.min.js"></script>

<script src="<?= base_url() ?>template/assets/libs/select2/dist/js/select2.min.js"></script>

<script>

$(document).ready(function () {

    Morris.Donut({
        element: 'donut-chart-asuransi2',
        data: [

            <?php foreach ($pie_asuransi as $p) : 
                
                $str    = strpos($p['total_recoveries'],".");

                $str2   = substr($p['total_recoveries'],$str+1,2);

                $str3   = substr($p['total_recoveries'],0,$str+1);

                $str5   = substr($str3,-1,1);
                
                $str4   = $str3.$str2;

                ?>

                {
                label: "<?= $p['cabang_asuransi'] ?>",
                value: <?= $str4 ?>,
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
        element: 'donut-chart-bank2',
        data: [

            <?php foreach ($pie_bank as $p) :
                
                $str    = strpos($p['total_recoveries'],".");

                $str2   = substr($p['total_recoveries'],$str+1,2);

                $str3   = substr($p['total_recoveries'],0,$str+1);

                $str4   = $str3.$str2;
                
                ?>

                {
                label: "<?= $p['cabang_bank'] ?>",
                value: <?= $str4 ?>,
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
        element: 'area-chart-bank2',
        data: [

        <?php foreach ($bulan as $b): ?>
            {
                <?php $dt = [ 'asuransi'        => $asuransi,
                            'cabang_asuransi'   => $cabang_asuransi,
                            'bank'              => $bank,
                            'cabang_bank'       => $cabang_bank,
                            'capem_bank'        => $capem_bank,
                            'tanggal_awal'      => $tanggal_awal,
                            'tanggal_akhir'     => $tanggal_akhir,
                            'verifikator'       => $verifikator,
                            'level'             => $level,
                            'spk'               => $spk_manager
                            ]; ?>

                <?php $nm = $this->M_home->get_recov_bank_area($dt, $b['id'])->result_array(); ?>
                period: '<?= $b['id'] ?>',

                <?php $tot =0; foreach ($nm as $n): 
                        $tot += $n['total_recoveries'];
                    ?>
                    
                <?php endforeach; ?>

                <?php 
                
                    $str    = strpos($tot,".");

                    $str2   = substr($tot,$str+1,2);

                    $str3   = substr($tot,0,$str+1);

                    $str5   = substr($str3,-1,1);
                    
                    $str_tot   = $str3.$str2;
                    
                ?>

                Total_Recoveries : <?= $str_tot ?>,

            },
            
        <?php endforeach; ?>
        
        ],
        xkey: 'period',
        ykeys: ['Total_Recoveries'],
        labels: ['Total_Recoveries'],
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
        element: 'area-chart-asuransi2',
        data: [

        <?php foreach ($bulan as $b): ?>
            {
            <?php $dt = [ 'asuransi'        => $asuransi,
                        'cabang_asuransi'   => $cabang_asuransi,
                        'bank'              => $bank,
                        'cabang_bank'       => $cabang_bank,
                        'capem_bank'        => $capem_bank,
                        'tanggal_awal'      => $tanggal_awal,
                        'tanggal_akhir'     => $tanggal_akhir,
                        'verifikator'       => $verifikator,
                        'level'             => $level,
                        'spk'               => $spk_manager
                    ]; ?>


                <?php $nm = $this->M_home->get_recov_asuransi_area($dt, $b['id'])->result_array(); ?>
                period: '<?= $b['id'] ?>',
                
                <?php $tot =0; foreach ($nm as $n): 
                        $tot += $n['total_recoveries'];
                    ?>
                    
                <?php endforeach; ?>

                <?php 
                
                    $str    = strpos($tot,".");

                    $str2   = substr($tot,$str+1,2);

                    $str3   = substr($tot,0,$str+1);

                    $str5   = substr($str3,-1,1);
                    
                    $str_tot   = $str3.$str2;
                    
                ?>

                Total_Recoveries : <?= $str_tot ?>,
            },
            
        <?php endforeach; ?>
        
        ],
        xkey: 'period',
        ykeys: ['Total_Recoveries'],
        labels: ['Total_Recoveries'],
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

})

</script>