<style type="text/css">
	thead tr th {
		text-align: center;
	}
</style>
<section class="content-header">
        <h1>
          Upload File </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i>Report Care</a></li>
        <li><a href="#">Print Report</a></li>
        <li class="active"><a href="#">Upload File</a></li>
        </ol>
      </section>

      <!-- Main content -->

<section class="content">

	<div class="row">
		<div class="col-md-12">
				
			<div class="box box-primary box-solid">
				<div class="box-header with-border"  style="background-color: #122E5D; color: white; ">
	                  <h1 class="box-title" style="font-size: 15px;">Upload Dokumen</h1>
	             </div>
	             <div class="box-body text-center" style="background-color: white;">
	             	<form method="POST" action="<?= base_url('r_print_report/do_upload') ?>" enctype="multipart/form-data">
	             		<div class="col-md-2"></div>
	             		<div class="col-md-3">
	             			<input type="text" name="judul" class="form-control" placeholder="Masukan Judul">
	             		</div>
		             	<div class="col-md-3">
		             		<input type="file" name="file">
		             	</div>
		             	<div class="col-md-2">
		             		<button class="btn btn-default" style="background-color: #EDE90F; color: #122E5D; font-weight: bold; font-size: 15px;" id="btn_upload" type="submit">Upload</button>
		             	</div>
		             	<div class="col-md-2"></div>
	             	</form>
	             </div>
	        </div>

		</div>
	</div>

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
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php $no=1; foreach ($data_upload as $d): ?>
									<tr>
										<td align="center"><?= $no++ ?></td>
										<td><?= $d['judul'] ?></td>
										<td align="center"><a href="<?= base_url() ?>r_print_report/download/<?= $d['dokumen'] ?>"><button class="btn btn-default" style="background-color: #EDE90F; color: #122E5D; font-weight: bold; font-size: 15px;">Download</button></a></td>
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

</section>

<!-- jQuery 3 -->
<script src="<?= base_url() ?>assets/bower_components/jquery/dist/jquery.min.js"></script>

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