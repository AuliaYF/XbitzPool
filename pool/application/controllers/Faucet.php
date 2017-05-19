<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faucet extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('accountmodel', 'model');
	}

	public function index()
	{
		if(NULL == $this->session->userdata('xrb_address'))
			redirect('login');
		$data['active'] = 'faucet';
		$this->view('pages/faucet', $data);
	}

	public function validate(){
		if(NULL == $this->session->userdata('xrb_address'))
			redirect('login');
		$distribution = $this->db->get('distribution_history')->row();
		$accountId = $this->model->isAccountExist($this->session->userdata('xrb_address')['address'])->accountId;

		$claimCaptchas = json_decode($this->input->post('captchas'), TRUE);
		$arrCaptcha = array();
		foreach($claimCaptchas as $claimCaptcha){
			$claimStatus = "p";
			$claimTime = date("Y-m-d H:i:s", time() + 3600);
			
			$arrCaptcha[] = array(
				'accountId' => $accountId,
				'claimCaptcha' => $claimCaptcha,
				'claimStatus' => $claimStatus,
				'claimTime' => $claimTime
			);
		}
		$this->db->insert_batch('pending_claims', $arrCaptcha);
		echo json_encode(array("error" => "no"));
	}

	public function stat(){
		if(NULL == $this->session->userdata('xrb_address'))
			redirect('login');
		$this->db->cache_on();
		$distribution = $this->db->get('distribution_history')->row();
		$accountId = $this->model->isAccountExist($this->session->userdata('xrb_address')['address'])->accountId;
		
		$claimStat = $this->db->query("SELECT (SELECT COUNT(*) FROM pending_claims WHERE accountId = '" . $accountId . "' AND claimStatus = 'p' AND claimTime BETWEEN '" . date("Y-m-d H:i:s", $distribution->distributionCurrent + 3600) . "' AND '" . date("Y-m-d H:i:s", time() + 3600) . "') pendingClaims, (SELECT COUNT(*) FROM pending_claims WHERE accountId = '" . $accountId . "' AND claimStatus = 'd' AND claimTime BETWEEN '" . date("Y-m-d H:i:s", $distribution->distributionCurrent + 3600) . "' AND '" . date("Y-m-d H:i:s", time() + 3600) . "') validatedClaims")->row();
		echo json_encode(array(
			'pendingClaims' => intval($claimStat->pendingClaims),
			'validatedClaims' => intval($claimStat->validatedClaims)
		));
		$this->db->cache_off();
	}
}

/* End of file Faucet.php */
/* Location: ./application/controllers/Faucet.php */