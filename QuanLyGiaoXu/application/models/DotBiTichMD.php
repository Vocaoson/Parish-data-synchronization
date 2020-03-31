<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DotBiTichMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table="dotbitich";
	}
	public function delete($objectSV)
	{
		$this->db->set('DeleteSV',1);
		$this->db->where('MaDotBiTich', $objectSV->MaDotBiTich);
		$this->db->where('MaGiaoXuRieng', $objectSV->MaGiaoXuRieng);
		$this->db->update($this->table);
	}
	public function update($data)
	{
		$maDotBiTich=$data['MaDotBiTich'];
		unset($data['MaDotBiTich']);
		unset($data['DeleteClient']);
		unset($data['KhoaChinh']);
		$data['DeleteSV']=0;
		$this->db->where('MaDotBiTich', $maDotBiTich);
		$this->db->update($this->table, $data);
	}
	public function insert($data)
	{
		unset($data['MaDotBiTich']);
		unset($data['DeleteClient']);
		unset($data['KhoaChinh']);
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	public function getByMaDotBiTich($maDotBiTich){
		$this->db->where("MaDotBiTich",$maDotBiTich);
		return $this->db->get($this->table)->row();
	}
	public function getByInfo($dieuKien,$maGiaoXuRieng)
	{
		$this->db->where($dieuKien);
		$this->db->where('MaGiaoXuRieng',$maGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->row();
	}


	//Tạm thời xóa
	/*
	public function getAllActive($maGiaoXu)
	{

		$this->db->where('MaGiaoXuRieng', $maGiaoXu);
		$query=$this->db->get($this->table);
		$data['field']=$this->db->list_fields($this->table);
		$data['data']= $query->result();
		return $data;

	}
	public function deleteReal($dataSV)
	{
		$this->db->where('MaDotBiTich', $dataSV->MaDotBiTich);
		$this->db->where('MaGiaoXuRieng', $dataSV->MaGiaoXuRieng);

		$this->db->delete($this->table);
	}
	public function getAll($maGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function delete($MaDotBiTich,$maGiaoXuRieng)
	{
		$this->db->set('DeleteSV',1);
		$this->db->where('MaDotBiTich', $MaDotBiTich);
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		
		$this->db->update($this->table);
	}
	
	
	public function insert($data)
	{
		unset($data['MaDotBiTich']);
		unset($data['UpdateDate']);
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	public function update($data,$maDotBiTich)
	{
		unset($data['MaDotBiTich']);
		$this->db->where('MaDotBiTich', $maDotBiTich);
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		$this->db->update($this->table,$data);

	}
	*/
}

/* End of file DotBiTichMD.php */
/* Location: ./application/models/DotBiTichMD.php */