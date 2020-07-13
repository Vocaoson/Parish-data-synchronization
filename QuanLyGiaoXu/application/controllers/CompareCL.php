<?php
defined('BASEPATH') OR exit('No direct script access allowed');

abstract class CompareCL extends CI_Controller {
    public $dirDongBo;
    public $dirData;
    public $file;
    public $data;
    public $dir;
    public $MaGiaoXuRieng;
    public function __construct($file,$dir) {
        parent::__construct();
        $dataImport = array('parse_header'=>true);
        $this->load->library('CsvImport');
        $this->file = $file;
        $this->MaGiaoXuRieng=$this->getMaGiaoXuRieng($dir);
        $this->dir = $dir;
        $this->data = $this->getData();
        $this->dirData = $this->config->item("data_dir");
        $this->dirDongBo=$this->dirData . '/dongboID/dongbo.csv';
        
    }
    abstract public function compare();
    public function getMaGiaoXuRieng($dir){
        $temp=explode("/",$dir);
        return $temp[count($temp)-2];
    }
    public function getData() {
        if($this->csvimport->setFileName($this->dir . '/' . $this->file))
        {
            $data = $this->csvimport->get();
            $data = $this->toBool($data);
            return $data;
        }
    }
    public function toBool($data){
        $datas = $data;
        foreach ($datas as $key => &$value) {
            foreach($value as $subKey => $subValue) {
                if($subValue === "False") {
                    $value[$subKey] = 0;
                } else if($subValue === "True") {
                    $value[$subKey] = -1;
                }
            }
        }
        return $datas;
    }
    public function processDataNull($dataSV,$dataClient)
    {
        foreach ($dataClient as $key=>$value) {
            if ($dataSV->{$key}!=""&&$value=="") {
                $dataClient[$key]=$dataSV->{$key};
            }
        } 
        return $dataClient;
    }
    public function CompareTwoDateTime($datetime1,$datetime2){
		$dt1=new DateTime($datetime1);
        $dt2=new DateTime($datetime2);
        if($dt1>$dt2)
        {
            return 1;
        }
        if($dt1<$dt2)
        {
            return -1;
        }
        if($dt1=$dt2)
        {
            return 0;
        }
    }
    public function updateObject($objectClient,$objectSV,$Model)
    {
        //TH1 Client 0 , update return
        if ($objectClient['DeleteClient']=='0') {
          //  $objectClient=$this->processDataNull($objectSV,$objectClient);
            $Model->update($objectClient);
            return;
        }
        //TH2 Client 1 Server 0 , delete =>return
        if ($objectClient['DeleteClient']=='1'&&$objectSV->DeleteSV=='0') {
            $Model->delete($objectClient);
            return;
        }
        //TH3 Client 1 Server 1 , return
        if ($objectClient['DeleteClient']=='1'&&$objectSV->DeleteSV=='1') {
            return;
        }
    }
    public function changeID($data,$khoaNgoai=false)
	{
        $key="KhoaChinh";
        if($khoaNgoai)
        {
            $key="KhoaNgoai";
        }
		if(strpos($data[$key],"+")!==false)
		{
			$khoa=explode("+",$data[$key]);
			//find Khoa 1
            $temp1=$this->csvimport->getListID($khoa[0],$data[$khoa[0]]);
            if($temp1==null)
            {
                return null;
            }
			$data[$khoa[0]]=$temp1["MaIDMayChu"];
			//find Khoa 2
            $temp2=$this->csvimport->getListID($khoa[1],$data[$khoa[1]]);
            if($temp2==null)
            {
                return null;
            }
			$data[$khoa[1]]=$temp2["MaIDMayChu"];
            $data[$key]="";
			return $data;
		}
		else
		{
			//find Khoa 
            $temp=$this->csvimport->getListID($data[$key],$data[$data[$key]]);
            if($temp==null)
            {
                return null;
            }
			$data[$data[$key]]=$temp["MaIDMayChu"];
			$data[$key]="";
			return $data;
		}
		return null;
	}


    // delete temp

