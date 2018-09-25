<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GiaoDanMD extends CI_Model {
    public $giaoDanModel;
    private $table; 
	public function __construct()
	{
		parent::__construct();
		$this->table="giaodan";
    }
  
    public function insert($giaoDanArray){
        //2018-09-17 Gia add start
        unset($giaoDanArray['MaGiaoDan']);
        //2018-09-17 Gia add end
		$this->db->insert($this->table, $giaoDanArray);
		return $this->db->insert_id();	
    }
    public function update($giaoDanArray,$id){
        unset($giaoDanArray['MaGiaoDan']);
        return $this->db->update($this->table, $giaoDanArray,"MaGiaoDan='$id'");
    }
    //2018/09/23 Gia add start
    public function delete($maGiaoDan,$maGiaoXu){
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
    //2018/09/23 Gia add end
    public function getById($id) {
        $this->db->select('*');
		$this->db->where('MaGiaoDan', $id);
		$query=$this->db->get($this->table);
		return $query->result();
    }
    public function getByInfo($name,$tenThanh,$birthdate){
        $this->db->select('*');
        $this->db->where('HoTen', $name);
        $this->db->where('TenThanh', $tenThanh);
        $this->db->where('NgaySinh', $birthdate);
		$query=$this->db->get($this->table);
		return $query->result();
    }    
    public function getByMaNhanDang($maNhanDang){
        $this->db->select('*');
        $this->db->where('MaNhanDang', $maNhanDang);
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

}