<?php
defined('BASEPATH') OR exit('No direct script access allowed');

abstract class CompareCL extends CI_Controller {

    public $file;
    public $data;
    public $dir;
    public $tracks;//array(stdClass)
    //strclass->oldId;stdclass->newId;stdclass->oldIdIsCsv
    public function __construct($file,$dir) {
        parent::__construct();
        $dataImport = array('parse_header'=>true);
        $this->load->library('CsvImport');
        $this->file = $file;
        $this->dir = $dir;
        $this->data = $this->getData();
    }
    abstract public function compare();
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
}