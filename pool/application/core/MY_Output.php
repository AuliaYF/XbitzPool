<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class MY_Output extends CI_Output {

	function _display_cache(&$CFG, &$URI){
		
		if (in_array(@$_SESSION['xrb_address']['address'], array(
			'xrb_3o7iocfcx1gcpa4q33ze56jmgicgct15kz4b4feu8mwxr43ju3t8cu668nei',
			'xrb_1y8ib51kuzf61mak6ojex3oor7kgntcc57rdwmt517a7nmnxpgxaxy7pgd84'
			))){
			return FALSE;
		}
		
		return parent::_display_cache($CFG,$URI);
	}
}

/* End of file MY_Output.php */
/* Location: ./application/core/MY_Output.php */