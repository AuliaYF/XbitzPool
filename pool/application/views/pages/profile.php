<?php
$account = $this->model->isAccountExist($this->session->userdata('xrb_address')['address']);
?>
<div class="container-fluid">
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-default">
			<div class="panel-heading">General Information</div>
			<div class="panel-body">
				<form class="form-horizontal" action="<?php echo base_url('profile') ?>" method="POST">
					<div class="form-group">
						<label class="col-sm-3 control-label">Fullname</label>
						<div class="col-sm-9">
							<p class="form-control-static" style="word-wrap: break-word;"><?php echo $account->accountName ?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">XRB Address</label>
						<div class="col-sm-9">
							<p class="form-control-static" style="word-wrap: break-word;"><?php echo $account->accountAddress ?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Available Balance</label>
						<div class="col-sm-9">
							<p class="form-control-static" style="word-wrap: break-word;"><?php echo number_format($account->accountBalance, 6, '.', ',') ?> Mrai</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Payout Threshold (Mrai)</label>
						<div class="col-sm-9">
							<input type="number" name="threshold" class="form-control" style="text-align: right" value="<?php echo $account->accountThreshold ?>" >
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-9">
							<button type="submit" name="submit" value="save" class="btn btn-primary col-md-12 col-xs-12">Save</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">Claim History <small>Today</small></div>
			<div class="panel-body">
				<table class="table">
					<thead>
						<tr>
							<th class="text-right" width="40">#</th>
							<th class="text-center">Datetime</th>
							<th class="text-right" width="180">Claim Rate</th>
							<th class="text-right" width="180">Total Claim</th>
							<th class="text-right" width="180">Mrai Received</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$claims = $this->db->where('accountId', $account->accountId)->where('DATE(createdTime)', date("Y-m-d"))->order_by('createdTime', 'DESC')->get('app_account_claim')->result();
						$no = 0;
						$totalClaim = 0;
						$totalMrai = 0;
						foreach ($claims as $row) {
							$no++;
							$totalClaim += $row->claimQty;
							$totalMrai += $row->claimMrai;
							?>
							<tr>
								<td class="text-right"><?php echo $no ?>.</td>
								<td class="text-center"><?php echo date("M d H:i:s", strtotime($row->createdTime)) ?></td>
								<td class="text-right"><?php echo number_format($row->claimRate, 6, '.', ',') ?> Mrai</td>
								<td class="text-right"><?php echo number_format($row->claimQty, 0, '.', ',') ?> Claims</td>
								<td class="text-right"><?php echo number_format($row->claimMrai, 6, '.', ',') ?> Mrai</td>
							</tr>
							<?php
						}
						?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="3">&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr class="info">
							<td colspan="3" class="text-right">Total</td>
							<td class="text-right"><?php echo number_format($totalClaim, 0, '.', ',') ?> Claims</td>
							<td class="text-right"><?php echo number_format($totalMrai, 6, '.', ',') ?> Mrai</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>

		<?php
		if(isBTC($account->accountAddress)){
			?>
			<div class="alert alert-info" role="alert">
				<b>Please note</b> that this payout is from pool. BTC Payout will be processed by your leader.
			</div>
			<?php
		}
		?>
		<div class="panel panel-default">
			<div class="panel-heading">Payout History <small>Last <b>10</b> Records</small></div>
			<div class="panel-body">
				<table class="table">
					<thead>
						<tr>
							<th class="text-right" width="40">#</th>
							<th class="text-center" width="180">Datetime</th>
							<th class="text-center">Block</th>
							<th class="text-center" width="80">Status</th>
							<th class="text-right" width="80">Amount</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$payouts = $this->db->where('accountId', $account->accountId)->order_by('payoutTime', 'DESC')->limit(10)->get('payout_history')->result();
						$no = 0;
						$totalMrai = 0;
						foreach($payouts as $row){
							$no++;
							$totalMrai += $row->payoutQty;
							?>
							<tr>
								<td class="text-right"><?php echo $no ?>.</td>
								<td class="text-center"><?php echo date("M d H:i:s", strtotime($row->payoutTime)) ?></td>
								<td class="text-center"><?php echo $row->payoutStatus == "p" ? "..." : '<a href="https://raiblockscommunity.net/block/index.php?h=' . $row->payoutHash . '" target="_blank">' . truncate($row->payoutHash, 40). '</a>' ?></td>
								<td class="text-center"><?php echo $row->payoutStatus == "p" ? "<span class=\"label label-warning\">Pending</span>" : "<span class=\"label label-success\">Completed</span>" ?></td>
								<td class="text-right"><?php echo number_format($row->payoutQty, 6, '.', ',') ?> Mrai</td>
							</tr>
							<?php
						}
						?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="4">&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr class="info">
							<td colspan="4" class="text-right">Total</td>
							<td class="text-right"><?php echo number_format($totalMrai, 6, '.', ',') ?> Mrai</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>