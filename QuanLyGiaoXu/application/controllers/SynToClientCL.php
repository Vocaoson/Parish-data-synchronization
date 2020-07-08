<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SynToClientCL extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('MayNhapMD');
        $this->load->model('GiaoXuMD');
		$this->load->model('BiTichChiTietMD');
		$this->load->model('ChiTietLopGiaoLyMD');
		$this->load->model('ChiTietHoiDoanMD');
		$this->load->model('ChuyenXuMD');
		$this->load->model('DotBiTichMD');
		$this->load->model('GiaoDanHonPhoiMD');
		$this->load->model('GiaoLyVienMD');
		$this->load->model('LinhMucMD');
		$this->load->model('HoiDoanMD');
		$this->load->model('HonPhoiMD');
		$this->load->model('KhoiGiaoLyMD');
		$this->load->model('LopGiaoLyMD');
		$this->load->model('RaoHonPhoiMD');
		$this->load->model('TanHienMD');
		$this->load->model('GiaDinhMD');
		$this->load->model('GiaoDanMD');
		$this->load->model('GiaoHoMD');
		$this->load->model('ThanhVienGiaDinhMD');
		$this->load->model('CauHinhMD');
	}
	private function parseDateTime($timeClient)
	{
		$temp=explode('-');
		$date=$temp[0]."-".$temp[1]."-".$temp[2]." ".$temp[3].":".$temp[4].":".$temp[5];
		return $date;
	}
	public function downloadMayNhap($maGiaoXuRieng,$maDinhDanh,$timestampPull)
	{
		$listMayNhap=$this->MayNhapMD->getDieuKienPull($maGiaoXuRieng,$maDinhDanh,$timestampPull);
		$result="";
		foreach ($listMayNhap as $key => $value) {
			$result.=$value->TenMay.'/'.$value->MaDinhDanh.'/'.$value->PushDate;
			if($key!=count($listMayNhap)-1)
			{
				$result.="&";
			}
		}
		// if($result=="")
		// {
		// 	$timestamp = time()+date("Z");
		// 	$nowUTC= gmdate("Y-m-d H:i:s",$timestamp);
		// 	//set lock's parish =0;
		// 	$this->GiaoXuMD->setLockSync($maGiaoXuRieng);
		// 	// update push date for maynhap
		// 	$this->MayNhapMD->setPushDate($maGiaoXuRieng,$maDinhDanh,$nowUTC);
		// 	echo $nowUTC;
		// 	return;
		// }
		echo $result;
		return;
	}
	public function createrFileSyn($maGiaoXuRieng,$maDinhDanh)
	{
		$dieukien="1=1";
		if(isset($_FILES) && count($_FILES) > 0){
			$name=$_FILES['file']['tmp_name'];
			$dieukien=file_get_contents($name);
		}
		//get file dieu kien
		$dirtemp=$this->config->item("data_dir").'/CsvToClient/'.$maGiaoXuRieng;
		if(!is_dir($dirtemp)) {
			$check= mkdir($dirtemp,0777,TRUE);
		}
		$files = glob($dirtemp.'/*'); // get all file names
		foreach($files as $file){ // iterate files
			if(is_file($file))
    			unlink($file); // delete file
			}
		
		$data=null;
		if(trim($dieukien)!="")
		{
			$data['BiTichChiTiet']=$this->BiTichChiTietMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh,$dieukien);
			$data['CauHinh']=$this->CauHinhMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh,$dieukien);
			$data['ChiTietHoiDoan']=$this->ChiTietHoiDoanMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh,$dieukien);
			$data['ChiTietLopGiaoLy']=$this->ChiTietLopGiaoLyMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh,$dieukien);
			$data['ChuyenXu']=$this->ChuyenXuMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh,$dieukien);
			$data['DotBiTich']=$this->DotBiTichMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh,$dieukien);
			$data['GiaDinh']=$this->GiaDinhMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh,$dieukien);
			$data['GiaoDanHonPhoi']=$this->GiaoDanHonPhoiMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh,$dieukien);
			$data['GiaoHo']=$this->GiaoHoMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh,$dieukien);
			$data['GiaoDan']=$this->GiaoDanMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh,$dieukien);
			$data['GiaoLyVien']=$this->GiaoLyVienMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh,$dieukien);
			$data['HoiDoan']=$this->HoiDoanMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh,$dieukien);
			$data['HonPhoi']=$this->HonPhoiMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh,$dieukien);
			$data['KhoiGiaoLy']=$this->KhoiGiaoLyMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh,$dieukien);
			$data['LinhMuc']=$this->LinhMucMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh,$dieukien);
			$data['LopGiaoLy']=$this->LopGiaoLyMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh,$dieukien);
			$data['RaoHonPhoi']=$this->RaoHonPhoiMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh,$dieukien);
			$data['TanHien']=$this->TanHienMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh,$dieukien);
			$data['ThanhVienGiaDinh']=$this->ThanhVienGiaDinhMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh,$dieukien);
			
		}
		//move file DongBoID to csv to client
		if(!copy($this->config->item("data_dir").'/dongboID/'.$maGiaoXuRieng.'/dongbo.csv',$dirtemp.'/dongbo.csv')){
			echo -1;
			return;
		}
		if($data!=null)
		foreach ($data as $key=>$table) {
		if(count($table['data'])==0)
			continue;
			$fp = fopen($dirtemp.'//'.$key.'.csv', 'w');
			$header="";
			foreach ($table['field'] as $field) {
				if($field=="DeleteSV")
				{
					$header.="`".$field."`\n";
					break;
				}
				$header.="`".$field."`;";
			}
			fwrite($fp,$header);
			foreach ($table['data'] as $object) {
				$value="";
				unset($object->MaDinhDanh);
				foreach ($object as $dataTable) {
					$dataTable=str_replace("`"," ",$dataTable);
					$value.="`".$dataTable."`;";
				}
				$value=rtrim($value,";");
				$value.="\n";
				fwrite($fp,$value);
			}
		}
		//
		
		$tempGloble=glob($dirtemp."/*.csv");
		if (count($tempGloble)>0) {
			$zip = new ZipArchive(); 
			$check=$zip->open($dirtemp."/".$maGiaoXuRieng.".zip", ZipArchive::CREATE); 
			$files = array();
			foreach ($tempGloble as  $file) {
				$split=explode ("/" , $file);
				$zip->addFile($file,$split[count($split)-1]);
			}
			$zip->close();
			$timestamp = time()+date("Z");
			$nowUTC= gmdate("Y-m-d H:i:s",$timestamp);
			//set lock's parish =0;
			$this->GiaoXuMD->setLockSync($maGiaoXuRieng);
			// update push date for maynhap
			$this->MayNhapMD->setPushDate($maGiaoXuRieng,$maDinhDanh,$nowUTC);
			echo $nowUTC;
			return;
		}
		echo -1;
	}
}

/* End of file SynToClientCL.php */
/* Location: ./application/controllers/SynToClientCL.php */