<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GiaoDanMD extends CI_Model {
  private $table; 
  public function __construct()
  {
    parent::__construct();
    $this->table="giaodan";
  }
  public function insert($data)
	{
		unset($data['MaGiaoDan']);
    unset($data['KhoaChinh']);
    unset($data['KhoaNgoai']);
    unset($data['DeleteClient']);
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
  public function update($data){
    $maGiaoDan=$data['MaGiaoDan'];
		unset($data['MaGiaoDan']);
    unset($data['KhoaChinh']);
    unset($data['KhoaNgoai']);
    unset($data['DeleteClient']);
		$data['DeleteSV']=0;
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$this->db->update($this->table, $data);
  }
  public function delete($data)
  {
    $this->db->set('DeleteSV',1);
		$this->db->set('MaDinhDanh',$data['MaDinhDanh']);
		$this->db->set('UpdateDate',$data['UpdateDate']);
		$this->db->where('MaGiaoDan', $data['MaGiaoDan']);
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		$this->db->update($this->table);
  }
  public function getByInfo($dieuKien,$MaGiaoXuRieng){
    $this->db->select('*');
    $this->db->where($dieuKien);
    $this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
    $query=$this->db->get($this->table);
    return $query->row();
  }    
  public function getByMaNhanDang($maNhanDang,$MaGiaoXuRieng){
    $this->db->select('*');
    $this->db->where('MaNhanDang', $maNhanDang);
    $this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
    $query=$this->db->get($this->table);
    return $query->row();
  }
  public function getByMaGiaoDan($maGiaoDan) {
    $this->db->select('*');
    $this->db->where('MaGiaoDan', $maGiaoDan);
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
  
  //
  /*
  public function getAll($MaGiaoXuRieng)
  {
    $this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
    $query=$this->db->get($this->table);
    return $query->result();
  }
  public function  getbyMaGX($maGiaoXu)
  {
    $this->db->where('MaGiaoXuRieng', $maGiaoXu);
    $query=$this->db->get($this->table);
    return $query->result();
  }
  
  public function deleteMaGiaoDan($maGiaoDan,$maGiaoXu){
    $this->db->set('DeleteSV',1);
    $this->db->where('MaGiaoDan', $maGiaoDan);
    $this->db->where('MaGiaoXuRieng', $maGiaoXu);
    $this->db->update($this->table);
  }
  public function getByMaGiaoHo($maGiaoHo,$maGiaoXu)
  {
    $this->db->select('MaGiaoDan');
    $this->db->where('MaGiaoHo', $maGiaoHo);
    $this->db->where('MaGiaoXuRieng', $maGiaoXu);
    $query=$this->db->get($this->table);
    return $query->result();
  }
  
  
  public function deleteByMaNhanDang($maNhanDang){
    $sql = "DELETE FROM giaodan
    WHERE UpdateDate NOT IN (
    SELECT `last_time` FROM (
    SELECT MAX(UpdateDate) AS last_time FROM giaodan WHERE MaNhanDang='$maNhanDang'
    )  AS temp
  ) AND ManhanDang = '$maNhanDang' AND MaNhanDang != ''";
  $rs = $this->db->query($sql);
  }
  public function deleteByInfo($name,$tenThanh,$birthdate) {
    $sql = "DELETE FROM giaodan
    WHERE UpdateDate NOT IN (
    SELECT `last_time` FROM (
    SELECT MAX(UpdateDate) AS last_time FROM giaodan WHERE `HoTen` = '$name' AND `TenThanh`='$tenThanh' AND `NgaySinh` = '$birthdate'
    )  AS temp
  ) AND `HoTen` = '$name' AND `TenThanh`='$tenThanh' AND `NgaySinh` = '$birthdate'";
  $rs = $this->db->query($sql);
  }
 */

}