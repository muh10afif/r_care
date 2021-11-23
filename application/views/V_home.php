<style type="text/css">
  thead tr th {
    text-align: center;
  }
</style>

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-5 align-self-center">
            <h4 class="page-title">Monitoring Kinerja</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>">R-care</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-info">
                <div class="card-body">
                  <?= form_open('home'); ?>
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Pilih Bank</label>
                        
                          <select class="form-control select2" name="bank" >
                            <option value="0">-- Pilih Bank --</option>
                            <?php foreach ($data_bank as $d): ?>
                              <option value="<?= $a = $d['id_bank'] ?>" <?= ($id_bank == $a) ? 'selected' : '' ?>><?= $d['bank'] ?></option>
                            <?php endforeach ?>
                          </select>
                          
                      </div>
                    </div>
                    
                    <div class="col-md-3" style="margin-top: 22px;">
                      <button class="btn btn-default" type="submit" name="cari" style="background-color: #EDE90F; color: #122E5D; font-weight: bold; font-size: 15px;">Tampilkan</button>
                      <button class="btn btn-default" type="submit" name="x" style="background-color:  #122E5D; color: white; font-weight: bold; font-size: 15px;">Tampilkan Semua</button></div>
                  </div>
                  <?php if (isset($_POST['cari'])): ?>
                    <?= $this->session->flashdata('pesan'); ?>
                  <?php endif ?>
                  

                  <div class="row">
                    <div class="col-md-12">
                      <div class="row">
                      <div class="col-md-4">
                        <!-- small box -->
                        <a href="#" data-toggle="modal" data-target="#modal-danger">
                        <div class="small-box bg-red">
                          <div class="inner">
                            <h3><?= count($data_recov_buruk) ?></h3>

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
                                <span style="font-size: 17px; font-weight: bold;">Rp. <?= number_format($recov_cabang_k['total_recoveries'],0,'.','.') ?></span>
                              </div>
                              <div class="col-md-6">
                                <span style=" font-weight: bold;">Cabang <?= $recov_cabang_k['cabang_bank'] ?></span>
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
                                <span style="font-size: 17px; font-weight: bold;">Rp. <?= number_format($recov_capem_k['total_recoveries'],0,'.','.') ?></span>
                              </div>
                              <div class="col-md-6">
                                <span style=" font-weight: bold;">Wilayah <?= $recov_capem_k['capem_bank'] ?></span>
                              </div>
                              <div class="col-md-12" style="margin-top: 10px">
                                <span style=" font-weight: bold; font-size: 17px;">Petugas : <?= $recov_capem_k['nama_lengkap'] ?></span>
                              </div>
                            </div>

                          </div>
                        </div>   
                    </div>
                  </div><br> 

                    <div class="row">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-3">
                            <div class="small-box bg-aqua">
                              <div class="inner" align="center">
                                <h3><?= $total_noa; ?></h3>

                                <p>Total Noa</p>

                              </div>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="small-box bg-green">
                              <div class="inner" align="center">
                                <h3><?= $total_ots; ?></h3>

                                <p>NOA yang sudah dikunjungi</p>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-3" align="center">
                            <div class="small-box bg-purple" style="height: 100px;">
                              <div class="inner">
                                <h4 style="font-size: 22px; font-weight: bold;">Rp. <?= number_format($shs_sudah['tot_shs_noa'],'0','.','.') ?></h4>

                                <p>SHS Semua NOA</p>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="small-box bg-orange">
                              <div class="inner" align="center">
                                <h4 style="font-size: 22px; font-weight: bold;">Rp. <?= number_format($total_shs_noa['tot_shs'],'0','.','.') ?></h4>

                                <p>SHS yang sudah dikunjungi</p>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                      </div>

                      <div class="col-md-12">
                        <div class="row">
                          
                          <div class="col-md-4">
                            <div class="box box-success">
                              <div class="box-body" align="center">
                                <p>Persentase Noa Yang sudah dikunjungi</p>

                                <?php if ($total_noa == 0): ?>
                                  <?php $ab = 0; ?>
                                <?php else: ?>
                                  <?php $ab = ($total_ots / $total_noa) * 100; ?>
                                <?php endif ?>

                                <h3><?= number_format($ab,2,',','.') ?> %</h3>

                                <div class="progress active" style="box-shadow: 2px 2px 2px;">
                                  <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?= number_format($ab) ?>%">
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-4" align="center">
                            <div class="box box-primary">
                              <div class="box-body" align="center">
                                <p>Persentase SHS semua Noa</p>

                                <?php if ($shs_sudah['tot_subro'] == 0): ?>
                                  <?php $ac = 0; ?>
                                <?php else: ?>
                                  <?php $ac = ($shs_sudah['tot_rec'] / $shs_sudah['tot_shs_noa']) * 100; ?>
                                <?php endif ?>

                                <h3><?= number_format($ac,2,',','.') ?> %</h3>

                                <div class="progress active" style="box-shadow: 2px 2px 2px;">
                                  <div class="progress-bar progress-bar-purple progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?= number_format($ac) ?>%">
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="box box-warning">
                              <div class="box-body" align="center">
                                <p>Persentase SHS yang sudah dikunjungi</p>

                                <?php if ($shs_sudah['tot_shs_noa'] == 0): ?>
                                  <?php $ad = 0; ?>
                                <?php else: ?>
                                  <?php $ad = ($total_shs_noa['tot_shs'] / $shs_sudah['tot_shs_noa']) * 100; ?>
                                <?php endif ?>

                                <h3><?= number_format($ad,2,',','.') ?> %</h3>

                                <div class="progress active" style="box-shadow: 2px 2px 2px;">
                                  <div class="progress-bar progress-bar-yellow progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?= number_format($ad) ?>%">
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                      </div>

                      <div class="col-md-6">
                        <!-- DONUT CHART -->
                          <div class="box box-primary" style="height: 600px;"> 
                            <div class="box-header with-border">
                              <h3 class="box-title">Pencapaian Recoveries Askrindo</h3>
                            </div>
                            <div class="box-body">
                              
                              <div class="row">
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <div class="input-group date">
                                      <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                      </div>
                                      <input type="text" class="form-control pull-right" value="<?= (!empty($tgl_awal)) ? $tgl_awal : '' ?>" name="tgl_awal" placeholder="Tanggal Awal" id="datepicker">
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <div class="input-group date">
                                      <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                      </div>
                                      <input type="text" class="form-control pull-right" value="<?= (!empty($tgl_akhir)) ? $tgl_akhir : '' ?>" name="tgl_akhir" placeholder="Tanggal Akhir" id="datepicker2">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                  <button class="btn btn-default" type="submit" name="cari_bulan" style="background-color: #EDE90F; color: #122E5D; font-weight: bold; font-size: 15px;">Tampilkan</button>
                                </div>
                              </div>    
                              <br>
                              <br>
                              <br>
                              <!-- <canvas id="myChart" style="height:250px"></canvas> -->
                              <div class="chart" id="askrindo-chart" style="height: 300px; position: relative;"></div>
                            </div>
                            <!-- /.box-body -->
                          </div>
                          <!-- /.box -->
                      </div> 

                      <div class="col-md-6">
                        <div class="box box-warning" style="height: 600px;">
                            <div class="box-header with-border">
                              <h3 class="box-title">Verifikator</h3>
                            </div>
                            <div class="box-body">
                              <div class="row">
                                <div class="col-md-6">
                                  <!-- nama karyawan verifikator -->
                                  <select class="form-control select2" name="verifikator" style="width: 100%;">
                                    <option value="0">-- Pilih Verifikator --</option>
                                    <?php foreach ($verifikator as $v): ?>
                                      <option value="<?= $a = $v['id_karyawan'] ?>" <?= ($id_ver == $a) ? 'selected' : '' ?> ><?= $v['nama_lengkap'] ?></option>
                                    <?php endforeach ?>
                                  </select>
                                </div>
                                <div class="col-md-3">
                                  <button class="btn btn-default" type="submit" name="cari_verifikator" style="background-color: #EDE90F; color: #122E5D; font-weight: bold; font-size: 15px;">Tampilkan</button></div>
                              </div><br>
                              <div class="row">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">

                                  <?php if ($data_ver['nama'] == '-'): ?>
                                    <img style="height: 250px; width: 100%;" src="<?= base_url("assets/dist/img/no_image.png") ?>" class="thumbnail img-responsive"> 
                                  <?php else: ?>
                                    <img style="height: 250px; width: 100%;" src="<?= base_url("assets/img/pp.png") ?>" class="thumbnail img-responsive"> 
                                  <?php endif ?>
                                  

                                    <h3 style="text-align: center;"><?= $data_ver['nama'] ?></h3 style="text-align: center;">
                                </div>
                                <div class="col-md-3"></div>
                                
                              </div>
                              <div class="row">
                                <div class="col-md-3"></div>
                                <div class="col-md-6" align="center">

                                  <table class="table">
                                    <tr align="center">
                                      <td style="font-weight: bold;">Jumlah Recoveries</td>
                                      <td style="font-weight: bold;">Sudah OTS</td>
                                    </tr>
                                    <tr align="center">
                                        <td>Rp. <?= number_format($data_ver['jml_recov'],'0','.','.') ?></td>
                                        <td><?= $data_ver['jml_ots'] ?></td>
                                    </tr>
                                  </table>
                                </div>
                                <div class="col-md-3" align="center">
                                </div>  
                              </div>              
                            </div>
                        </div>
                      </div> 

                    </div>

                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- jQuery -->
