<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GiaoHatMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
	//Do your magic here
		$this->table="giaohat";
		
	}
	public function insertMD($maGiaoPhan,$name,$note)
	{
		$objectGH=array(
			"MaGiaoPhan"=>$maGiaoPhan,
			"TenGiaoHat"=>$name,
			"GhiChu"=>$note);
		$this->db->insert($this->table, $objectGH);
		return $this->db->insert_id();
	}
	public function getGHjsonMD($idGP)
	{
		$this->db->select('MaGiaoHat,TenGiaoHat,status');
		$this->db->where('MaGiaoPhan', $idGP);
		$this->db->where('status = 1');
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function updateStatus($id){
		$data = array('status'=>1);
		return $this->db->update('giaohat',$data,"MaGiaoHat = $id");
	}

}

/* End of file GiaoHatMD.php */
/* Location: ./application/models/GiaoHatMD.php */