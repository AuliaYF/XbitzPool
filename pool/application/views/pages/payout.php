<?php
$account = $this->model->isAccountExist($this->session->userdata('xrb_address')['address']);
if($account->accountName != "AuliaYF")
	redirect('index');
?>
<div class="container-fluid">
	<div class="col-md-12">
		<?php
		$arrDistribution = NULL;
		while(!$arrDistribution){
			$json = file_get_contents('https://raiblockscommunity.net/faucet/distribution.php?json=1');
			$arrDistribution = json_decode($json, TRUE);
		}

		$dbDistribution = $this->db->get('distribution_history')->row();
		$disStart = $arrDistribution[0]['time_start'];
		// $disStart = 1491868800;
		$disMrai = $arrDistribution[0]['reward'] / 1000000;
		if($dbDistribution->distributionCurrent != $disStart){
			$paylist = $this->model->populatePaylist(date("Y-m-d H:i:s", $dbDistribution->distributionCurrent + 1 * 3600), date("Y-m-d H:i:s", $disStart + 1 * 3600));
			$poolClaim = $this->model->countClaims(date("Y-m-d H:i:s", $dbDistribution->distributionCurrent + 1 * 3600), date("Y-m-d H:i:s", $disStart + 1 * 3600));

			foreach ($paylist as $row) {
				if($row->totalClaim > 0){
					$memberMrai = $row->totalClaim / $poolClaim * ($poolClaim * $disMrai);
					if($memberMrai >= 100){
						$payoutMrai = $memberMrai - ((1 / 100) * $memberMrai);
						$this->db->insert('payout_history', array(
							'accountId' => $row->accountId,
							'payoutQty' => number_format($payoutMrai, 0, '.', ''),
							'payoutTime' => date("Y-m-d H:i:s"),
							'payoutStatus' => 'p'
						));
						if($row->accountBalance > 0){
							$this->db->where('accountId', $row->accountId)->update('app_account', array(
								'accountBalance' => $row->accountBalance - number_format($memberMrai, 0, '.', '')
							));
						}
						echo "payout to: " . $row->accountAddress . ' ' . number_format($payoutMrai, 0, '.', '') . " mrai <br>";
					}else{
						$this->db->where('accountId', $row->accountId)->update('app_account', array(
							'accountBalance' => $row->accountBalance + number_format($memberMrai, 0, '.', '')
						));
						echo "updating balance to: " . $row->accountAddress . ' ' . number_format($memberMrai, 0, '.', '') . " mrai <br>";
					}
					$this->db->insert('app_account_claim', array(
						'accountId' => $row->accountId,
						'claimQty' => $row->totalClaim,
						'claimMrai' => number_format($memberMrai, '0', '.', ''),
						'createdTime' => date("Y-m-d H:i:s")
					));
				}
			}
			$this->db->where('distributionId', 1)->update('distribution_history', array(
				'distributionPrevious' => $dbDistribution->distributionCurrent,
				'distributionCurrent' => $disStart
			));
		}else{
			echo 'distribution already done.';
		}
		?>
	</div>
</div>
