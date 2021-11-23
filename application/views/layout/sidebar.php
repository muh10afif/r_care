<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <!-- User Profile-->
                <li>
                    <!-- User Profile-->
                    <div class="user-profile d-flex no-block dropdown mt-3">
                        <div class="user-pic"><img src="<?= base_url() ?>template/assets/images/users/1.jpg" alt="users" class="rounded-circle" width="40" /></div>
                        <div class="user-content hide-menu ml-2">
                            <a href="javascript:void(0)" class="" id="Userdd" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <h5 class="mb-0 user-name font-medium"><?= ucwords($this->session->userdata('username'))?><?= nbs(3) ?><i class="fa fa-angle-down"></i></h5>
                                <span class="op-5 user-email"><?= ucwords($this->session->userdata('akses')) ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="Userdd">
                                <a class="dropdown-item" href="<?= base_url('login/keluar') ?>"><i class="fa fa-power-off mr-1 ml-1"></i> Logout</a>
                            </div>
                        </div>
                    </div>
                    <!-- End User Profile-->
                </li>

                <?php if ($this->session->userdata('level') == 4): ?>
                    <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url('home') ?>" aria-expanded="false"><i class="fas fa-desktop"></i><span class="hide-menu"><?= nbs(2) ?>Dashboard</span></a></li>
                    <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url('r_ots') ?>" aria-expanded="false"><i class="fas fa-street-view"></i><span class="hide-menu"><?= nbs(2) ?>Report OTS</span></a></li>
                    <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url('r_noa') ?>" aria-expanded="false"><i class="fas fa-id-card"></i><span class="hide-menu"><?= nbs(2) ?>Report NOA</span></a></li>
                    <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url('r_recov') ?>" aria-expanded="false"><i class="fas fa-donate"></i><span class="hide-menu"><?= nbs(2) ?>Report Recoveries</span></a></li>
                    <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url('r_proses') ?>" aria-expanded="false"><i class="fas fa-exchange-alt"></i><span class="hide-menu"><?= nbs(2) ?>Report Proses</span></a></li>
                    <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url('r_eks_asset') ?>" aria-expanded="false"><i class="fas fa-warehouse"></i><span class="hide-menu"><?= nbs(2) ?>Report Eks Asset</span></a></li>
                    <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url('data/foto') ?>" aria-expanded="false"><i class="fas fa-image"></i><span class="hide-menu"><?= nbs(2) ?>Foto Dokumen</span></a></li>
                    <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-file"></i><span class="hide-menu"><?= nbs(2) ?>Print Report</span></a>
                        <ul aria-expanded="false" class="collapse  first-level">
                            <li class="sidebar-item"><a href="<?= base_url('r_semua') ?>" class="sidebar-link"><i class="fas fa-file-alt"></i><?= nbs(2) ?><span class="hide-menu"> All Report </span></a></li>
                            <li class="sidebar-item"><a href="<?= base_url('r_laporan') ?>" class="sidebar-link"><i class="fas fa-file-alt"></i><?= nbs(2) ?><span class="hide-menu"> Laporan Keuangan </span></a></li>
                            <li class="sidebar-item"><a href="<?= base_url('r_print_report/dokumen_upload') ?>" class="sidebar-link"><i class="fas fa-file-alt"></i><?= nbs(2) ?><span class="hide-menu"> Dokumen Upload </span></a></li>
                            <li class="sidebar-item"><a href="<?= base_url('r_print_report/agunan') ?>" class="sidebar-link"><i class="fas fa-file-alt"></i><?= nbs(2) ?><span class="hide-menu"> Agunan / lain-lain </span></a></li>
                            <li class="sidebar-item"><a href="<?= base_url('r_print_report/upload_dokumen') ?>" class="sidebar-link"><i class="fas fa-upload"></i><?= nbs(2) ?><span class="hide-menu">Upload</span></a></li>
                        </ul> 
                    </li>
                <?php elseif ($this->session->userdata('level') == 10) : ?>

                    <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url('home/index/syariah') ?>" aria-expanded="false"><i class="fas fa-desktop"></i><span class="hide-menu"><?= nbs(2) ?>Dashboard</span></a></li>
                    <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url('r_ots/index/syariah') ?>" aria-expanded="false"><i class="fas fa-street-view"></i><span class="hide-menu"><?= nbs(2) ?>Report OTS</span></a></li>
                    <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url('r_noa/index/syariah') ?>" aria-expanded="false"><i class="fas fa-id-card"></i><span class="hide-menu"><?= nbs(2) ?>Report NOA</span></a></li>
                    <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url('r_recov/index/syariah') ?>" aria-expanded="false"><i class="fas fa-donate"></i><span class="hide-menu"><?= nbs(2) ?>Report Recoveries</span></a></li>
                    <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url('r_proses/index/syariah') ?>" aria-expanded="false"><i class="fas fa-exchange-alt"></i><span class="hide-menu"><?= nbs(2) ?>Report Proses</span></a></li>
                    <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url('r_semua/index/syariah') ?>" aria-expanded="false"><i class="fas fa-file"></i><span class="hide-menu"><?= nbs(2) ?>Print Report</span></a></li>

                <?php elseif ($this->session->userdata('level') == 13 || $this->session->userdata('level') == 14) : ?>

                    <?php if ($this->session->userdata('level') == 14) : 

                        $id_spk     = $this->session->userdata('id_spk'); 
                        $cabang_as  = $this->session->userdata('cabang_as');
                        
                        ?>

                        <?php if ($id_spk == '' && $cabang_as == '') : ?>

                            <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url('home/index/asuransi') ?>" aria-expanded="false"><i class="fas fa-desktop"></i><span class="hide-menu"><?= nbs(2) ?>Home</span></a></li>

                        <?php else: ?>

                            <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url("home/index/asuransi/$id_spk/$cabang_as") ?>" aria-expanded="false"><i class="fas fa-desktop"></i><span class="hide-menu"><?= nbs(2) ?>Dashboard</span></a></li>
                            <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url("r_ots/index/asuransi/$id_spk/$cabang_as") ?>" aria-expanded="false"><i class="fas fa-street-view"></i><span class="hide-menu"><?= nbs(2) ?>Report OTS</span></a></li>
                            <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url("r_noa/index/asuransi/$id_spk/$cabang_as") ?>" aria-expanded="false"><i class="fas fa-id-card"></i><span class="hide-menu"><?= nbs(2) ?>Report NOA</span></a></li>
                            <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url("r_recov/index/asuransi/$id_spk/$cabang_as") ?>" aria-expanded="false"><i class="fas fa-donate"></i><span class="hide-menu"><?= nbs(2) ?>Report Recoveries</span></a></li>
                            <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url("r_proses/index/asuransi/$id_spk/$cabang_as") ?>" aria-expanded="false"><i class="fas fa-exchange-alt"></i><span class="hide-menu"><?= nbs(2) ?>Report Proses</span></a></li>
                            <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url("r_semua/index/asuransi/$id_spk/$cabang_as") ?>" aria-expanded="false"><i class="fas fa-file"></i><span class="hide-menu"><?= nbs(2) ?>Print Report</span></a></li>

                        <?php endif; ?>

                    <?php else : ?>

                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url('home/index/asuransi') ?>" aria-expanded="false"><i class="fas fa-desktop"></i><span class="hide-menu"><?= nbs(2) ?>Dashboard</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url('r_ots/index/asuransi') ?>" aria-expanded="false"><i class="fas fa-street-view"></i><span class="hide-menu"><?= nbs(2) ?>Report OTS</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url('r_noa/index/asuransi') ?>" aria-expanded="false"><i class="fas fa-id-card"></i><span class="hide-menu"><?= nbs(2) ?>Report NOA</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url('r_recov/index/asuransi') ?>" aria-expanded="false"><i class="fas fa-donate"></i><span class="hide-menu"><?= nbs(2) ?>Report Recoveries</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url('r_proses/index/asuransi') ?>" aria-expanded="false"><i class="fas fa-exchange-alt"></i><span class="hide-menu"><?= nbs(2) ?>Report Proses</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= base_url('r_semua/index/asuransi') ?>" aria-expanded="false"><i class="fas fa-file"></i><span class="hide-menu"><?= nbs(2) ?>Print Report</span></a></li>

                    <?php endif; ?>

                <?php endif; ?>
                        
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>