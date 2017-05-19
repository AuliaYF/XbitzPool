<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pending1432 extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('accountmodel', 'model');
		if(NULL === $this->session->userdata('xrb_address'))
			redirect('login');
		else{
			if(!isAdmin($this->session->userdata['xrb_address']['address']))
				redirect('login');
		}
	}

	public function index()
	{
		$data['active'] = 'payout1432';
		$this->view('pages/pending', $data);
	}

	public function process(){
		$res = $this->db->where('payoutId', $this->input->post('payoutId'))->update('payout_history', array(
			'payoutHash' => $this->input->post('payoutHash'),
			'payoutStatus' => 'c'
		));

		echo json_encode(
			array(
				'status' => $this->db->affected_rows() > 0 ? '1' : '0'
			)
		);
	}
}

/* End of file Pending1432.php */
/* Location: ./application/controllers/Pending1432.php */