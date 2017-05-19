<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'third_party/Guzzle/autoloader.php';
use GuzzleHttp\Client;
class Payout1432 extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('accountmodel', 'model');
		// if(NULL === $this->session->userdata('xrb_address'))
		// 	redirect('login');
	}

	public function index()
	{
		$data['active'] = 'payout1432';
		$this->view('pages/payout', $data);
	}

	public function cli($time = NULL, $mrai = 0){
		error_reporting(-1);
		ini_set('display_errors', 1);
		
		$startTime = microtime(true);
		$arrDistribution = json_decode(performGet('https://faucet.raiblockscommunity.net/history.php?json=1'), TRUE);

		$dbDistribution = $this->db->get('distribution_history')->row();
		$disStart = $time == NULL ? $arrDistribution['distributions'][0]['time_start'] : $time;
		//$disStart = 1493953202;
		if($dbDistribution->distributionCurrent != $disStart){
			$distributionSingle = json_decode(performGet('https://faucet.raiblockscommunity.net/distribution-single.php?did=' . $disStart . '&json=1'), TRUE);
			$indexPool = getIndexByValue($distributionSingle['pending'], 'account', $this->config->item('xrb_address'));
			if(NULL != $indexPool || NULL != $time){
				$disMrai = $time == NULL ? $distributionSingle['pending'][$indexPool]['amount'] / 1000000 : $mrai;
				//$disMrai = 1836;

				$paylist = $this->model->populatePaylist(date("Y-m-d H:i:s", $dbDistribution->distributionCurrent + 3600), date("Y-m-d H:i:s", $disStart + 3600));
						// $poolClaim = number_format($distributionSingle['pending'][$indexPool]['ask'], 0, '.', '');
				$poolClaim = $this->model->countClaims(date("Y-m-d H:i:s", $dbDistribution->distributionCurrent + 1 * 3600), date("Y-m-d H:i:s", $disStart + 1 * 3600));

				foreach ($paylist as $row) {
					if($row->totalClaim > 0){
						$claimRate = $disMrai / $poolClaim;
						$memberMrai = $row->totalClaim / $poolClaim * $disMrai;
						$claimMrai = $memberMrai;
						$memberMrai = $memberMrai + $row->accountBalance;
						if($memberMrai >= $row->accountThreshold){
							$payoutMrai = $memberMrai - ((2 / 100) * $memberMrai);
							$payoutProfit = ((2 / 100) * $memberMrai);
							foreach($this->db->where('accountId', $row->accountId)->where('payoutStatus', 'p')->get('payout_history')->result() as $payout){
								$payoutMrai = $payoutMrai + $payout->payoutQty;
								$payoutProfit = $payoutProfit + $payout->payoutProfit;

								$this->db->where('payoutId', $payout->payoutId)->delete('payout_history');
							}
							$this->db->insert('payout_history', array(
								'accountId' => $row->accountId,
								'payoutProfit' => number_format($payoutProfit, 6, '.', ''),
								'payoutQty' => number_format($payoutMrai, 6, '.', ''),
								'payoutTime' => date("Y-m-d H:i:s"),
								'payoutStatus' => 'p'
							));
							if($row->accountBalance > 0){
								$this->db->where('accountId', $row->accountId)->update('app_account', array(
									'accountBalance' => '0'
								));
							}
							echo "payout to: " . $row->accountAddress . ' ' . number_format($payoutMrai, 6, '.', '') . " mrai <br>";
						}else{
							$this->db->where('accountId', $row->accountId)->update('app_account', array(
								'accountBalance' => number_format($memberMrai, 6, '.', '')
							));
							echo "updating balance to: " . $row->accountAddress . ' ' . number_format($memberMrai, 6, '.', '') . " mrai <br>";
						}
						$this->db->insert('app_account_claim', array(
							'accountId' => $row->accountId,
							'claimQty' => $row->totalClaim,
							'claimRate' => number_format($claimRate, 6, '.', ','),
							'claimMrai' => number_format($claimMrai, 6, '.', ''),
							'createdTime' => date("Y-m-d H:i:s")
						));
					}
				}
				$this->db->where('distributionId', 1)->update('distribution_history', array(
					'distributionPrevious' => $dbDistribution->distributionCurrent,
					'distributionCurrent' => $disStart
				));

				$this->db->query("DELETE FROM pending_claims WHERE claimTime < '" . date("Y-m-d H:i:s", $disStart + 3600) . "'");
			}else {
				echo 'pool not in top 60';
			}
		}else{
			echo 'distribution already done.';
		}

		$rowCount = $this->db->query("SELECT COUNT(*) count FROM cron_history")->row()->count;
		if($rowCount >= 1000)
			$this->db->truncate('cron_history');
		$time_elapsed_secs = microtime(true) - $startTime;
		$this->db->insert('cron_history', array('cronDuration' => number_format($time_elapsed_secs, 3, '.', ',') . " ms", 'cronTime' => date('Y-m-d H:i:s'), 'cronType' => 'distribution'));
	}
}

/* End of file Index.php */
/* Location: ./application/controllers/Index.php */
