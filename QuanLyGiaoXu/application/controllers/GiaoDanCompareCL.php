<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class GiaoDanCompareCL extends CompareCL {
    public function __construct($file,$syn) {
        parent::__construct($file,$syn);
        require_once(APPPATH.'models/GiaoDanMD.php');
        $this->GiaoDanMD=new GiaoDanMD();
    }
    //2018/09/22 Gia add start
    private $GiaoDanMD;
    private $listGHThayDoi;
    public function getListGiaoHoTracks($tracks)
    {
        $this->listGHThayDoi=$tracks;
    }
    //2018/09/22 Gia add end
    public function compare(){
        foreach($this->data as $data){
            $track = new stdClass();
            $track->updated = false;
            if(!empty($data['MaNhanDang']) || (!empty($data['HoTen']) && !empty($data['NgaySinh']) && !empty($data['TenThanh']))) {
                $giaoDans = !empty($data['MaNhanDang']) ? $this->GiaoDanMD->getByMaNhanDang($data['MaNhanDang']):$this->GiaoDanMD->getByInfo($data['HoTen'],$data['TenThanh'],$data['NgaySinh']);

                //2018/09/22 Gia add start
                $data['MaGiaoHo']=$this->findIdObjectSV($this->listGHThayDoi,$data['MaGiaoHo']);
                //2018/09/22 Gia add end
                if(isset($giaoDans) && count($giaoDans) > 0) { //=> trùng
                    //2018/09/20 Gia add start
                    $data=$this->processDataNull($giaoDans[0],$data);
                    //2118/09/20 Gia add end
                    $track->updated = true;
                    if($data['UpdateDate'] > $giaoDans[0]->UpdateDate) {
                        $track->oldIdIsCsv = false;
                        $track->oldId = $giaoDans[0]->MaGiaoDan;
                        $track->newId = $data['MaGiaoDan'];
                        $track->nowId = $giaoDans[0]->MaGiaoDan;
                        //2018/09/24 Gia delete start
                        //$this->GiaoDanMD->update($data);
                        //2018/09/24 Gia delete end

                        //2018/09/24 Gia add start
                        $this->GiaoDanMD->update($data,$giaoDans[0]->MaGiaoDan);
                         //2018/09/24 Gia add end
                    } else {
                        $track->oldIdIsCsv = true;
                        $track->oldId = $data['MaGiaoDan'];
                        $track->newId = $giaoDans[0]->MaGiaoDan;
                        $track->nowId = $giaoDans[0]->MaGiaoDan;

                    }
                } else {
                    //create
                    $idNew = $this->GiaoDanMD->insert($data);
                    $track->oldIdIsCsv = true;
                    $track->oldId = $data['MaGiaoDan'];
                    $track->newId = $idNew;
                    $track->nowId = $idNew;
                }
                $this->tracks[] = $track;
            }
            // by name +  tenthanh + ngaysinh
            // if(!empty($data['HoTen']) && !empty($data['NgaySinh']) && !empty($data['TenThanh'])) {
            //     $giaoDans = $this->GiaoDanMD->getByInfo($data['HoTen'],$data['TenThanh'],$data['NgaySinh']);
            //     if(isset($giaoDans) && count($giaoDans) > 1){ //=> trùng
            //         $this->GiaoDanMD->deleteByInfo($data['HoTen'],$data['TenThanh'],$data['NgaySinh']);
            //     }
            // }
        }
    }
}