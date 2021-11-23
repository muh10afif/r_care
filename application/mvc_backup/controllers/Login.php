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

   public function index()
   {
      $this->load->library('Cek_login_lib');
      $this->cek_login_lib->logged_in_2();

   	$this->load->view('V_login');
   }

   public function cek_login()
   {
      $this->load->library('Cek_login_lib');
      $this->cek_login_lib->logged_in_2();

   		$u = $this->input->post('username', TRUE);
   		$p = $this->input->post('password', TRUE);

   		$username = trim(htmlspecialchars($u, ENT_QUOTES)); 
   		$password = trim(htmlspecialchars($p, ENT_QUOTES)); 

   		$cek_username = $this->M_login->cek_user_login($username);

         if ($cek_username->num_rows() != 0) {
            $data = $cek_username->row_array();
            // cek password dengan verify
            if (password_verify($password,$data['sha'])) {
               
               if ($data['level'] == 'manajer subrogasi') {
                  $array = array(
                     'akses'     => 'Manager',
                     'masuk'     => TRUE,
                     'username'  => $data['username']
                  );
                  
                  $this->session->set_userdata( $array );

                  redirect('home');


               } elseif ($data['level'] == 'R Care Asuransi') {

                  $array = array(
                     'akses'     => 'Manager Syariah',
                     'masuk'     => TRUE,
                     'username'  => $data['username']
                  );
                  
                  $this->session->set_userdata( $array );

                  redirect('askSyariah/HomeSyariah');
                  
               } else {
                  $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Anda tidak mempunyai hak akses masuk!</div>');
                  redirect('login');
               }

            } else {
               $this->session->set_flashdata('pesan', '<div class="alert alert-danger" style="margin-right=20px;">Username atau Password salah!</div>');
               redirect('login');
            }

         } else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Username tidak temukan!</div>');
               redirect('login');
         }

   }

   public function tes()
   {
      // $a = password_hash('manager', PASSWORD_DEFAULT);
      //echo $a;
      $a = '$2y$10$c4ANBK0QgzlhxvhAo007v.BQu8Z9UspJ5K0CSwMX9vTOhupwhbNFa';

      if (password_verify('manager', $a)) {
         echo 'Password is valid';
      } else {
         echo 'Invalid Password';
      }

   }

   public function keluar()
   {
      $this->session->sess_destroy();
      redirect('login','refresh');
   }
}