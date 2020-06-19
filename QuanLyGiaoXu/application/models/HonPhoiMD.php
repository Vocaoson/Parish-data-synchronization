<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HonPhoiMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table='honphoi';
	}
	public function delete($data)
	{
		$this->db->set('DeleteSV',1);
		$this->db->set('MaDinhDanh',$data['MaDinhDanh']);
		$this->db->set('UpdateDate',$data['UpdateDate']);
		$this->db->where('MaHonPhoi', $data['MaHonPhoi']);
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		$this->db->update($this->table);
	}
	public function update($data)
	{
		$maHonPhoi=$data['MaHonPhoi'];
		unset($data['MaHonPhoi']);
		unset($data['DeleteClient']);
		unset($data['KhoaChinh']);
		$data['DeleteSV']=0;
		$this->db->where('MaHonPhoi', $maHonPhoi);
		$this->db->update($this->table, $data);
	}
	public function insert($data)
	{
		unset($data['MaHonPhoi']);
		unset($data['DeleteClient']);
		unset($data['KhoaChinh']);
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	public function getByMaHonPhoi($maHonPhoi)
	{
		$this->db->where('MaHonPhoi', $maHonPhoi);
		$query=$this->db->get($this->table);
		return $query->row();
	}
	public function getByMaNhanDang($maNhanDang,$maGiaoXuRieng)
	{
		$this->db->where('MaNhanDang', $maNhanDang);
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->row();
	}
	public function getByInfo($dieuKien,$maGiaoXuRieng)
	{
		$this->db->where($dieuKien);
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->row();
	}  
	public function getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh,$dieukien)
	{
		$this->db->where($dieukien);
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$this->db->where('MaDinhDanh !=', $maDinhDanh);
		$query=$this->db->get($this->table);
		$data['field']=$this->db->list_fields($this->table);
		$data['data']= $query->result();
		return $data;
	}


	//Tạm thời xóa
	/*
	public function getAll($maGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function deleteReal($dataSV)
	{
		$this->db->where('MaHonPhoi', $dataSV->MaHonPhoi);
		$this->db->where('MaGiaoXuRieng', $dataSV->MaGiaoXuRieng);

		$this->db->delete($this->table);
	}
	*/
}

/* End of file HonPhoiMD.php */
/* Location: ./application/models/HonPhoiMD.php */