<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GiaDinhMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table='giadinh';
	}
	
	public function getByMaGiaDinh($maGiaDinh) {
		$this->db->select('*');
		$this->db->where('MaGiaDinh', $maGiaDinh);
		$query=$this->db->get($this->table);
		return $query->row();
	}
	public function getByMaNhanDang($maNhanDang,$maGiaoXu)
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXu);
		$this->db->where('MaNhanDang', $maNhanDang);
		$query=$this->db->get($this->table);
		return $query->row();
	}
	public function getByInfo($maGiaoXuRieng,$tenGiaDinh,$diaChi,$sdt,$maGiaDinhRieng)
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$this->db->where('TenGiaDinh', $tenGiaDinh);
		$this->db->where('DiaChi', $diaChi);
		$this->db->where('DienThoai', $sdt);
		$this->db->where('MaGiaDinhRieng', $maGiaDinhRieng);
		$query=$this->db->get($this->table);
		return $query->row();
	}
	public function delete($data)
	{
		$this->db->set('DeleteSV',1);
		$this->db->set('MaDinhDanh',$data['MaDinhDanh']);
		$this->db->set('UpdateDate',$data['UpdateDate']);
		$this->db->where('MaGiaDinh', $data['MaGiaDinh']);
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		$this->db->update($this->table);
	}
	public function update($data)
	{
		$maGiaDinh=$data['MaGiaDinh'];
		unset($data['MaGiaDinh']);
		unset($data['KhoaChinh']);
		unset($data['KhoaNgoai']);
		unset($data['DeleteClient']);
		$data['DeleteSV']=0;
		$this->db->where('MaGiaDinh', $maGiaDinh);
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		$this->db->update($this->table, $data);
	}
	public function insert($data)
	{
		unset($data['MaGiaDinh']);
		unset($data['KhoaChinh']);
		unset($data['KhoaNgoai']);
		unset($data['DeleteClient']);;
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
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
	public function deleteMaGiaDinh($maGiaDinh,$maGiaoXu)
	{
		$this->db->set('DeleteSV',1);
		$this->db->where('MaGiaDinh', $maGiaDinh);
		$this->db->where('MaGiaoXuRieng', $maGiaoXu);
		$this->db->update($this->table);
	}
	public function deleteByGiaoHo($maGiaoHo,$maGiaoXuRieng)
	{
		
	}
	public function getByMaGiaoHo($maGiaoHo,$maGiaoXuRieng)
	{
		$this->db->select('MaGiaDinh');
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$this->db->where('MaGiaoHo', $maGiaoHo);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function getAllListIDGiaDinh($maGiaoXuRieng)
	{
		// $this->db->select('MaGiaDinh');
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	*/
	
}

/* End of file GiaDinhMD.php */
/* Location: ./application/models/GiaDinhMD.php */