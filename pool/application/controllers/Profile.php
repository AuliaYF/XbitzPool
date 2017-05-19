<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('accountmodel', 'model');
		if(NULL === $this->session->userdata('xrb_address'))
			redirect('login');
	}

	public function index()
	{
		if(NULL !== $this->input->post('submit')){
			$account = $this->model->isAccountExist($this->session->userdata('xrb_address')['address']);
			if($this->input->post('threshold') >= 50 && $this->input->post('threshold') <= 1000)
				$this->db->where('accountId', $account->accountId)->update('app_account', array(
					'accountThreshold' => $this->input->post('threshold')
				));
		}
		$data['active'] = 'account';
		$this->view('pages/profile', $data);
	}

}

/* End of file Profile.php */
/* Location: ./application/controllers/Profile.php */