<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	public function index()
	{
		$resAccount = file_get_contents('https://raiblockscommunity.net/account/index.php?acc=xrb_3o7iocfcx1gcpa4q33ze56jmgicgct15kz4b4feu8mwxr43ju3t8cu668nei&json=1');
		$arrAccount = json_decode($resAccount, TRUE);
		$tArrTx = $arrAccount['history'];
		$arrTx = array();
		foreach($tArrTx as $obj){
			if($obj['type'] == "receive" && $obj['account'] == "xrb_13ezf4od79h1tgj9aiu4djzcmmguendtjfuhwfukhuucboua8cpoihmh8byo")
				$arrTx[] = $obj;
		}
		$lastIndex = $this->array_search2d_by_field("ACA94885D0800A78DAC4B0F919973C2D8E02C90804715D97B4A5947BE73B66AB", $arrTx, "hash");
		for ($i = ($lastIndex-1); $i >= 0; $i--) {
			echo $arrTx[$i]['hash'] . "<br>";			
		}
	}

	private function array_search2d_by_field($needle, $haystack, $field) {
		foreach ($haystack as $index => $innerArray) {
			if (isset($innerArray[$field]) && $innerArray[$field] === $needle) {
				return $index;
			}
		}
		return false;
	}

}

/* End of file Test.php */
/* Location: ./application/controllers/Test.php */