<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cek_login_lib
{
    public function logged_in()
    {
    	$_this =& get_instance();
    	if ($_this->session->userdata('masuk') != 'r_care') {
    		redirect('login','refresh');
    	}
    }

    public function logged_in_2()
    {
    	$_this =& get_instance();
    	if ($_this->session->userdata('masuk') == 'r_care') {
    		redirect('home','refresh');
    	}
    }

    public function logged_in_3()
    {
        $_this =& get_instance();
        if ($_this->session->userdata('masuk') == 'r_care') {
            redirect('home','refresh');
        }
    }
}