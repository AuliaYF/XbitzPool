<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paylist extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('accountmodel', 'model');
		if(NULL === $this->session->userdata('xrb_address'))
			redirect('login');
	}

	public function index()
	{
		$this->output->cache(5);
		$data['active'] = 'paylist';
		$this->view('pages/paylist', $data);
		// echo 'Maintenance';
	}

	public function test(){
		// $dbDistribution = $this->db->get('distribution_history')->row();
		// $arr = $this->model->populatePaylist(date("Y-m-d H:i:s", $dbDistribution->distributionCurrent + 3600), date("Y-m-d H:i:s", time() + 3600));
		// foreach ($arr as $row) {
		// 	if($row->totalClaim > 0){
		// 		echo $row->accountName . ", ";
		// 		$totalClaim = 0;
		// 		for ($i = 0; $i < 60; $i++) {
		// 			$totalClaim++;
		// 			$this->db->insert('pending_claims', array('accountId' => $row->accountId, 'claimCaptcha' => 'bonus', 'claimTime' => '2017-04-20 07:10:12', 'claimStatus' => 'd'));
		// 		}

		// 		echo $totalClaim . '<br>';
		// 	}
		// }

		// $this->db->where('claimStatus', 'i')->update('pending_claims', array('claimStatus' => 'd'));
	}

}

/* End of file Paylist.php */
/* Location: ./application/controllers/Paylist.php */