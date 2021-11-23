<style type="text/css">
  thead tr th {
    text-align: center;
  }
</style>
<!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Monitoring Kinerja </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i>Report care</a></li>
        <li class="active"><a href="#">Dashboard</a></li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <!-- <div class="row">
          <form method="POST" action="<?= base_url('home') ?>">
          <div class="col-md-3">
          Date
          <div class="form-group">
            <label>Periode Bulan dan Tahun</label>
            
            <div class="input-group date">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="text" name="bulan" class="form-control pull-right" id="datepicker3" placeholder="-- Periode Bulan dan Tahun --">
        
            </div>
              
            /.input group
          </div>
          /.form group
          </div>
          <div class="col-md-3"><button class="btn btn-default" type="submit" name="cari" style="margin-top: 25px; margin-bottom: 10px; background-color: #EDE90F; color: #122E5D; font-weight: bold; font-size: 15px;">Tampilkan</button></div>
          </form>
        </div> -->
        <?php if (isset($_POST['cari'])): ?>
          <?= $this->session->flashdata('pesan'); ?>
        <?php endif ?>
        

        <div class="row" style="margin-top: 50px">
          <div class="col-md-12">
            <div class="row">
            <div class="col-md-4">
              <!-- small box -->
              <a href="#" data-toggle="modal" data-target="#modal-danger">
              <div class="small-box bg-red">
                <div class="inner">
                  <h3>0<!-- <?= count($data_recov_buruk) ?> --></h3>

                  <p>Cabang / Capem Buruk</p>
                </div>
                <div class="icon">
                  <i class="fa fa-thumbs-down"></i>
                </div>
              </div>
              </a>

              <div class="modal modal-danger fade" tabindex="-1" id="modal-danger">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title">Data Capem Buruk</h4>
                    </div>
                    <div class="modal-body">
                      <table class="table table-bordered" id="tabel2">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>CABANG</th>
                            <th>CAPEM</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no=1; foreach ($data_recov_buruk as $d): ?>
                              <tr>
                                <td align="center"><?= $no++ ?></td>
                                <td align="center"><?= $d['cabang_bank'] ?></td>
                                <td align="center"><?= $d['capem_bank'] ?></td>
                              </tr>
                          <?php endforeach ?>
                        </tbody>
                      </table>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline pull-right" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>

            </div>
            <div class="col-md-4">
              <!-- small box -->
              <a href="#" data-toggle="modal" data-target="#modal-warning">
                <div class="small-box bg-yellow">
                  <div class="inner">
                    <h3><?= count($data_recov_cukup) ?></h3>

                    <p>Cabang / Capem Cukup</p>
                  </div>
                  <div class="icon">
                    <i class="fa fa-hand-paper-o"></i>
                  </div>
                </div>
              </a>

              <div class="modal modal-warning fade" tabindex="-1" id="modal-warning">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title">Data Capem Cukup</h4>
                    </div>
                    <div class="modal-body">
                      <table class="table table-bordered" id="tabel">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Cabang</th>
                            <th>Capem</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no=1; foreach ($data_recov_cukup as $d): ?>
                            <tr>
                                <td align="center"><?= $no++ ?></td>
                                <td align="center"><?= $d['cabang_bank'] ?></td>
                                <td align="center"><?= $d['capem_bank']; ?></td>
                              </tr>
                              
                          <?php endforeach ?>
                        </tbody>
                      </table>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline pull-right" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>

            </div>
            <div class="col-md-4">
              <!-- small box -->
              <a href="#" data-toggle="modal" data-target="#modal-success">
                <div class="small-box bg-green">
                  <div class="inner">
                    <h3><?= count($data_recov_baik) ?></h3>

                    <p>Cabang / Capem Baik</p>
                  </div>
                  <div class="icon">
                    <i class="fa fa-thumbs-o-up"></i>
                  </div>
                </div>
              </a>

              <div class="modal modal-success fade" tabindex="-1" id="modal-success">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title">Data Capem Baik</h4>
                    </div>
                    <div class="modal-body">
                      <table class="table table-bordered" id="tabel2">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Cabang</th>
                            <th>Capem</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no=1; foreach ($data_recov_baik as $d): ?>
                              <tr>
                                <td align="center"><?= $no++ ?></td>
                                <td align="center"><?= $d['cabang_bank'] ?></td>
                                <td align="center"><?= $d['capem_bank'] ?></td>
                              </tr>
                          <?php endforeach ?>
                        </tbody>
                      </table>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline pull-right" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>

            </div>  

            </div>
            
            <div class="row">
              <div class="col-md-12">
                  <div class="col-md-6" style="border: 3px solid #122E5D; padding: 10px;">
                    <label>RECOVERIES TERBESAR :</label><br>
                    <div class="col-md-6">
                      <span style="font-size: 17px; font-weight: bold;">Rp. <?= number_format($recov_cabang_b['total_recoveries'],0,'.','.') ?></span>
                    </div>
                    <div class="col-md-6">
                      <span style=" font-weight: bold;">Cabang <?= $recov_cabang_b['cabang_bank'] ?></span>
                    </div>
                     
                  </div>
                  <div class="col-md-6" style="border: 3px solid #122E5D; padding: 10px;">
                    <label>RECOVERIES TERKECIL :</label><br>
                    <div class="col-md-6">
                      <span style="font-size: 17px; font-weight: bold;">Rp. 0<!-- <?= number_format($recov_cabang_k['total_recoveries'],0,'.','.') ?> --></span>
                    </div>
                    <div class="col-md-6">
                      <span style=" font-weight: bold;">Cabang <!-- <?= $recov_cabang_k['cabang_bank'] ?> --></span>
                    </div>
                  </div>
              </div>
              <div class="col-md-12">
                  <div class="col-md-6" style="border: 3px solid #122E5D; margin-top: 10px; padding: 10px;">
                    <div class="col-md-6">
                      <span style="font-size: 17px; font-weight: bold;">Rp. <?= number_format($recov_capem_b['total_recoveries'],0,'.','.') ?></span>
                    </div>
                    <div class="col-md-6">
                      <span style=" font-weight: bold;">Wilayah <?= $recov_capem_b['capem_bank'] ?></span>
                    </div>
                    <div class="col-md-12" style="margin-top: 10px">
                      <span style=" font-weight: bold; font-size: 17px;">Petugas : <?= $recov_capem_b['nama_lengkap'] ?></span>
                    </div>
                  </div>
                  <div class="col-md-6" style="border: 3px solid #122E5D; margin-top: 10px; padding: 10px;">
                     <div class="col-md-6">
                      <span style="font-size: 17px; font-weight: bold;">Rp. 0 <!-- <?= number_format($recov_capem_k['total_recoveries'],0,'.','.') ?> --></span>
                    </div>
                    <div class="col-md-6">
                      <span style=" font-weight: bold;">Wilayah - <!-- <?= $recov_capem_k['capem_bank'] ?> --></span>
                    </div>
                    <div class="col-md-12" style="margin-top: 10px">
                      <span style=" font-weight: bold; font-size: 17px;">Petugas : - <!-- <?= $recov_capem_k['nama_lengkap'] ?> --></span>
                    </div>
                  </div>

                </div>
              </div>   
          </div>
        
      </section>

      


<!-- jQuery 3 -->
<script src="<?= base_url() ?>assets/bower_components/jquery/dist/jquery.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    $('#kinerja').change(function () {
      var id = $(this).val();
      $.ajax({ 
        url    : "<?= base_url() ?>home/get_data_cabang",
        method : "POST",
        data   : {id: id},
        async  : false,
        dataType : 'json',
        success  : function(data) {
          var html = '';
          var i;
          for (var i = 0; i < data.length; i++) {
            html += '<tr><td>'+(i+1)+'</td><td>'+data[i].cabang_bank+'</td><td>'+data[i].capem_bank+'</td></tr>';
          }
          $('.cabang').html(html);
        }
       });
    });
  });
</script>