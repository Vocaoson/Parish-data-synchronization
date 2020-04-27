<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LopGiaoLyMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table='lopgiaoly';
	}
	public function getByInfo($dieuKien,$MaGiaoXuRieng){
		$this->db->select('*');
		$this->db->where($dieuKien);
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->row();
	}
	public function delete($objectSV)
	{
		$this->db->set('DeleteSV',1);
		$this->db->where('MaLop', $objectSV->MaLop);
		$this->db->where('MaGiaoXuRieng', $objectSV->MaGiaoXuRieng);
		$this->db->update($this->table);
	}
	public function update($data)
	{
		$maLop=$data['MaLop'];
		unset($data['MaLop']);
		unset($data['KhoaChinh']);
		unset($data['KhoaNgoai']);
		unset($data['DeleteClient']);
		$data['DeleteSV']=0;
		$this->db->where('MaLop', $maLop);
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		$this->db->update($this->table, $data);
	}
	public function insert($data)
	{
		unset($data['MaLop']);
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
	public function getByMaLop($maLop)
	{
		$this->db->where('MaLop', $maLop);
		$query=$this->db->get($this->table);
		return $query->row();
	}
	//Tạm xóa
	/*
	public function getAll($MaGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function deleteReal($dataSV)
	{
		$this->db->where('MaLop', $dataSV->MaLop);
		$this->db->where('MaGiaoXuRieng', $dataSV->MaGiaoXuRieng);
		$this->db->delete($this->table);
	}
	public function insert($data)
	{
		unset($data['MaLop']);
		unset($data['UpdateDate']);
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	public function update($data,$MaLop)
	{
		unset($data['MaLop']);
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		$this->db->where('MaLop', $MaLop);
		$this->db->update($this->table, $data);
	}	

	public function deleteMaLop($MaLop,$MaGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		$this->db->where('MaLop', $MaLop);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
	public function getByMaKhoi($MaKhoi,$MaGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		
		$this->db->where('MaKhoi', $MaKhoi);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function getByDK1($data)
	{
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		

		$this->db->where('TenLop', $data['TenLop']);
		$this->db->where('Nam', $data['Nam']);
		$this->db->where('MaKhoi', $data['MaKhoi']);
		$query=$this->db->get($this->table);
		return $query->row();
	}
*/
}

/* End of file LopGiaoLyMD.php */
/* Location: ./application/models/LopGiaoLyMD.php */