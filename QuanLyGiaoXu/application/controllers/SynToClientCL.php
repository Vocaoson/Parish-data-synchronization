<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SynToClientCL extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
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
	public function createrFileSyn($maGiaoXuRieng,$maDinhDanh)
	{
		$data['BiTichChiTiet']=$this->BiTichChiTietMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh);
		$data['CauHinh']=$this->CauHinhMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh);
		$data['ChiTietHoiDoan']=$this->ChiTietHoiDoanMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh);
		$data['ChiTietLopGiaoLy']=$this->ChiTietLopGiaoLyMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh);
		$data['ChuyenXu']=$this->ChuyenXuMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh);
		$data['DotBiTich']=$this->DotBiTichMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh);
		$data['GiaDinh']=$this->GiaDinhMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh);
		$data['GiaoDanHonPhoi']=$this->GiaoDanHonPhoiMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh);
		$data['GiaoHo']=$this->GiaoHoMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh);
		$data['GiaoDan']=$this->GiaoDanMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh);
		$data['GiaoLyVien']=$this->GiaoLyVienMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh);
		$data['HoiDoan']=$this->HoiDoanMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh);
		$data['HonPhoi']=$this->HonPhoiMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh);
		$data['KhoiGiaoLy']=$this->KhoiGiaoLyMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh);
		$data['LinhMuc']=$this->LinhMucMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh);
		$data['LopGiaoLy']=$this->LopGiaoLyMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh);
		$data['RaoHonPhoi']=$this->RaoHonPhoiMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh);
		$data['TanHien']=$this->TanHienMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh);
		$data['ThanhVienGiaDinh']=$this->ThanhVienGiaDinhMD->getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh);
	
		$dirtemp=$this->config->item("data_dir").'/CsvToClient/'.$maGiaoXuRieng;
		if(!is_dir($dirtemp)) {
			$check= mkdir($dirtemp,0777,TRUE);
		}
		foreach ($data as $key=>$table) {
		if(count($table['data'])==0)
			continue;
			$fp = fopen($dirtemp.'\\'.$key.'.csv', 'w');
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
		//move file DongBoID to csv to client
		copy($this->config->item("data_dir").'/dongBoID/'.$maGiaoXuRieng.'/dongbo.csv',$dirtemp.'/dongbo.csv');
		$tempGloble=glob($dirtemp."\*.csv");
		if (count($tempGloble)>0) {
			$zip = new ZipArchive(); 
			$check=$zip->open($dirtemp."/".$maGiaoXuRieng.".zip", ZipArchive::CREATE); 
			$files = array();
			foreach ($tempGloble as  $file) {
				$split=explode ("\\" , $file);
				$zip->addFile($file,$split[count($split)-1]);
			}
			$zip->close();
			echo 1;
			return;
		}
		echo -1;
	}
}

/* End of file SynToClientCL.php */
/* Location: ./application/controllers/SynToClientCL.php */