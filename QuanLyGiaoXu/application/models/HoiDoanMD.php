<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HoiDoanMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table="hoidoan";
	}
	public function delete($data)
	{
		$this->db->set('DeleteSV',1);
		$this->db->set('MaDinhDanh',$data['MaDinhDanh']);
		$this->db->set('UpdateDate',$data['UpdateDate']);
		$this->db->where('MaHoiDoan', $data['MaHoiDoan']);
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		$this->db->update($this->table);
	}
	public function update($data)
	{
		$maHoiDoan=$data['MaHoiDoan'];
		unset($data['MaHoiDoan']);
		unset($data['DeleteClient']);
		unset($data['KhoaChinh']);
		$data['DeleteSV']=0;
		$this->db->where('MaHoiDoan', $maHoiDoan);
		$this->db->update($this->table, $data);
	}
	public function insert($data)
	{
		unset($data['MaHoiDoan']);
		unset($data['DeleteClient']);
		unset($data['KhoaChinh']);
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	public function getByMaHoiDoan($maHoiDoan){
		$this->db->where("MaHoiDoan",$maHoiDoan);
		return $this->db->get($this->table)->row();
	}
	public function getByInfo($dieuKien,$maGiaoXuRieng)
	{
		$this->db->where($dieuKien);
		$this->db->where('MaGiaoXuRieng',$maGiaoXuRieng);
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

}

/* End of file HoiDoanMD.php */
/* Location: ./application/models/HoiDoanMD.php */