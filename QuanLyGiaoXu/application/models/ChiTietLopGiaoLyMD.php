<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ChiTietLopGiaoLyMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table="chitietlopgiaoly";
	}
	public function getByMaLopMaGiaoDan($maLop,$maGiaoDan)
	{
		$this->db->where("MaLop",$maLop);
		$this->db->where("MaGiaoDan",$maGiaoDan);
		return $this->db->get($this->table)->row();
	}
	public function insert($data){
		unset($data["KhoaChinh"]);
		unset($data["DeleteClient"]);
		$this->db->insert($this->table,$data);
	}
	public function delete($objectSV)
	{
		$this->db->set("DeleteSV",1);
		$this->db->where("MaLop",$objectSV->MaLop);
		$this->db->where("MaGiaoDan",$objectSV->MaGiaoDan);
		$this->db->update($this->table);
	}
	public function update($data)
	{
		$maLop=$data['MaLop'];
		$maGiaoDan=$data['MaGiaoDan'];
		unset($data['DeleteClient']);
		unset($data['MaLop']);
		unset($data['MaGiaoDan']);
		unset($data['KhoaChinh']);
		$data['DeleteSV']=0;
		$this->db->where('MaLop', $maLop);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$this->db->update($this->table,$data);
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
	public function getAll($MaGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	
	public function deleteTwoKey($MaLop,$MaGiaoDan,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaGiaoDan', $MaGiaoDan);
		$this->db->where('MaLop', $MaLop);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
	public function deleteMaLop($MaLop,$MaGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		$this->db->where('MaLop', $MaLop);
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
	public function getByMaLop($MaLop,$MaGiaoXuRieng)
	{
		
		$this->db->where('MaLop', $MaLop);
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function findwithID($MaLop,$maGiaoDan,$maGiaoXu)
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXu);
		$this->db->where('MaLop', $MaLop);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$query=$this->db->get($this->table);
		return $query->row();
	}
	*/
}

/* End of file ChiTietLopGiaoLyMD.php */
/* Location: ./application/models/ChiTietLopGiaoLyMD.php */