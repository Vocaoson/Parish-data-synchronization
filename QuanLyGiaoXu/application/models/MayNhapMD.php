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
			"PushDate"=>"1970-01-01 00:00:00"
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
		$this->db->where("PushDate >",date('Y-m-d H:i:s', $timestamp));
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
		$this->db->set('PushDate', $pushDateNew);
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$this->db->where('MaDinhDanh', $maDinhDanh);
		$this->db->update($this->table);
	}
}

/* End of file MayNhapMD.php */
/* Location: ./application/models/MayNhapMD.php */