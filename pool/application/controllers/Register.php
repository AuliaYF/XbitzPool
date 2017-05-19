<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('accountmodel', 'model');
		if(NULL !== $this->session->userdata('xrb_address'))
			redirect('index');
	}

	public function index()
	{
		if(NULL != $this->input->post('submit')){
			$continue = TRUE;
			if(strpos($this->input->post('inputAddress'), 'RaiWalletBot: ') !== false){
				$continue = FALSE;
			}
			if(strpos($this->input->post('inputAddress'), 'xrb_') === false){
				$continue = FALSE;
			}
			if(!filter_var($this->input->post('inputAddress'), FILTER_VALIDATE_EMAIL) === false){
				$continue = FALSE;
			}
			if(strlen($this->input->post('inputAddress')) < 64 || strlen($this->input->post('inputAddress')) > 64){
				$continue = FALSE;
			}
			if($continue){
				if($this->model->isUsernameExist(trim($this->input->post('inputFullname'))))
					redirect('login?error=' . urlencode("Account Exist"));
				if($this->model->isAccountExist(trim($this->input->post('inputAddress'))))
					redirect('login?error=' . urlencode("Account Exist"));
				$res = $this->model->registerAccount(trim($this->input->post('inputAddress')), trim($this->input->post('inputFullname')));
				if($res){
					$sess['xrb_address'] = array('address' => trim($this->input->post('inputAddress')));
					$this->session->set_userdata($sess);
					redirect('index');
				}
			}else{
				redirect('login?error=' . urlencode("Invalid Address"));
			}
		}else{
			redirect('login');
		}
	}

}

/* End of file Register.php */
/* Location: ./application/controllers/Register.php */