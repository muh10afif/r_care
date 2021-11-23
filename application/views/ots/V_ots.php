<div class="page-breadcrumb">
    <div class="row">
        <div class="col-5 align-self-center">
            <h4 class="page-title">Report OTS</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>">Report Care</a></li>
                        <li class="breadcrumb-item active" aria-current="page">R-OTS</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-info card-hover">
                <div class="card-header bg-info">
					<h4 class="mb-0 text-white">Filter Data</h4></div>
				<form method="POST" target="_self" id="tab" action="<?= base_url('r_ots') ?>" autocomplete="off">
                <div class="card-body">
					
						<div class="row">

							<div class="form-group col-md-4">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<span class="ti-calendar"></span>
										</span>
									</div>
									<input type="text" class="form-control pull-right" name="tgl_awal" placeholder="Tanggal Awal Periode" id="tanggal" value="<?= ($p_tgl_awal != null) ? $p_tgl_awal : '' ?>">
								</div>
							</div>
							<div class="form-group col-md-4">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<span class="ti-calendar"></span>
										</span>
									</div>
									<input type="text" class="form-control pull-right" name="tgl_akhir" placeholder="Tanggal Akhir Periode" id="tanggal2" value="<?= ($p_tgl_akhir != null) ? $p_tgl_akhir : '' ?>">
								</div>
							</div>
							<div class="col-md-4">
								<!-- cabang bank -->
								<select class="select2 form-control custom-select" name="wilayah" style="width: 100%; height:36px;">  
									<option value="">-- Pilih Cabang --</option>
									<?php foreach ($wilayah->result_array() as $w): ?>
										<option value="<?= $a = $w['cabang_bank'] ?>" <?= ($p_wilayah == $a) ? 'selected' : '' ?>><?= $w['cabang_bank'] ?></option>
									<?php endforeach ?>
								</select>
							</div>
							<div class="col-md-4">
								<!-- nama karyawan verifikator -->
								<select class="select2 form-control custom-select" name="verifikator" style="width: 100%; height:36px;">
									<option value="">-- Pilih Verifikator --</option>
									<?php foreach ($verifikator->result_array() as $v): ?>
										<option value="<?= $c = $v['nama_lengkap'] ?>" <?= ($p_ver == $c) ? 'selected' : '' ?>><?= $v['nama_lengkap'] ?></option>
									<?php endforeach ?>
								</select>
							</div>
							<div class="col-md-4">
								<!-- nama karyawan verifikator -->
								<select class="select2 form-control custom-select" name="bank" style="width: 100%; height:36px;">
									<option value="">-- Pilih Bank --</option>
									<?php foreach ($bank->result_array() as $b): ?>
										<option value="<?= $e = $b['bank'] ?>" <?= ($p_bank == $e) ? 'selected' : ''; ?>><?= $b['bank'] ?></option>
									<?php endforeach ?>
								</select>
							</div>
							<div class="col-md-4">
								<select class="select2 form-control custom-select" name="asuransi" style="width: 100%; height:36px;">
									<option value="">-- Pilih Asuransi --</option>
									<?php foreach ($asuransi->result_array() as $d): ?>
										<option value="<?= $a = $d['id_asuransi'] ?>" <?= ($p_asuransi == $a) ? 'selected' : ''; ?> ><?= $d['asuransi'] ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
					
				</div>
				<div class="card-footer">
					<div class="col-md-12" align="center">
						<button class="btn btn-success" onclick="b()" name="cari">Tampilkan</button><?= nbs(3) ?>
						<button class="btn btn-warning" onclick="b()" name="all">Tampil Semua</button><?= nbs(3) ?>
						<button name="unduh" onclick="a()" class="btn btn-info">Export Data</button>
					</div>
				</div>
				</form>
            </div>
        </div>
    </div>


	<div class="row">
		<div class="col-md-9">
			<div class="card card-hover">
				<div class="card-header bg-info">
					<h4 class="mb-0 text-white">Data Report OTS</h4></div>

				<div class="card-body">
					<?php if (isset($_POST['cari'])): ?>	
					<?= $this->session->flashdata('pesan'); ?>
					<?php endif ?>
					<table class="table table-bordered table-hover" id="tabel">
						<thead class="bg-primary text-white">
							<tr>
								<th>No</th>
								<th>Nama Debitur</th>
								<th>No Klaim / ID</th>
								<th>Cabang Bank</th>
								<th>Bank</th>
								<th>Alamat</th>
								<th>Hasil OTS</th>
								<th>POT</th>
							</tr>
						</thead>
						<tbody>
							<?php $no=0; foreach ($data_r_ots->result_array() as $d): $no++ ?>
							<tr>
								<td style="text-align: center;"><?= $no; ?></td>
								<td><?= $d['nama_debitur'] ?></td>
								<td><?= $d['no_klaim'] ?></td>
								<td><?= $d['cabang_bank'] ?></td>
								<td><?= $d['bank'] ?></td>
								<td><?= $d['alamat_deb'] ?></td>
								<td style="width: 150px;"><?= $d['keterangan'] ?></td>
								<td><?= $d['status_deb'] ?></td>
							</tr>
							<?php endforeach ?>
						</tbody>
					</table>
					
				</div>
		</div>
		</div>	
		<div class="col-md-3">
			<div class="card border-info card-hover">

                <div class="card-header bg-info">
                  <h4 class="mb-0 text-white">Peringkat Verifikator</h4>
                </div>
                <!-- /.box-header -->
                <div class="card-body">
                    <table class="table table-striped table-hover">
                      <tbody>
                      <?php $no=1; foreach ($peringkat_ver->result_array() as $p): ?>
                      	<tr>
                          <td align="center" width="5px"><?= $no++ ?>.</td>
                          <td align="left"><?= $p['nama_lengkap'] ?></td>
                          <td style="font-size: 12px; width: 50%;"><?= "Rp. ".number_format($p['total_recoveries'],0,",",".") ?></td>
                       </tr> 
                      <?php endforeach ?>
                      </tbody>
                    </table>
                </div>

            </div>

			<div class="card text-white bg-danger card-hover">
				<div class="card-header">
					<table width="100%" style="font-weight: bold; font-size: 20px; text-align: center;">
					<tr style="border-top: hidden;">
						<td width="50%">Total NOA</td>
						<td>:</td>
						<td><?= $total_noa ?></td>
					</tr>
					</table>
				</div>
			</div>

			<div class="card text-white bg-danger card-hover">
				<div class="card-header">
					<table width="100%" style="font-weight: bold; font-size: 20px; text-align: center;">
					<tr style="border-top: hidden;">
						<td width="50%">NOA OTS</td>
						<td>:</td>
						<td><?= $total_ots ?></td>
					</tr>
					</table>
				</div>
			</div>

			<div class="card text-white bg-danger card-hover">
				<div class="card-header">
					<table width="100%" style="font-weight: bold; font-size: 20px; text-align: center;">
					<tr style="border-top: hidden;">
						<td width="50%">Persentase</td>
						<td>:</td>
						<td>
							<?php if (($total_ots == 0) && ($total_noa == 0)): ?>
							<?= 0 ?>
							<?php else: ?>
							<?php $a = ($total_ots / $total_noa) * 100; echo number_format($a,2,',','.'); ?> %</td>
							<?php endif ?>
						</td>
					</tr>
					</table>
				</div>
			</div>

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

     