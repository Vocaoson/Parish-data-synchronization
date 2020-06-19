<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GiaoDanHonPhoiMD extends CI_Model {
	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table="giaodanhonphoi";
	}

	public function getByMaHonPhoiMaGiaoDan($maHonPhoi,$maGiaoDan)
	{
		$this->db->where('MaHonPhoi', $maHonPhoi);
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
		$maHonPhoi=$data['MaHonPhoi'];
		$maGiaoDan=$data['MaGiaoDan'];
		unset($data['DeleteClient']);
		unset($data['MaHonPhoi']);
		unset($data['MaGiaoDan']);
		unset($data['KhoaChinh']);
		$data['DeleteSV']=0;
		$this->db->where('MaHonPhoi', $maHonPhoi);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$this->db->update($this->table, $data);
	}
	public function delete($data)
	{
		$this->db->set('DeleteSV',1);
		$this->db->set('MaDinhDanh',$data['MaDinhDanh']);
		$this->db->set('UpdateDate',$data['UpdateDate']);
		$this->db->where('MaGiaoDan', $data['MaGiaoDan']);
		$this->db->where('MaHonPhoi', $data['MaHonPhoi']);
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		$this->db->update($this->table);
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

	//tạm xóa
	/*
	public function getAll($MaGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function deleteTwoKey($MaHonPhoi,$MaGiaoDan,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaGiaoDan', $MaGiaoDan);
		$this->db->where('MaHonPhoi', $MaHonPhoi);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
	public function deleteMaHonPhoi($MaHonPhoi,$MaGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		$this->db->where('MaHonPhoi', $MaHonPhoi);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
	public function delete($maGiaoDan,$MaDotBiTich,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$this->db->where('MaDotBiTich', $MaDotBiTich);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
	public function insert($data,$maGiaoDan,$MaHonPhoi)
	{
		unset($data['UpdateDate']);
		//2018/10/29 son add start
		unset($data['DeleteClient']);
		//2018/10/29 son add start
		$data['MaHonPhoi']=$MaHonPhoi;
		$data['MaGiaoDan']=$maGiaoDan;
		$this->db->insert($this->table, $data);
	}
	public function update($data,$maGiaoDan,$MaHonPhoi)
	{
		unset($data['UpdateDate']);
		//2018/10/29 son add start
		unset($data['DeleteClient']);
		//2018/10/29 son add start
		unset($data['MaHonPhoi']);
		unset($data['MaGiaoDan']);
		$this->db->where('MaHonPhoi', $MaHonPhoi);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		$this->db->update($this->table, $data);
	}
	public function findwithID($MaHonPhoi,$maGiaoDan,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaHonPhoi', $MaHonPhoi);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$query=$this->db->get($this->table);
		return $query->row();
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

/* End of file GiaoDanHonPhoiMD.php */
/* Location: ./application/models/GiaoDanHonPhoiMD.php */