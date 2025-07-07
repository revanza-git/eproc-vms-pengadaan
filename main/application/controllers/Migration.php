<?php
/**
 * 
 */
class Migration extends CI_Controller
{
	public $eproc_db;
	public $eproc_dev_db;

	function __construct()
	{
		parent::__construct();
		$this->eproc_db = $this->load->database('eproc',true);
		$this->eproc_dev_db = $this->load->database('eproc_dev',true);
	}

	public function index()
	{
		$table = array(
			'ms_agen',
			'ms_agen_bsb',
			'ms_agen_produk',
			'ms_akta',
			'ms_ijin_usaha',
			'ms_iu_bsb',
			'ms_pemilik',
			'ms_pengalaman',
			'ms_pengurus',
			'ms_situ',
			'ms_tdp',
			'ms_vendor_admistrasi',
			'ms_vendor_pic'
		);
		$status = '';

		foreach ($table as $k => $v) {
			$from_dev = $this->eproc_dev_db->get($v)->result_array();

			foreach ($from_dev as $kd => $vd) {
				$to_prod = $this->eproc_db->where('id',$vd['id'])->get($v)->row_array();
				if (empty($to_prod)) {
					$this->eproc_db->insert($v,$vd);
					$status .= 'Insert';
				} else {
					$this->eproc_db->where('id',$vd['id'])->update($v,$vd);
					$status .= 'Update';
				}
			}
		}

		echo $status." <br>";
	}

	public function deleteDouble()
	{
		$table = array(
			'ms_agen',
			'ms_agen_bsb',
			'ms_agen_produk',
			'ms_akta',
			'ms_ijin_usaha',
			'ms_iu_bsb',
			'ms_pemilik',
			'ms_pengalaman',
			'ms_pengurus',
			'ms_situ',
			'ms_tdp',
			'ms_vendor_admistrasi',
			'ms_vendor_pic'
		);

		$status = '';

		foreach ($table as $k => $v) {
			
			if ($v == 'ms_agen') {
				$query = "SELECT *,count(*) FROM ms_agen WHERE del = 0 GROUP BY id_vendor,no,principal HAVING COUNT(no) > 1";
				$data = $this->eproc_db->query($query)->result_array();
				$tbl = "ms_agen";
			}
			else if ($v == 'ms_akta') {
				$query = "SELECT *,count(*) FROM ms_akta WHERE del = 0 GROUP BY id_vendor,no,type,notaris HAVING COUNT(no) > 1;";
				$data = $this->eproc_db->query($query)->result_array();
				$tbl = "ms_akta";
			}
			else if ($v == 'ms_pengurus') {
				$query = "SELECT *,count(*) FROM ms_pengurus WHERE del = 0 GROUP BY id_vendor,no HAVING COUNT(no) > 1";
				$data = $this->eproc_db->query($query)->result_array();
				$tbl = "ms_pengurus";
			}
			else if ($v == 'ms_pemilik') {
				$query = "SELECT *,count(*) FROM ms_pemilik WHERE del = 0 GROUP BY id_vendor,percentage,name HAVING COUNT(percentage) > 1";
				$data = $this->eproc_db->query($query)->result_array();
				$tbl = "ms_pemilik";
			}
			// else if ($v == 'ms_ijin_usaha') {
			// 	$query = "SELECT *,count(*) FROM ms_ijin_usaha WHERE del = 0 GROUP BY id_vendor,id_dpt_type,type,no,authorize_by HAVING COUNT(no) > 1;";
			// 	$data = $this->eproc_db->query($query)->result_array();
			// }

			foreach ($data as $kd => $vd) {
				$this->eproc_db->where('id',$vd['id'])->update($tbl,array('del' => 1));
				$status .= 'deleted';
			}
		}

		echo $status."<br>";
	}
}