<?php
$distribution = $this->db->get('distribution_history')->row();
$accountId = $this->model->isAccountExist($this->session->userdata('xrb_address')['address'])->accountId;
$claimStat = $this->db->query("SELECT (SELECT COUNT(*) FROM pending_claims WHERE accountId = '" . $accountId . "' AND claimStatus = 'p' AND claimTime BETWEEN '" . date("Y-m-d H:i:s", $distribution->distributionCurrent + 3600) . "' AND '" . date("Y-m-d H:i:s", time() + 3600) . "') pendingClaims, (SELECT COUNT(*) FROM pending_claims WHERE accountId = '" . $accountId . "' AND claimStatus = 'd' AND claimTime BETWEEN '" . date("Y-m-d H:i:s", $distribution->distributionCurrent + 3600) . "' AND '" . date("Y-m-d H:i:s", time() + 3600) . "') validatedClaims")->row();

#By using this tool, you are agreed to donate to RaiBlocks's dev.
?>
<div id="modal" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Attention!</h4>
			</div>
			<div class="modal-body">
				<p>
					Hi, <br>
					I'm closing the pool on Sunday May 21 GMT+7. All the mrai balance from active users for the past two weeks will be processed without any fee.
					<br><br>
					Best regards,
					uoy14
				</p>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-offset-3 col-md-6">
			<table class="table">
				<thead>
					<tr>
						<th class="text-center">Pool Claims</small></th>
						<th class="text-center">Threshold </th>
						<th class="text-center">Top 60</th>
						<th class="text-center">Distribution</th>
					</tr>
				</thead>
				<tbody style="font-size: 18px;">
					<tr>
						<td class="text-center" id="displayPoolClaims">
							...
						</td>
						<td class="text-center" id="displayThreshold">
							...
						</td>
						<td class="text-center" id="displayTop60">
							...
						</td>
						<td class="text-center" id="displayNextDistribution">
							...
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="col-md-6 col-md-offset-3">
		<div id="validationMessage" class="alert alert-danger text-center" role="alert" style="display: none;">
			Maintenance
		</div>
		<button id="claimButton" class="g-recaptcha btn btn-primary btn-lg col-xs-12" data-sitekey="6Lf0qx8UAAAAAN1eEGr2nSAFckLjjVuzXAw4qhHM" data-callback="onClaim">Claim</button>
		<div class="clearfix"></div>
		<br>
		<div class="row">
			<div class="row" id="claimStat">
				<div class="col-md-4 text-center">
					<h4>Current: <span id="currentClaims" class="label label-primary">0</span></h4>
				</div>
				<div class="col-md-4 text-center">
					<h4>Pending: <span id="pendingClaims" class="label label-warning"><?php echo $claimStat->pendingClaims ?></span></h4>
				</div>
				<div class="col-md-4 text-center">
					<h4>Validated: <span id="validatedClaims" class="label label-success"><?php echo $claimStat->validatedClaims ?></span></h4>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 text-center">
				<div class="checkbox">
					<label>
						<input type="checkbox" id="nightToggle" value="1"> Night Mode
					</label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<h4 class="text-center"><a href="https://faucet.raiblockscommunity.net/paylist.php?acc=<?php echo $this->config->item('xrb_address') ?>" target="_blank">View Pool Progress</a></h4>
				<br>
				<p class="text-center" id="donateInformation"><b></b></p>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('#modal').modal({
			show: true
		});
	});
</script>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="<?php echo base_url('assets/js/faucet.js') ?>"></script>
