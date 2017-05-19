<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
    	$this->load->model('accountmodel', 'model');
	}

	public function index()
	{
		$data['active'] = 'logs';
		$this->view('pages/logs', $data);
	}

}

/* End of file Logs.php */
/* Location: ./application/controllers/Logs.php */