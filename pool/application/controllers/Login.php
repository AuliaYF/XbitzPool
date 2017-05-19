<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('accountmodel', 'model');
	}

	public function index()
	{
		$content = 'pages/login';
		$data['active'] = 'login';

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
				$account = $this->model->isAccountExist(trim($this->input->post('inputAddress')));
				if(NULL == $account){
					$data['address'] = $this->input->post('inputAddress');
					$content = 'pages/register';
				}else{
					
					if(isAdmin(trim($this->input->post('inputAddress')))){
						if(trim($this->input->post('inputPassword')) != 'p0ol4dmin'){
							$data['address'] = trim($this->input->post('inputAddress'));
							$content = 'pages/password';
							$this->view($content, $data);
							return;
						}
					}
					if($account->accountStatus == 't'){
						$sess['xrb_address'] = array('address' => trim($this->input->post('inputAddress')));
						$this->session->set_userdata($sess);
						redirect('index');
					}else{
						redirect('login?error=' . urlencode("Account Suspended."));
					}
				}
			}else{
				redirect('login?error=' . urlencode("Invalid Address"));
			}
		}
		$this->view($content, $data);
	}

}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */