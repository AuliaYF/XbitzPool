<?php
$paylist = json_decode(performGet('https://faucet.raiblockscommunity.net/paylist.php?acc=' . $this->config->item('xrb_address') . '&json=1'), TRUE);
$dbDistribution = $this->db->get('distribution_history')->row();
$threshold = (isset($paylist['threshold']) ? $paylist['threshold'] : 0);
$poolClaim = (isset($paylist['pending'][0]['pending']) ? $paylist['pending'][0]['pending'] : 0);
$poolMrai = (isset($paylist['pending'][0]['expected-pay']) ? $paylist['pending'][0]['expected-pay'] / 1000000 : 0);
?>
<div class="container-fluid">
	<div class="col-md-12">
		<div class="page-header">
			<h1>NEXT DISTRIBUTION IN <?php echo floor($paylist['eta'] / 60) ?> MINUTES</h1>
		</div>
		<div class="alert alert-info" role="alert">
			<p><b>Please note</b> that RaiBlocks's paylist takes time to update.</p>
			<p>List updated every <b>5</b> minutes.</p>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-offset-3 col-md-6">
				<table class="table">
					<thead>
						<tr>
							<th class="text-center">Accounts</th>
							<th class="text-center">Pool Claims</small></th>
							<th class="text-center">Threshold </th>
							<th class="text-center">Top 60</th>
						</tr>
					</thead>
					<tbody style="font-size: 18px;">
						<tr>
							<td class="text-center">
								<?php echo $poolClaim > 0 ? count($this->model->countMember(date("Y-m-d H:i:s", $dbDistribution->distributionCurrent + 3600), date("Y-m-d H:i:s", time() + 3600))) : "0" ?>
							</td>
							<td class="text-center">
								<?php echo number_format($poolClaim, 0, '.', ',') ?>
							</td>
							<td class="text-center">
								<?php echo number_format($threshold, 0, '.', ',') ?>
							</td>
							<td class="text-center">
								<?php
								if($poolClaim < $threshold)
									echo '<span class="text-danger">' . number_format(($threshold - $poolClaim), 0, '.', ',') . ' to Top 60</span>';
								else
									echo '<span class="text-success">Yes <i class="fa fa-check" aria-hidden="true"></i></span>';
								?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-title">
					Claims
				</div>
			</div>
			<div class="panel-body">

				<table class="table">
					<thead>
						<tr>
							<th class="text-right" width="80">#</th>
							<th class="text-left">Account</th>
							<th class="text-right" width="200">Claims</th>
							<?php
							if($poolMrai > 0){
								?>
								<th class="text-right" width="200">Mrai (XRB)</th>
								<?php
							}
							?>
						</tr>
					</thead>
					<tbody style="font-size: 18px;">
						<?php
						if($poolClaim > 0){
							$arr = $this->model->populatePaylist(date("Y-m-d H:i:s", $dbDistribution->distributionCurrent + 3600), date("Y-m-d H:i:s", time() + 3600));
							$no = 0;
							$totalClaim = 0;
							$totalMrai = 0;
							foreach ($arr as $row) {
								if($row->totalClaim > 0){
									$no++;
									?>
									<tr <?php echo "" /*$row->accountAddress == $this->session->userdata('xrb_address')['address'] ? "style=\"background-color: #A5D6A7;\"" : ""*/ ?>>
										<td class="text-right"><?php echo $no ?>.</td>
										<td><a href="https://raiblockscommunity.net/account/index.php?acc=<?php echo $row->accountAddress ?>" target="_blank"><?php echo $row->accountName ?></a></td>
										<td class="text-right"><?php echo number_format($row->totalClaim, 0, '.', ',') ?></td>
										<?php
										if($poolMrai > 0){
											$mrai = ($row->totalClaim / $poolClaim * $poolMrai);
											$totalMrai += $mrai;
											?>
											<td class="text-right"><?php echo number_format($mrai, 6, '.', ',') ?></td>
											<?php
										}
										?>
									</tr>
									<?php
									$totalClaim += $row->totalClaim;
								}
							}
						}
						?>
					</tbody>
					<tfoot style="font-size: 18px;">
						<tr>
							<td colspan="2">&nbsp;</td>
							<td>&nbsp;</td>
							<?php
							if($poolMrai > 0){
								?>
								<td>&nbsp;</td>
								<?php
							}
							?>
						</tr>
						<tr class="info">
							<td colspan="2" class="text-right">Total Claims</td>
							<td class="text-right"><?php echo number_format($totalClaim, 0, '.', ',') ?></td>
							<?php
							if($poolMrai > 0){
								?>
								<td class="text-right"><?php echo number_format($totalMrai, 6, '.', ',') ?></td>
								<?php
							}
							?>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>