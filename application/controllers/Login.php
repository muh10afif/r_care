<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * LOGIN
 */
class Login extends CI_Controller
{
   function __construct()
   {
      parent::__construct();
      
      $this->load->model(array('M_login'));
   }

   public function hapus_debitur()
   {
      $data = ['9360', '9373', '9407', '9447', '9469', '9506', '9527', '9535', '9537', '9602', '9607', '9744', '9785', '8998', '9001', '9030', '9037','9041','9060','9071','9081','9082','9089','9126','9129','9145', '9863'];
  
      foreach ($data as $d) {
         $this->db->delete('debitur', array('id_debitur' => $d));
      }

      echo "sukses ".count($data)." terhapus";
   }

   public function index()
   {
      if ($this->session->userdata('akses') == 'Manager') {

         $this->load->library('Cek_login_lib');
         $this->cek_login_lib->logged_in_3();

      } elseif ($this->session->userdata('akses') == 'Manager Syariah') {
         
         $this->load->library('Cek_login_lib_syariah');
         $this->cek_login_lib_syariah->logged_in_3();

      }

   	$this->load->view('V_login');
   }

   public function cek_login()
   {
   		$u = $this->input->post('username', TRUE);
   		$p = $this->input->post('password', TRUE);

   		$username = trim(htmlspecialchars($u, ENT_QUOTES)); 
   		$password = trim(htmlspecialchars($p, ENT_QUOTES)); 

   		$cek_username = $this->M_login->cek_user_login($username);

         if ($cek_username->num_rows() != 0) {
            $data = $cek_username->row_array();
            // cek password dengan verify
            if (password_verify($password,$data['sha'])) {
               
               if ($data['level'] == 4) {
                  $array = array(
                     'akses'     => 'Manager',
                     'masuk'     => 'r_care',
                     'username'  => $data['username'],
                     'level'     => $data['level']
                  );
                  
                  $this->session->set_userdata( $array );

                  //redirect('home');
                  echo json_encode(['hasil'  => 'home']);

               } elseif ($data['level'] == 10) {

                  $array = array(
                     'akses'     => 'Manager Syariah',
                     'masuk'     => 'r_care',
                     'username'  => $data['username'],
                     'level'     => $data['level']
                  );
                  
                  $this->session->set_userdata( $array );

                  // redirect('askSyariah/HomeSyariah');
                  echo json_encode(['hasil'  => 'HomeSyariah']);
                  
               } elseif ($data['level'] == 13) {

                  $nm_asuransi = $this->M_login->cari_data('m_cabang_asuransi', array('id_cabang_asuransi' => $data['id_karyawan']))->row_array();

                  // mencari cabang asuransi dari spk  
                  $id_spk  = $this->M_login->cari_data('spk', array('id_cabang_asuransi' => $data['id_karyawan'], 'status' => 1))->row_array();

                  $nm_spk  = $this->M_login->cari_data('spk', array('id_spk' => $id_spk['id_spk']))->row_array();

                  $array = array(
                     'akses'     => 'user_asuransi',
                     'masuk'     => 'r_care',
                     'username'  => $data['username'],
                     'level'     => $data['level'],
                     'cabang_as' => $data['id_karyawan'],
                     'nama_as'   => " - ".$nm_asuransi['cabang_asuransi']." - <small> No. Spk ".$nm_spk['no_spk']."</small>",
                     'id_spk'    => $nm_spk['id_spk']
                  );
                  
                  $this->session->set_userdata( $array );

                  echo json_encode(['hasil'  => 'user_asuransi']);
                  
               } elseif ($data['level'] == 14) {
                  
                  $nm_korwil = $this->M_login->cari_data('m_korwil_asuransi', array('id_korwil_asuransi' => $data['id_karyawan']))->row_array();

                  $array = array(
                     'akses'     => 'kanwil_asuransi',
                     'masuk'     => 'r_care',
                     'username'  => $data['username'],
                     'level'     => $data['level'],
                     'kanwil_as' => $data['id_karyawan'],
                     'nama_as'   => " - ".$nm_korwil['korwil_asuransi']
                  );
                  
                  $this->session->set_userdata( $array );
                  
                  echo json_encode(['hasil' => 'kanwil_asuransi']);

               } else {
                  // $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Anda tidak mempunyai hak akses masuk!</div>');
                  // redirect('login');
                  echo json_encode(['hasil'  => 3]);
               }

            } else {
               // $this->session->set_flashdata('pesan', '<div class="alert alert-danger" style="margin-right=20px;">Username atau Password salah!</div>');
               // redirect('login');
               echo json_encode(['hasil'  => 1]);
            }

         } else {
            // $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Username tidak temukan!</div>');
            // redirect('login');
            echo json_encode(['hasil'  => 0]);
         }

   }

   public function tes()
   {
      $a = password_hash('kanwil_rcare', PASSWORD_DEFAULT);
      echo $a;
      // $a = '$2y$10$c4ANBK0QgzlhxvhAo007v.BQu8Z9UspJ5K0CSwMX9vTOhupwhbNFa';

      // if (password_verify('manager', $a)) {
      //    echo 'Password is valid';
      // } else {
      //    echo 'Invalid Password';
      // }

   }

   public function keluar()
   {
      $us = $this->session->userdata('masuk');
        
      if ($us == 'r_care') {
         $this->session->sess_destroy();
         redirect(base_url());  
      } else {
         redirect(base_url());  
      } 

      // $this->session->sess_destroy();
      // redirect('login','refresh');
   }
}