<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_login extends CI_Model {

	public function cek_user_login($username)
	{
		$this->db->select('p.level, p.username, p.sha, p.id_karyawan');
		$this->db->from('pengguna as p');
		$this->db->join('level as l', 'l.id_level = p.level');
		$this->db->where('p.username', $username);

		return $this->db->get();
	}

	public function cari_data($tabel, $where)
	{
		return $this->db->get_where($tabel, $where);
		
	}


}

/* End of file M_login.php */
/* Location: ./application/models/M_login.php */