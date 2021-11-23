<style type="text/css">
	thead tr th {
		text-align: center;
	}
</style>

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 align-self-center">
            <h4 class="page-title">Upload File</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>">Report Care</a></li>
						<li class="breadcrumb-item">Print Report</li>
						<li class="breadcrumb-item active" aria-current="page">Upload File</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card shadow">
				<div class="card-header bg-info">
					<h4 class="mb-0 text-white">Upload Dokumen</h4>	
				</div>
				<form method="POST" action="<?= base_url('r_print_report/do_upload') ?>" enctype="multipart/form-data">
					<div class="card-body">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<div class="row">
										<div class="col-md-3 text-right">
											<label class="mt-2">Judul</label>
										</div>
										<div class="col-md-9">
											<input type="text" name="judul" class="form-control" placeholder="Masukan Judul">	
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<div class="row">
										<div class="col-md-3 text-right">
											<label class="mt-2">File</label>
										</div>
										<div class="col-md-9">
										<input type="file" class="form-control" name="file">
										</div>
									</div>
								</div>
							</div>
							<div class="form-group col-md-4" align="center">
								<button class="btn btn-success" id="btn_upload" type="submit">Upload</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="card border-info shadow">
				<div class="card-header bg-info">
					<h4 class="mb-0 text-white">Data Dokumen</h4>	
				</div>
				<div class="card-body table-responsive">
					<table class="table table-bordered table-hover" id="tabel" width="100%">
						<thead class="bg-info text-white">
							<tr>
								<th width="15%">No</th>
								<th>Judul</th>
								<th width="15%">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php $no=1; foreach ($data_upload as $d): ?>
								<tr>
									<td align="center"><?= $no++ ?></td>
									<td><?= $d['judul'] ?></td>
									<td align="center">
										<div class="row">
											<div class="col-md-6">
												<form action="<?= base_url() ?>r_print_report/download" method="POST">
													<input type="hidden" name="dok" value="<?= $d['dokumen'] ?>">
													<button class="btn btn-success" type="submit" >Download</button>
												</form>
											</div>
											<div class="col-md-6">
												<form action="<?= base_url('r_print_report/hapus_file_dok') ?>" method="POST">
													<input type="hidden" name="id_laporan" value="<?= $d['id_laporan'] ?>">
													<input type="hidden" name="dok" value="<?= $d['dokumen'] ?>">
													<button class="btn btn-danger" type="submit" onclick="return confirm('Apakah yakin akan menghapus?..')">Hapus</button>
												</form>
											</div>
										</div>
										
									</td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- <section class="content">

	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary box-solid">
				<div class="box-body" style="background-color: white;">
					<div class="col-md-1"></div>
					<div class="col-md-10">
						<table class="table table-hover table-striped" id="tabel">
							<thead style="background-color: #122E5D; color: white; ">
								<tr>
									<th>No</th>
									<th>Judul</th>
									<th width="30%">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php $no=1; foreach ($data_upload as $d): ?>
									<tr>
										<td align="center"><?= $no++ ?></td>
										<td><?= $d['judul'] ?></td>
										<td align="center">
											<a href="<?= base_url() ?>r_print_report/download/<?= $d['dokumen'] ?>"><button class="btn btn-default" style="background-color: #EDE90F; color: #122E5D; font-weight: bold; font-size: 15px;">Download</button></a><?= nbs(2)?><a href="<?= base_url() ?>r_print_report/hapus_file_dok/<?= $d['id_laporan'] ?>/<?= $d['dokumen'] ?>"><button class="btn btn-danger" style=" font-weight: bold; font-size: 15px;" onclick="return confirm('Apakah yakin akan menghapus?..')">Hapus</button></a></td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
					<div class="col-md-1"></div>
				</div>
			</div>
		</div>
	</div>

</section> -->

<script src="<?= base_url() ?>template/assets/libs/jquery/dist/jquery.min.js"></script>

<script type="text/javascript">
	
	/*
	
	$('#submit').submit(function(e){
            e.preventDefault(); 
                 $.ajax({
                     url:'<?php echo base_url();?>index.php/upload/do_upload', //URL submit
                     type:"post", //method Submit
                     data:new FormData(this), //penggunaan FormData
                     processData:false,
                     contentType:false,
                     cache:false,
                     async:false,
                      success: function(data){
                          alert("Upload Image Berhasil."); //alert jika upload berhasil
                   }
                 });
	});
	
	**** http://mfikri.com/artikel/upload-codeigniter-ajax
	*/

	$(document).ready(function(){

		$('#submit').submit(function(e){
			e.preventDefault();
				$.ajax({
					url 		: '<?= base_url('r_print_report/do_upload') ?>',
					type 		: "post",
					data 		: new FormData(this),
					processData : false,
					contentType : false,
					cache 		: false,
					async 		: false,
					success 	: function(data){
						alert("Upload File Berhasil");
						location.reload();
					}
				})
		})

	})

</script>