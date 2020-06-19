<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class CsvImport
{ 
    private $enclosure;
    private $fp; 
    private $parse_header; 
    private $header; 
    private $delimiter; 
    private $length; 
    public $ListTable=[
        "MaCauHinh"    =>"CauHinh",
        "MaChuyenXu"    =>"ChuyenXu",
        "MaDotBiTich"   =>"DotBiTich",
        "MaGiaDinh"     =>"GiaDinh",
        "MaGiaoDan"     =>"GiaoDan",
        "MaGiaoHo"      =>"GiaoHo",
        "MaHoiDoan"     =>"HoiDoan",
        "MaHonPhoi"     =>"HonPhoi",
        "MaKhoi"        =>"KhoiGiaoLy",
        "MaLinhMuc"     =>"LinhMuc",
        "MaLop"         =>"LopGiaoLy",
        "MaRaoHonPhoi"  =>"RaoHonPhoi",
        "MaTanHien"     =>"TanHien",
        "ID"            =>"ChiTietHoiDoan"
    ];
    private $ListID=[];
    //-------------------------------------------------------------------- 
    function __construct($parse_header=true, $delimiter=";", $length=8000,$enclosure="`") 
    {
        $this->parse_header = $parse_header; 
        $this->delimiter = $delimiter; 
        $this->length = $length; 
        $this->enclosure=$enclosure;
    } 
    public function setFileName($file_name){
        if(file_exists($file_name))
        {
            $this->fp = fopen($file_name, "r"); 
            if ($this->parse_header) 
            { 
                $this->header = fgetcsv($this->fp, $this->length, $this->delimiter,$this->enclosure); 
            }
            return true;
        }
        return false;
    }
    //-------------------------------------------------------------------- 
    function __destruct() 
    { 
        if ($this->fp) 
        { 
            fclose($this->fp); 
        } 
    } 
    //-------------------------------------------------------------------- 
    function get($max_lines=0) 
    { 
        //if $max_lines is set to 0, then get all the data 

        $data = array(); 

        if ($max_lines > 0){
            $line_count = 0; 
        }
        else {
            $line_count = -1; // so loop limit is ignored 
        }
        while ($line_count < $max_lines && ($row = fgetcsv($this->fp, $this->length, $this->delimiter,$this->enclosure)) !== FALSE) 
        { 
            if ($this->parse_header) 
            { 
                foreach ($this->header as $i => $heading_i) 
                { 
                    $row_new[$heading_i] = $row[$i]; 
                } 
                $data[] = $row_new; 
            }
            else 
            { 
                $data[] = $row; 
            } 
            if ($max_lines > 0) 
                $line_count++; 
        } 
        return $data; 
    }


    public function CreateFileAndFolder($dataDir,$maGiaoXuRieng)
    {
        $check=false;
        if(!is_dir($dataDir . '/dongboID/'.$maGiaoXuRieng)) {
            $check= mkdir($dataDir . '/dongboID/'.$maGiaoXuRieng,0777,TRUE);
            if(!$check)
            return false;
        }
        try {
            $fopen=fopen($dataDir . '/dongboID/'.$maGiaoXuRieng.'/dongbo.csv','w');
            fwrite($fopen,"`TenBang`;`MaIDMayKhach`;`MaIDMayChu`;`UpdateDate`");
            fclose($fopen);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    public function getTableNameByID($id)
    {
        return $this->ListTable[$id];
    }
    public function WriteData($id,$IDClient,$IDServer,$dataDir,$maGiaoXuRieng)
    {
        try {
            $timestamp = time()+date("Z");
            $nowUTC= gmdate("Y-m-d H:i:s",$timestamp);
            $fopen=fopen($dataDir . '/dongboID/'.$maGiaoXuRieng.'/dongbo.csv','a');
            fwrite($fopen,"\n");
            $tableName=$this->getTableNameByID($id);
            fwrite($fopen,'`'.$tableName.'`;`'.$IDClient.'`;`'.$IDServer.'`;`'.$nowUTC.'`');
            fclose($fopen);
            $values["TenBang"]=$tableName;
            $values["MaIDMayKhach"]=$IDClient;
            $values["MaIDMayChu"]=$IDServer;
            $values["UpdateDate"]=$nowUTC;
            if($tableName=="GiaoDan" || $tableName=="DotBiTich"|| $tableName=="HonPhoi")
            {
                $keyServer=$tableName."+server+".$IDServer;
                $this->setListID($keyServer,$values);
            }
            $key=$tableName."+".$IDClient;
            $this->setListID($key,$values);
        } catch (Exception $e) {
            return false;
        }
    }
    private function setListID($key,$values)
    {
        $this->ListIDClient[$key]=$values;
        return;
    }
    public function getListID($id,$IDClient)
    {
        $tableName="GiaoDan";
        if($id != "MaGiaoDan" && $id != "MaGiaoDan1" && $id != "MaGiaoDan2")
        {
            $tableName=$this->getTableNameByID($id);
        }
        $keyIDClient=$tableName."+".$IDClient;
        if(array_key_exists($keyIDClient, $this->ListIDClient))
        return $this->ListIDClient[$keyIDClient];
        return null;
    }
    
    //-------------------------------------------------------------------- 
} 
?>