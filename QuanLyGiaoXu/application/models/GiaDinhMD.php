<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GiaDinhMD extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->table='GiaDinh';
	}
	private $table;
	public function delete($maGiaDinh,$maGiaoXu)
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
		$this->db->select('MaGiaDinh');
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	/*
	DK1 trùng mã nhận dạng
	 */
	public function getGiaDinhByDK1($maNhanDang,$maGiaoXu)
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXu);
		$this->db->where('MaNhanDang', $maNhanDang);
		$query=$this->db->get($this->table);
		return $query->row();
		
	}
	/*
	ĐK2 trùng Tên gia đình,địa chỉ, sdt,ghi chu
	 */
	public function getGiaDinhByDK2($maGiaoXu,$name,$add,$sdt,$note)
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXu);
		$this->db->where('TenGiaDinh', $name);
		$this->db->where('DiaChi', $add);
		$this->db->where('DienThoai', $sdt);
		$this->db->where('GhiChu', $note);
		$query=$this->db->get($this->table);
		return $query->row();
	}
	/*
	update
	 */
	public function update($data,$MaGiaDinh)
	{
		$data['MaGiaDinh']=$MaGiaDinh;
		$this->db->update($this->table, $data);
	}
	/*
	insert gia dinh
	 */
	public function insert($data)
	{
		unset($data['MaGiaDinh']);
		unset($data['UpdateDate']);
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
}

/* End of file GiaDinhMD.php */
/* Location: ./application/models/GiaDinhMD.php */