<script src="<?= base_url() ?>template/assets/libs/jquery/dist/jquery.min.js"></script>
<!-- Morris Chart -->
<script src="<?= base_url() ?>template/assets/libs/morris.js/morris.min.js"></script>

<script type="text/javascript">
  $(function () {
    "use strict";
  //DONUT CHART
    var donut = new Morris.Donut({
      element: 'askrindo-chart',
      resize: true,
      colors: ['#00e34c', '#2000e3', '#e3006d', '#25c9c9', '#3ea10f', '#f2c115', '#f26415', '#434745','#bd8ded', '#8db9ed'],
      data: [

      <?php foreach ($pie_cabang as $p) : ?>

        {label: "<?= $p['cabang_asuransi'] ?>", value: Math.round(<?= $p['total_recoveries'] ?>)},

      <?php endforeach ?>
      ],
      hideHover: 'auto'
    });
  });
</script>

<!-- <?php $pie = array(); $tot = array(); foreach ($pie_cabang as $p) : ?>

  <?php array_push($pie, $p['cabang_asuransi']) ?>
  <?php array_push($tot, number_format($p['total_recoveries'],0,'.','.')) ?>

<?php endforeach ?>

<script type="text/javascript">
  
  var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'pie',
      data: {

        labels: 

        [

        <?php foreach ($pie_cabang as $p) : ?>
            <?php $b = number_format($p['total_recoveries'],0,'.','.') ?>
            <?php $c = $p['cabang_asuransi'] ?>

            "<?= $c.'='.$b ?>",

          <?php endforeach ?>

        ]
        ,
        datasets: [

        {
          label: 'as',
           
          data: [

          <?php foreach ($pie_cabang as $p) : ?>
            <?php $b = $p['total_recoveries'] ?>

            '<?= $b ?>',

          <?php endforeach ?>

          ],
          backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(153, 102, 255, 0.2)',
          'rgba(255, 159, 64, 0.2)'
          ],
          borderColor: [
          'rgba(255,99,132,1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 159, 64, 1)'
          ],
          borderWidth: 1
        }

        ]
      },
      options: {
        scales: {

        }
      }
    });