    /*
    public function deleteObjecChild($data,$fieldID1,$fieldID2,&$rs,$Model)
    {
        if (!isset($data["DeleteClient"])) {
        return false;
    }
    //TH1 Client 1 Server 0 , Client>Server=>1=>true
     if (isset($rs)&&$data['DeleteClient']=='1'&&$rs->DeleteSV=='0') {
        $Model->deleteTwoKey($rs->{$fieldID1},$rs->{$fieldID2},$rs->MaGiaoXuRieng);
        return true;
    }
        //TH2 Client 1 Server 0 , Client<Server=>true
    if (isset($rs)&&$data['DeleteClient']=='1'&&$rs->DeleteSV=='0') {
        return true;
    }
        //Th3 Client 0 Server 1 , Client>Server=>Xóa luôn Server=>false
    if (isset($rs)&&$data['DeleteClient']=='0'&&$rs->DeleteSV=='1') {
        $Model->deleteReal($rs);
        $rs=null;
        return false;
    }
        //TH4 Client 0 Server 1 , Client<Server=>true
    if (isset($rs)&&$data['DeleteClient']=='0'&&$rs->DeleteSV=='1'  ) {
        return true;
    }
    if ($data['DeleteClient']=='1'){
        return true;
    }
    return false;
}
public function findObjectChild($data,$listChange,$fieldID1,$fieldID2)
{
    if (isset($listChange)&&count($listChange)>0) {
        foreach ($listChange as $value) {
            if ($value->{$fieldID1}=$data->{$fieldID1}&&$value->{$fieldID2}==$data->{$fieldID2}) {
                return 1;
            }
        }
    }
    return 0;
}

    public function importObjectChild($objectTrack,$listObjectDetailCSV,$fieldID1,$listObjectChange,$fieldID2,$Model)
    {
        if (!$objectTrack->oldIdIsCsv) {   
            $objectCSV=$this->getListByID($listObjectDetailCSV,$fieldID1,$objectTrack->newId);
        }
        else {
            $objectCSV=$this->getListByID($listObjectDetailCSV,$fieldID1,$objectTrack->oldId);
        }
        if (isset($objectCSV)&&count($objectCSV)>0) {
            foreach ($objectCSV as $data) {
                $IDobject2=$this->findIdObjectSV($listObjectChange,$data[$fieldID2]);
                if ($IDobject2==0) {
                    continue;
                }
                $rs=$Model->findwithID($objectTrack->nowId,$IDobject2,$data['MaGiaoXuRieng']);
                
                $objectTrackNew=new stdClass();
                $objectTrackNew->{$fieldID2}=$IDobject2;
                $objectTrackNew->{$fieldID1}=$objectTrack->nowId;
                $this->tracks[]=$objectTrackNew;
                
                if ($this->deleteObjecChild($data,$fieldID1,$fieldID2,$rs,$Model))
                    continue;


                if ($rs) {
                    if ($this->compareDate($data['UpdateDate'],$rs->UpdateDate)) {
                        $Model->update($data,$IDobject2,$objectTrack->nowId);
                    }
                    continue;
                }
                $Model->insert($data,$IDobject2,$objectTrack->nowId);
                
            }
        }

    }
   
   
    public function containerDic($dic,$id1,$fieldID1,$id2,$fieldID2)
    {
        foreach ($dic as $data) {
            if ($data->{$fieldID1}==$id1&&$data->{$fieldID2}==$id2) {
                return true;
            }
        }
        return false;
    }
    public function getListByID($list,$field,$id)
    {
        $listTemp=array();
        foreach ($list as $rowCSV) {
            if ($rowCSV[$field]==$id) {
                $listTemp[]=$rowCSV;
            }               
        }
        return $listTemp;
    }

    public function findIdObjectCSV($listTrack,$idSV)
    {
        if ($listTrack!=null && count($listTrack)>0) {
            foreach ($listTrack as $data) {
                if ($data->oldIdIsCsv) {
                    if ($data->newId==$idSV) {
                        return $data->oldId;
                    }
                }
                else {
                    if ($data->oldId==$idSV) {
                        return $data->newId;
                    }
                }
            }
        }
        return 0;
    }

    public function findIdObjectSV($listTrack,$idCSV)
    {
        if ($listTrack!=null&& count($listTrack)>0) {
            foreach ($listTrack as $data) {
                if ($data->oldIdIsCsv) {
                    if ($data->oldId==$idCSV) {
                        return $data->newId;
                    }
                }
                else {
                    if ($data->newId==$idCSV) {
                        return $data->oldId;
                    }
                }
            }
        }
        return 0;
    }

    
    
    
   */
    
}