<?php
$timeStart = date("Y-m-d H:i:s", ((time() + 1 * 3600) - (24 * 3600)));
$timeEnd = date("Y-m-d H:i:s", time() + 1 * 3600);
$poolStat = $this->model->getPoolStat($timeStart, $timeEnd);
?>
<div class="container-fluid">
	<div class="col-md-12">
		<div class="page-header">
			<h1>
				Pool Statistics <small>Last <b>24h</b></small>
			</h1>
		</div>
		<div class="row">
			<div class="col-md-offset-3 col-md-6">
				<table class="table">
					<thead>
						<tr>
							<th class="text-center">Active Workers</th>
							<th class="text-center">Total Claims</th>
							<th class="text-center">Total Mrai</small></th>
						</tr>
					</thead>
					<tbody style="font-size: 18px;">
						<tr>
							<td class="text-center">
								<?php echo number_format($poolStat->activeWorker, 0, '.', ','); ?>
							</td>
							<td class="text-center">
								<?php echo number_format($poolStat->totalClaim, 0, '.', ','); ?>
							</td>
							<td class="text-center">
								<?php echo number_format($poolStat->totalMrai, 6, '.', ','); ?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="col-md-12">
		<div class="alert alert-info" role="alert">
			<p><b>Please note</b> that all payouts are being done manually due to no running node yet.</p>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="col-md-6">
		<div class="page-header">
			<h1>Pending <small>Last <b>24h</b></small></h1>
		</div>
		<table class="table">
			<thead>
				<tr>
					<th width="80" class="text-right">#</th>
					<th>Account</th>
					<th width="220" class="text-center">Datetime</th>
					<th width="220" class="text-right">Mrai</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$totalMrai = 0;
				$no = 0;
				$res = $this->db->select('t2.accountName, t2.accountAddress, t2.accountThreshold, t1.payoutId, t1.payoutQty, t1.payoutTime')->join('app_account t2', 't2.accountId = t1.accountId')->where('t1.payoutStatus', 'p')->where("t1.payoutTime BETWEEN '$timeStart' AND '$timeEnd'")->order_by('t1.payoutTime', 'DESC')->get('payout_history t1')->result();
				foreach($res as $row){
					$no++;
					$totalMrai += $row->payoutQty;
					?>
					<tr>
						<td class="text-right"><?php echo $no ?>.</td>
						<td><a href="https://raiblockscommunity.net/account/index.php?acc=<?php echo $row->accountAddress ?>" target="_blank"><?php echo $row->accountName ?></a></td>
						<td class="text-center"><?php echo date("M d H:i:s", strtotime($row->payoutTime)) ?></td>
						<td class="text-right"><?php echo number_format($row->payoutQty, 6, '.', ',') ?> Mrai</td>
					</tr>
					<?php
				}
				if($no == 0){
					?>
					<tr>
						<td colspan="4" class="text-center">No Data Available</td>
					</tr>
					<?php
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3">&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr class="info">
					<td colspan="3" class="text-right">Total</td>
					<td class="text-right"><?php echo number_format($totalMrai, 6, '.', ',') ?> Mrai</td>
				</tr>
			</tfoot>
		</table>
	</div>
	<div class="col-md-6">
		<div class="page-header">
			<h1>Paid <small>Last <b>24h</b></small></h1>
		</div>
		<table class="table">
			<thead>
				<tr>
					<th width="80" class="text-right">#</th>
					<th>Account</th>
					<th>Hash</th>
					<th width="220" class="text-center">Datetime</th>
					<th width="150" class="text-right">Mrai</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$totalMrai = 0;
				$no = 0;
				$res = $this->db->select('t2.accountName, t2.accountAddress, t1.payoutId, t1.payoutHash, t1.payoutQty, t1.payoutTime')->join('app_account t2', 't2.accountId = t1.accountId')->where('t1.payoutStatus', 'c')->where("t1.payoutTime BETWEEN '$timeStart' AND '$timeEnd'")->order_by('t1.payoutTime', 'DESC')->get('payout_history t1')->result();
				foreach($res as $row){
					$no++;
					$totalMrai += $row->payoutQty;
					?>
					<tr>
						<td class="text-right"><?php echo $no ?>.</td>
						<td><a href="https://raiblockscommunity.net/account/index.php?acc=<?php echo $row->accountAddress ?>" target="_blank"><?php echo $row->accountName ?></a></td>
						<td><a href="https://raiblockscommunity.net/block/index.php?h=<?php echo $row->payoutHash ?>" target="_blank"><?php echo truncate($row->payoutHash, 20) ?></a></td>
						<td class="text-center"><?php echo date("M d H:i:s", strtotime($row->payoutTime)) ?></td>
						<td class="text-right"><?php echo number_format($row->payoutQty, 6, '.', ',') ?> Mrai</td>
					</tr>
					<?php
				}
				if($no == 0){
					?>
					<tr>
						<td colspan="5" class="text-center">No Data Available</td>
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