<style type="text/css">
    #gmbr{
        width: 200px;
        height: 125px;
        cursor: pointer;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header bg-info">
                <h4 class="mb-0 text-white">Detail Debitur</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Id Care</label>
                                </div>
                                <div class="col-md-9">
                                    : <?= $data_deb['id_care'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>No Klaim</label>
                                </div>
                                <div class="col-md-9">
                                    : <?= $data_deb['no_klaim'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3 ">
                                    <label>Nama Debitur</label>
                                </div>
                                <div class="col-md-9">
                                    : <?= $data_deb['nama_debitur'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3 ">
                                    <label>Capem Bank</label>
                                </div>
                                <div class="col-md-9">
                                    : <?= $data_deb['capem_bank'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3 ">
                                    <label>Cabang Asuransi</label>
                                </div>
                                <div class="col-md-9">
                                    : <?= $data_deb['cabang_asuransi'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3 ">
                                    <label>Alamat Debitur</label>
                                </div>
                                <div class="col-md-9">
                                    : <?= $data_deb['alamat_deb'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3 ">
                                    <label>Alamat Agunan</label>
                                </div>
                                <div class="col-md-9">
                                    : <?= $data_deb['alamat'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3 ">
                                    <label>Sisa Hutang Bank</label>
                                </div>
                                <div class="col-md-9">
                                    : Rp. <?= number_format($sisa_hutang,0,'.','.') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3 ">
                                    <label>SHS Asuransi</label>
                                </div>
                                <div class="col-md-9">
                                    : Rp. <?= number_format($shs,0,'.','.') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3 ">
                                    <label>Nilai Agunan</label>
                                </div>
                                <div class="col-md-9">
                                    : Rp. <?= number_format($data_deb['total_harga'],0,'.','.') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3 ">
                                    <label>Status Agunan</label>
                                </div>
                                <div class="col-md-9">
                                    : <?= $data_deb['status_asset'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   
</div>

<?php foreach ($jns_dok as $f): ?>
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header bg-info">
                <h4 class="mt-0 text-white"><?= $f['jenis_dokumen'] ?></h4>
            </div>
            <div class="card-body">
                <div class="row gambar">
                    <?php foreach ($foto as $h): ?>
                        <div class="col-md-4">

                            <!-- Card -->
                            <div class="card shadow">
                                <img class="card-img-top img-responsive" id="gmbr" style="height: 250px; width: 100%;" src="http://vcare-new.skdigital.id/images/<?php echo $h['foto'];?>" alt="Card image cap">
                                <div class="card-body text-center">
                                    <?php if ($h['tampak_asset']) : ?>
                                        <br>
                                        <h4 class="card-title">Tampak <?= $h['tampak_asset'] ?></h4>
                                    <?php endif ?>
                                </div>
                            </div>
                            <!-- Card -->
                        </div>

                    <?php endforeach; ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Photoviewer -->
<link rel="stylesheet" href="<?= base_url() ?>assets/viewer/css/viewer.css">
<script src="<?= base_url() ?>template/assets/libs/jquery/dist/jquery.min.js"></script>
<!-- Photoviewer -->
<script src="<?= base_url() ?>assets/viewer/js/viewer.js"></script>

<script type="text/javascript">
	$('.gambar').viewer();
</script>