</script> -->

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

  var pieChartCanvas = $('#myChart').get(0).getContext('2d')
    var pieChart       = new Chart(pieChartCanvas)
    var PieData        = [
    <?php foreach ($pie_cabang as $p) : ?>

      <?php $a = ['#42f57e', '#f56954', '#417051', '#4500f2', '#f00e52', '#996877', '#7ae60e', '#27e60e','#b1e4f0', '#550fb8', '#010a03', '#ff8282', '#fceded', '#6afc26', '#78fa94', '#59c5f7', '#023a54']; ?>

      {
        value    : <?= $p['total_recoveries'] ?>,
        color    : '<?= random_element($a) ?>',
        label    : "<?= $p['cabang_asuransi'] ?>"
      },
      
    <?php endforeach ?>
      
    ]
    var pieOptions     = {
      //Boolean - Whether we should show a stroke on each segment
      segmentShowStroke    : true,
      //String - The colour of each segment stroke
      segmentStrokeColor   : '#fff',
      //Number - The width of each segment stroke
      segmentStrokeWidth   : 2,
      //Number - The percentage of the chart that we cut out of the middle
      percentageInnerCutout: 50, // This is 0 for Pie charts
      //Number - Amount of animation steps
      animationSteps       : 100,
      //String - Animation easing effect
      animationEasing      : 'easeOutBounce',
      //Boolean - Whether we animate the rotation of the Doughnut
      animateRotate        : true,
      //Boolean - Whether we animate scaling the Doughnut from the centre
      animateScale         : false,
      //Boolean - whether to make the chart responsive to window resizing
      responsive           : true,
      // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio  : true,
      //String - A legend template
      legendTemplate       : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChart.Doughnut(PieData, pieOptions)
</script>
