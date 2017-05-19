<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payouts extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('accountmodel', 'model');
	}

	public function index()
	{
		// $this->output->cache(5);
		$data['active'] = 'payouts';
		$this->view('pages/payouts', $data);
	}

}

/* End of file Payouts.php */
/* Location: ./application/controllers/Payouts.php */