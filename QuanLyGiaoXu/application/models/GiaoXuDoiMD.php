<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GiaoXuDoiMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
	//Do your magic here
		$this->table="giaoxudoi";
	}
	public function getGiaoXuDaPheDuyet($maGiaoXuDoi){
		$this->db->where("`giaoxudoi.MaGiaoXuDoi`",$maGiaoXuDoi);
		$this->db->where("`giaoxudoi.Status`",1);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function getGiaoXuDangDoi(){
		$this->db->where("`giaoxudoi.status`",0);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function insertGiaoXuDoiMD($tenGiaoPhan,$maGiaoPhanRieng,$tenGiaoHat,$maGiaoHatRieng,$tenGiaoXu,$diaChi,$dienThoai,$email,$website,$hinh,$ghiChu)
	{
		$objectGiaoXuDoi=array(
			"TenGiaoPhan"=>$tenGiaoPhan,
			"MaGiaoPhanRieng"=>$maGiaoPhanRieng,
			"TenGiaoHat"=>$tenGiaoHat,
			"MaGiaoHatRieng"=>$maGiaoHatRieng,
			"TenGiaoXu"=>$tenGiaoXu,
			"DiaChi"=>$diaChi,
			"DienThoai"=>$dienThoai,
			"Email"=>$email,
			"Website"=>$website,
			"Hinh"=>$hinh,
			"GhiChu"=>$ghiChu,
			);
		$this->db->insert($this->table, $objectGiaoXuDoi);
		return $this->db->insert_id();
	}
	public function updateGiaoXuDoiMD($maGiaoXuDoi,$tenGiaoPhan,$maGiaoPhan,$tenGiaoHat,$maGiaoHat,$tenGiaoXu,$maGiaoXuRieng,$diaChi,$dienThoai,$email,$website,$hinh,$ghiChu){
		$objectGiaoXuDoi=array(
			"TenGiaoPhan"=>$tenGiaoPhan,
			"MaGiaoPhanRieng"=>$maGiaoPhan,
			"TenGiaoHat"=>$tenGiaoHat,
			"MaGiaoHatRieng"=>$maGiaoHat,
			"TenGiaoXu"=>$tenGiaoXu,
			"MaGiaoXuRieng"=>$maGiaoXuRieng,
			"DiaChi"=>$diaChi,
			"DienThoai"=>$dienThoai,
			"Email"=>$email,
			"Website"=>$website,
			"Hinh"=>$hinh,
			"GhiChu"=>$ghiChu,
			"status"=>1
			);
		$this->db->where("`giaoxudoi.MaGiaoXuDoi`",$maGiaoXuDoi);
		$this->db->update($this->table,$objectGiaoXuDoi);
		return $this->db->affected_rows();
	}
	public function getGiaoXuDoiByMaGiaoXuDoi($maGiaoXuDoi)
	{
		$this->db->where("`giaoxudoi.MaGiaoXuDoi`",$maGiaoXuDoi);
		$query=$this->db->get($this->table);
		return $query->result();
	}

}

/* End of file GiaoXuDoiMD.php */
/* Location: ./application/models/GiaoXuDoiMD.php */