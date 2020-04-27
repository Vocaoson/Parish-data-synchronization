<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ChuyenXuMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table="chuyenxu";
	}
	public function getByMaChuyenXu($maChuyenXu)
	{
		$this->db->where("MaChuyenXu",$maChuyenXu);
		return $this->db->get($this->table)->row();
	}
	public function getByMaGiaoDan($maGiaoDan)
	{
		$this->db->where("MaGiaoDan",$maGiaoDan);
		return $this->db->get($this->table)->row();
	}
	public function delete($objectSV)
	{
		$this->db->set('DeleteSV',1);
		$this->db->where('MaChuyenXu', $objectSV->MaChuyenXu);
		$this->db->where('MaGiaoXuRieng', $objectSV->MaGiaoXuRieng);
		$this->db->update($this->table);
	}
	public function update($data)
	{
		$maChuyenXu=$data['MaChuyenXu'];
		unset($data['MaChuyenXu']);
		unset($data['KhoaChinh']);
		unset($data['KhoaNgoai']);
		unset($data['DeleteClient']);
		$data['DeleteSV']=0;
		$this->db->where('MaChuyenXu', $maChuyenXu);
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		$this->db->update($this->table, $data);
	}
	public function insert($data)
	{
		unset($data['MaChuyenXu']);
		unset($data['KhoaChinh']);
		unset($data['KhoaNgoai']);
		unset($data['DeleteClient']);;
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}  
	public function getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh)
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$this->db->where('MaDinhDanh !=', $maDinhDanh);
		$query=$this->db->get($this->table);
		$data['field']=$this->db->list_fields($this->table);
		$data['data']= $query->result();
		return $data;
	}
	//Tạm xóa
	/*
	public function deleteById($maChuyenXu,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaChuyenXu', $maChuyenXu);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
	public function getAll($MaGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		$this->db->where('DeleteSV', 0);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function getByIdGiaoDan($maGiaoDan)
	{
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$query=$this->db->get($this->table);
		return $query->result();
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

/* End of file ChuyenXuMD.php */
/* Location: ./application/models/ChuyenXuMD.php */