<style>

    #tabel_spk thead tr th {
        text-align: center;
        font-weight: bold;
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
        <div class="col-sm-12">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="card-title">List SPK</h4>
                
                <div class="table-responsive p-3">
                    <table class="table v-middle table-bordered table-hover" id="tabel_spk">
                        <thead class="bg-info text-white">
                            <tr>
                                <th class="border-top-0">No</th>
                                <th class="border-top-0">Cabang Asuransi</th>
                                <th class="border-top-0">No. SPK</th>
                                <th class="border-top-0">Tanggal Akhir</th>
                                <th class="border-top-0">NOA</th>
                                <th class="border-top-0">Subrogasi</th>
                                <th class="border-top-0">Recoveries</th>
                                <th class="border-top-0">SHS</th>
                                <th class="border-top-0">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach ($kanwil_spk as $k) : 
                                $tot_shs    = ($k['tot_subro'] - $k['tot_recov_awal_as']) - $k['tot_nominal_as'];
                                $tot_recov  = $k['tot_recov_awal_as'] + $k['tot_nominal_as'];

                                $id_spk         = $k['id_spk'];
                                $id_cabang_as   = $k['id_cabang_asuransi'];

                                ?>
                                <tr>
                                    <td align="center"><?= $no ?></td>
                                    <td><?= $k['cabang_asuransi'] ?></td>
                                    <td><?= $k['no_spk'] ?></td>
                                    <td><?= nice_date($k['tgl_akhir'], 'd-F-Y') ?></td>
                                    <td align="center"><?= $k['tot_noa'] ?></td>
                                    <td align="right"><?= number_format($k['tot_subro'],'2',',','.') ?></td>
                                    <td align="right"><?= number_format($tot_recov,'2',',','.') ?></td>
                                    <td align="right"><?= number_format($tot_shs,'2',',','.') ?></td>

                                    <td align="center"><a href="<?= base_url("home/index/asuransi/$id_spk/$id_cabang_as") ?>"><button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm">Detail</button></a></td>
                                </tr>
                            <?php $no++; endforeach; ?>
                            
                        </tbody>
                    </table>
                </div>

                </div>
            </div>
        </div>
    </div>

</div>