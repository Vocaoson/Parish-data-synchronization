<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GiaoXuMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table="giaoxu";
	}
	//////
	public function getGiaoXuByMaGiaoXuRieng($maGiaoXuRieng)
	{
		$this->db->where("MaGiaoXuRieng",$maGiaoXuRieng);
		return $this->db->get($this->table)->row();
	}
	public function setLockSync($maGiaoXuRieng,$lock=0)
	{
		$this->db->set("lockSync",$lock);
		$this->db->where("MaGiaoXuRieng",$maGiaoXuRieng);
		$this->db->update($this->table);
	}
	//////////


	public function getAllActive($maGiaoXu)
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXu);
		$this->db->where('DeleteSV', 0);
		$query=$this->db->get($this->table);
		$data['field']=$this->db->list_fields($this->table);
		$data['data']= $query->result();
		return $data;
	}
	public function checkStatus($maGiaoXuRieng)
	{
		$this->db->select('status');
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->row();
	}
	public function downloadImgMD($id)
	{
		$this->db->select('Hinh');
		$this->db->where('MaGiaoXuRieng', $id);
		$query=$this->db->get($this->table);
		return $query->row();
	}
	public function insertMD($TenGiaoXu,$DiaChi,$DienThoai,$Email,$Website,$Hinh,$GhiChu,$Ma_GiaoHat,$status,$MaGiaoXuRieng)
	{
		$objectGX=array(
			"TenGiaoXu"=>$TenGiaoXu,
			"DiaChi"=>$DiaChi,
			"DienThoai"=>$DienThoai,
			"Email"=>$Email,
			"Website"=>$Website,
			"Hinh"=>$Hinh,
			"GhiChu"=>$GhiChu,
			"Ma_GiaoHat"=>$Ma_GiaoHat,
			"status"=>$status,
			"MaGiaoXuRieng"=>$MaGiaoXuRieng);
		$this->db->insert($this->table, $objectGX);
		return $this->db->insert_id();
	}
	public function getGXByIDjsonMD($id)
	{
		$this->db->where('ID', $id);
		$query=$this->db->get($this->table);
		return $query->row();
	}
	public function getGXjsonMDIDGH($idGH)
	{
		$this->db->select('giaoxu.*,giaohat.TenGiaoHat,giaophan.TenGiaoPhan,giaophan.MaGiaoPhan');
		$this->db->from('giaohat,giaophan');
		$this->db->where('giaoxu.Ma_GiaoHat', $idGH);
		$this->db->where('giaoxu.Ma_GiaoHat=giaohat.MaGiaoHat');
		$this->db->where('giaohat.MaGiaoPhan=giaophan.MaGiaoPhan AND giaoxu.status = 1');
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function getGXjsonMD()
	{
		$this->db->select('giaoxu.*,giaophan.TenGiaoPhan,giaohat.TenGiaoHat,giaophan.MaGiaoPhan');
		$this->db->from('giaoxu,giaohat,giaophan');
		$this->db->where('giaoxu.Ma_GiaoHat=giaohat.MaGiaoHat');
		$this->db->where('giaohat.MaGiaoPhan=giaophan.MaGiaoPhan AND giaoxu.status = 1');
		$query=$this->db->get();
		return $query->result();
	}
	public function getGxById($id){
		$this->db->select('giaoxu.*,giaophan.TenGiaoPhan,giaohat.TenGiaoHat,giaophan.MaGiaoPhan');
		$this->db->from('giaoxu,giaohat,giaophan');
		$this->db->where('giaoxu.Ma_GiaoHat=giaohat.MaGiaoHat');
		$this->db->where("giaohat.MaGiaoPhan=giaophan.MaGiaoPhan AND giaoxu.MaGiaoXuRieng='$id'");
		$query=$this->db->get();
		return $query->result();
	}
	public function getGxsRequest(){
		$this->db->select('giaoxu.*,giaophan.TenGiaoPhan,giaohat.TenGiaoHat,giaophan.MaGiaoPhan');
		$this->db->from('giaoxu,giaohat,giaophan');
		$this->db->where('giaoxu.Ma_GiaoHat=giaohat.MaGiaoHat');
		$this->db->where("giaohat.MaGiaoPhan=giaophan.MaGiaoPhan AND `giaoxu.status`=0");
		$query=$this->db->get();
		return $query->result();
	}
	public function getFind($value)
	{
		$this->db->like('TenGiaoXu', $value, 'both');
		$this->db->where('giaoxu.status = 1');
		$this->db->join('giaohat', 'giaohat.MaGiaoHat = giaoxu.Ma_GiaoHat');
		$this->db->join('giaophan', 'giaophan.MaGiaoPhan = giaohat.MaGiaoPhan');
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function getPhanTrang($numPage,$offset)
	{
		$this->db->where('giaoxu.status',1);
		$this->db->join('giaohat', 'giaohat.MaGiaoHat = giaoxu.Ma_GiaoHat');
		$this->db->join('giaophan', 'giaophan.MaGiaoPhan = giaohat.MaGiaoPhan');
		$query=$this->db->get($this->table,$numPage,$offset);
		return $query->result();
	}
	public function countRow()
	{
		$this->db->where('status',1);
		return $this->db->count_all_results($this->table);
	}
	public function getNameSDTAll()
	{
		$this->db->select('giaoxu.TenGiaoXu,giaoxu.DienThoai,backup.Time');

		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function update($id,$name,$add,$sdt,$email,$web,$img='',$note,$maGiaoHat){
		$objectGX=array(
			"TenGiaoXu"=>$name,
			"DiaChi"=>$add,
			"DienThoai"=>$sdt,
			"Email"=>$email,
			"Website"=>$web,
			"Hinh"=>$img,
			"GhiChu"=>$note,
			"Ma_GiaoHat"=>$maGiaoHat,
			"status"=>1
		);
		$this->db->update($this->table,$objectGX,"MaGiaoXuRieng='$id'");
		return $this->db->affected_rows();
	}
	public function insertGiaoXuMD($id,$name,$add,$sdt,$email,$web,$img,$note,$maGiaoHat)
	{
		$objectGX=array(
			"ID"=>$id,
			"Name"=>$name,
			"Address"=>$add,
			"Phone"=>$sdt,
			"Email"=>$email,
			"Web"=>$web,
			"Img"=>$img,
			"Note"=>$note,
			"Ma_GiaoHat"=>$maGiaoHat);
		$this->db->insert($this->table, $objectGX);
		return $this->db->affected_rows();
	}
	public function checkMaGiaoXuRiengMD($id)
	{
		$this->db->where('ID', $id);
		$query=$this->db->get($this->table);
		return $query->result();;
	}
}

/* End of file GiaoXuMD.php */
/* Location: ./application/models/GiaoXuMD.php */