<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BiTichChiTietMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table='bitichchitiet';
	}
	public function getByMaDotBiTichMaGiaoDan($maDotBiTich,$maGiaoDan)
	{
		$this->db->where('MaDotBiTich', $maDotBiTich);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$query=$this->db->get($this->table);
		return $query->row();
	}
	public function insert($data)
	{
		unset($data['KhoaChinh']);
		unset($data['DeleteClient']);
		$this->db->insert($this->table, $data);
	}
	public function update($data)
	{
		$maDotBiTich=$data['MaDotBiTich'];
		$maGiaoDan=$data['MaGiaoDan'];
		unset($data['DeleteClient']);
		unset($data['MaDotBiTich']);
		unset($data['MaGiaoDan']);
		unset($data['KhoaChinh']);
		$data['DeleteSV']=0;
		$this->db->where('MaDotBiTich', $maDotBiTich);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$this->db->update($this->table, $data);
	}
	public function delete($objectSV)
	{
		$this->db->set('DeleteSV',1);
		$this->db->where('MaDotBiTich', $objectSV->MaDotBiTich);
		$this->db->where('MaGiaoDan', $objectSV->MaGiaoDan);
		$this->db->update($this->table);
	}


	//Tạm xóa
	/*
	public function getAllActive($maGiaoXu)
	{

		$this->db->where('MaGiaoXuRieng', $maGiaoXu);
		$query=$this->db->get($this->table);
		$data['field']=$this->db->list_fields($this->table);
		$data['data']= $query->result();
		return $data;

	}
	public function getAll($maGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function deleteTwoKey($MaDotBiTich,$MaGiaoDan,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaGiaoDan', $MaGiaoDan);
		$this->db->where('MaDotBiTich', $MaDotBiTich);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
	
	public function deleteMaDotBiTich($MaDotBiTich,$MaGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		$this->db->where('MaDotBiTich', $MaDotBiTich);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
	
	
	
	public function deleteMaGiaoDan($maGiaoDan,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
	*/
}

/* End of file BiTichChiTietMD.php */
/* Location: ./application/models/BiTichChiTietMD.php */