<?php
defined('BASEPATH') OR exit('No direct script access allowed');

abstract class CompareCL extends CI_Controller {

    public $file;
    public $data;
    public $dir;
    public $tracks = array();//array(stdClass)
    //strclass->oldId;stdclass->newId;stdclass->oldIdIsCsv
    public $MaGiaoXuRieng;
    public function __construct($file,$dir) {
        parent::__construct();
        $dataImport = array('parse_header'=>true);
        $this->load->library('CsvImport');
        $this->file = $file;
        $this->MaGiaoXuRieng=$this->getMaGiaoXuRieng($dir);
        $this->dir = $dir;
        $this->data = $this->getData();
    }
    abstract public function compare();
    public function getMaGiaoXuRieng($dir){
     $temp=explode("/",$dir);
     return $temp[count($temp)-2];
 }


 public function deleteObjecChild($listChange,$fieldID1,$fieldID2,$Model,$maGiaoXuRieng)
 {
    if (isset($this->tracks)&&count($this->tracks)>0) {
        $listObjectChild=$Model->getAll($maGiaoXuRieng);
        if (isset($listObjectChild)&&count($listObjectChild)>0) {
            foreach ($listObjectChild as $data) {
                $rs=$this->findObjectChild($data,$listChange,$fieldID1,$fieldID2);
                if ($rs==0) {
                        //delete
                    $Model->deleteTwoKey($data->{$fieldID1},$data->{$fieldID2},$data->MaGiaoXuRieng);
                }
            }
        }
    }
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
public function compareDate($dateCSV,$dateSV){
    $time1 = strtotime($dateCSV);
    $time2 = strtotime($dateSV);
    if($time1>$time2){
      return true;
  }
  return false;
}
    /**
     * [importObjectChild Merger cac ban co quan he nhieu nhieu]
     * @param  [type] $objectTrack         [description]
     * @param  [type] $listObjectDetailCSV [description]
     * @param  [type] $fieldID1            [description]
     * @param  [type] $listObjectChange    [description]
     * @param  [type] $fieldID2            [description]
     * @param  [type] $Model               [description]
     * @return [type]                      [description]
     */
    public function importObjectChild($objectTrack,$listObjectDetailCSV,$fieldID1,$listObjectChange,$fieldID2,$Model)
    {
        if ($objectTrack->updated) {
            //update
            if (!$objectTrack->oldIdIsCsv) {
                $objectCSV=$this->getListByID($listObjectDetailCSV,$fieldID1,$objectTrack->newId);
            }
        }
        else {
            //insert
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
    /**
     * [importObjectMaster Merge cac ban chinh ]
     * @param  [type] $objectCSV [description]
     * @param  [type] $fieldID   [Name ID]
     * @param  [type] $objectSV  [object find in server]
     * @param  [type] $Model     [model of main]
     * @return [type]            []
     */
    public function importObjectMaster($objectCSV,$fieldID,$objectSV,$Model)
    {
        $objectTrack=new stdClass();
        $objectTrack->updated=false;
        $objectTrack->oldIdIsCsv=true;
        
        if ($objectSV==null) {
                //Insert
            $objectTrack->oldId=$objectCSV[$fieldID];
            $objectTrack->newId=$Model->insert($objectCSV);
            $objectTrack->nowId=$objectTrack->newId;
        }
        else {
                //Update
                //Xu ly du lieu Null
            $objectCSV=$this->processDataNull($objectSV,$objectCSV);
                //check time UpdateDate
            $objectTrack->updated=true;
            if ($this->compareDate($objectCSV['UpdateDate'],$objectSV->UpdateDate)) {
                $objectTrack->newId=$objectCSV[$fieldID];
                $objectTrack->oldId=$objectSV->{$fieldID};
                $objectTrack->nowId=$objectSV->{$fieldID};
                $objectTrack->oldIdIsCsv=false;
                $Model->update($objectCSV,$objectSV->{$fieldID});
            }
            else {
                $objectTrack->oldIdIsCsv=true;
                $objectTrack->newId=$objectSV->{$fieldID};
                $objectTrack->oldId=$objectCSV[$fieldID];
                $objectTrack->nowId=$objectSV->{$fieldID};
            }
        }
        return $objectTrack;
    }
    /*
    Container in Dic
     */
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
    /**
     * [findIdObjectCSV Tìm mã CSV của object]
     * @param  [type] $listTrack [description]
     * @param  [type] $idSV      [description]
     * @return [type]      0      [Nếu không tìm thấy]
     */
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
    /**
     * [findIdObjectSV Tìm mã server của object trong list track]
     * @param  [type] $listTrack [description]
     * @param  [type] $idCSV     [id trong file csv]
     * @return [type]     0       [Nếu không tìm thấy]
     */
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
    /**
     * [processDataNull thuoc tinh ben SV co du lieu ma CSV 0 thi gan du lieu qua CSV]
     * @param  [type] $dataSV  [description]
     * @param  [type] $dataCSV [description]
     */
    public function processDataNull($dataSV,$dataCSV)
    {
        foreach ($dataCSV as $key=>$value) {
            if ($dataSV->{$key}!=""&&$value=="") {
                $dataCSV[$key]=$dataSV->{$key};
                
            }
        }
        return $dataCSV;
    }
    public function getData() {
        $this->csvimport->setFileName($this->dir . '/' . $this->file);
        $data = $this->csvimport->get();
        $data = $this->toBool($data);
        return $data;
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
}