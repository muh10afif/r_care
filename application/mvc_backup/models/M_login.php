<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_login extends CI_Model {

	public function cek_user_login($username)
	{
		$this->db->select('l.level, p.username, p.sha');
		$this->db->from('pengguna as p');
		$this->db->join('level as l', 'l.id_level = p.level');
		$this->db->where('p.username', $username);

		return $this->db->get();
	}

}

/* End of file M_login.php */
/* Location: ./application/models/M_login.php */