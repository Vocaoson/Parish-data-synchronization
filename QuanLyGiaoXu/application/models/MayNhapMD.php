<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MayNhapMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
	//Do your magic here
		$this->table="maynhap";
	}
	public function insertMayNhapMD($maDinhDanh,$tenMay,$maGiaoXuRieng)
	{
		$timestamp = time()+date("Z");
		$nowUTC= gmdate("Y-m-d H:i:s",$timestamp);
		$objectMayNhap=array(
			"MaDinhDanh"=>$maDinhDanh,
			"TenMay"=>$tenMay,
			"MaGiaoXuRieng"=>$maGiaoXuRieng,
			"PushDateOld"=>"1970-01-01 00:00:00",
			"PushDateNew"=>$nowUTC
			);
		$this->db->insert($this->table, $objectMayNhap);
		return $this->db->insert_id();
    }
    public function getAllMaDinhDanhByMaGiaoXuRieng($idgx)
	{
		$this->db->select('MaDinhDanh,TenMay');
		$this->db->where('MaGiaoXuRieng', $idgx);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function getDieuKienPull($maGiaoXuRieng,$maDinhDanh,$timestamp)
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$this->db->where('MaDinhDanh !=', $maDinhDanh);
		$this->db->where("PushDateNew >",date('Y-m-d H:i:s', $timestamp));
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function getMayNhap($maGiaoXuRieng,$maDinhDanh,$tenMay)
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$this->db->where('MaDinhDanh', $maDinhDanh);
		$this->db->where('TenMay', $tenMay);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function getAllMayNhapByMaGiaoXuRieng($idgx)
	{
		$this->db->select('TenMay');
		$this->db->where('MaGiaoXuRieng', $idgx);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function setPushDate($maGiaoXuRieng,$maDinhDanh,$pushDateNew)
	{
		//getpushdatenew
		$this->db->select('PushDateNew');
		$this->db->where('MaGiaoXuRieng',$maGiaoXuRieng);
		$this->db->where('MaDinhDanh',$maDinhDanh);
		$pushdateold=$this->db->get($this->table)->row();
		//update
		$this->db->set('PushDateOld',$pushdateold->PushDateNew);
		$this->db->set('PushDateNew', $pushDateNew);
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$this->db->where('MaDinhDanh', $maDinhDanh);
		$this->db->update($this->table);
	}
}

/* End of file MayNhapMD.php */
/* Location: ./application/models/MayNhapMD.php */