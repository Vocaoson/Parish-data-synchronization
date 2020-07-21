<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BackupMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->table="backup";
	}
	public function getPathByID($id=-1)
	{
		if ($id!=-1) {
			$this->db->select('PathFile');
			$this->db->where('Id', $id);
			$query=$this->db->get($this->table);
			return $query->row();
		}
	}
	public function removeFile($id=-1)
	{
		if ($id!=-1) {
			$this->db->where('ID', $id);
			$this->db->delete($this->table);		
		}
	}
	public function getIdRemove($maGiaoXuRieng,$maDinhDanh)
	{
		$this->db->select('ID,PathFile');
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$this->db->where('MaDinhDanh', $maDinhDanh);
		$this->db->order_by('ID', 'ASC');
		$this->db->limit(1); 
		$query=$this->db->get($this->table);
		return $query->row();
	}
	public function countFile($maGiaoXuRieng,$maDinhDanh)
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$this->db->where('MaDinhDanh', $maDinhDanh);
		return $this->db->count_all_results($this->table);
	}
	public function getBackupGx($idgx,$maDinhDanh)
	{
		$this->db->where('MaGiaoXuRieng', $idgx);
		$this->db->where('MaDinhDanh', $maDinhDanh);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function getAllBackupByMaDinhDanh($maDinhDanh,$maGiaoXuRieng)
	{
		$this->db->where('MaDinhDanh',$maDinhDanh);
		$this->db->where('MaGiaoXuRieng',$maGiaoXuRieng);
		$this->db->order_by('ID', 'DESC');
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function getLastUploadMD($maGiaoXuRieng)
	{
		$this->db->select('Time');
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->row();
	}
	public function insertGiaoXuBackupMD($name,$path,$idgx,$dateUL,$maDinhDanh,$tenMay,$tongGiaoHo,$tongGiaoDan,$tongGiaDinh,$tongHonPhoi)
	{
		$objectBK=array(
			"Name"=>$name,
			"PathFile"=>$path,
			"Time"=>$dateUL,
			"MaGiaoXuRieng"=>$idgx,
			"MaDinhDanh"=>$maDinhDanh,
			"TenMay"=>$tenMay,
			"TongGiaoHo"=>$tongGiaoHo,
			"TongGiaoDan"=>$tongGiaoDan,
			"TongGiaDinh"=>$tongGiaDinh,
			"TongHonPhoi"=>$tongHonPhoi
	);
		$this->db->insert($this->table, $objectBK);
		return $this->db->affected_rows();
	}
}

/* End of file GiaoXuBackupMD.php */
/* Location: ./application/models/GiaoXuBackupMD.php */