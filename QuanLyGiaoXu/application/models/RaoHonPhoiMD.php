<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RaoHonPhoiMD extends CI_Model {
	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table="RaoHonPhoi";
	}
	public function deleteMaGiaoDan($maGiaoDan,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaGiaoDan1', $maGiaoDan);
		$this->db->or_where('MaGiaoDan2', $maGiaoDan);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}

	

}

/* End of file RaoHonPhoiMD.php */
/* Location: ./application/models/RaoHonPhoiMD.php */