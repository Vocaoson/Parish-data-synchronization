<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GiaoPhanMD extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->table="giaophan";
	}
	public function getAllActive($maGiaoXu,$timeClient)
	{

		$this->db->where('MaGiaoXuRieng', $maGiaoXu);
		$this->db->where('UpdateDate>', $timeClient);
		$query=$this->db->get($this->table);
		$data['field']=$this->db->list_fields($this->table);
		$data['data']= $query->result();
		return $data;

	}
	public function insertMD($name,$note,$status=0)
	{
		$objectGP=array(
			"TenGiaoPhan"=>$name,
			"GhiChu"=>$note,
			"status"=>$status);
		$this->db->insert($this->table, $objectGP);
		return $this->db->insert_id();	
	}
	public function getGPjsonMD()
	{
		$this->db->select('MaGiaoPhan,TenGiaoPhan,status');
		$this->db->where("status = 1");
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function getGPjsonMDWeb()
	{
		$this->db->select('MaGiaoPhan,TenGiaoPhan,status');
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function updateStatus($id){
		$data = array('status'=>1);
		return $this->db->update($this->table,$data,"MaGiaoPhan = $id");
	}
}

/* End of file GiaoPhanMD.php */
/* Location: ./application/models/GiaoPhanMD.php */