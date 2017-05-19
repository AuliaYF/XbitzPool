<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('accountmodel', 'model');
	}

	public function index()
	{
		$data['active'] = 'home';
		$this->view('pages/home', $data);
	}

}

/* End of file Index.php */
/* Location: ./application/controllers/Index.php */