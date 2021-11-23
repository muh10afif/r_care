<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cek_login_lib_syariah
{
	public function logged_in()
    {
    	$_this =& get_instance();
    	if ($_this->session->userdata('masuk') != TRUE) {
    		redirect('login','refresh');
    	}
    }

    public function logged_in_2()
    {
    	$_this =& get_instance();
    	if ($_this->session->userdata('masuk') == TRUE) {
    		redirect('askSyariah/HomeSyariah','refresh');
    	}
    }

    public function logged_in_3()
    {
    	$_this =& get_instance();
    	if ($_this->session->userdata('masuk') == TRUE) {
    		redirect('askSyariah/HomeSyariah','refresh');
    	}
    }

}

/* End of file Cek_login_lib_syariah.php */
/* Location: ./application/libraries/Cek_login_lib_syariah.php */
