<div class="container-fluid">
	<div class="col-md-12">
		<div class="alert alert-info" role="alert">
		<b>Please note</b> that there will be invalid claims. We are trying our best to decrease it.
		</div>
		<div class="page-header">
			<h1>FAUCET LOGS <small>LAST <b>20</b> RECORDS</small></h1>
		</div>
		<table class="table">
			<thead>
				<tr>
					<th width="80" class="text-right">#</th>
					<th width="220" class="text-center">Datetime</th>
					<th>Status</th>
					<th width="120" class="text-right">Attempts</th>
					<th width="120" class="text-right">Claims Sent</th>
					<th width="120" class="text-right">Valid Claims</th>
					<th width="120" class="text-right">Invalid Claims</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$arrAttemptColor = array(
					"1" => "text-success",
					"2" => "text-warning",
					"3" => "text-danger"
					);
				$no = 0;
				$lastCronTime = "";
				$totalClaimsSent = 0;
				$totalServerValidClaims = 0;
				$totalInvalidClaims = 0;
				$logs = $this->db->where('cronType', 'faucet')->order_by('cronTime', 'DESC')->limit(20)->get('cron_history')->result();
				foreach($logs as $log){
					$no++;
					$cronContent = json_decode($log->cronContent, TRUE);
					$claimsSent = count(json_decode($cronContent['claimsSent'], TRUE));
					$serverValidClaims = count(json_decode($cronContent['serverValidClaims'], TRUE));
					$invalidClaims = $claimsSent - $serverValidClaims;

					$totalClaimsSent += $claimsSent;
					$totalServerValidClaims += $serverValidClaims;
					$totalInvalidClaims += $invalidClaims;
					?>
					<tr>
						<td class="text-right"><?php echo $no ?>.</td>
						<td class="text-center"><?php echo date("M d H:i:s", strtotime($log->cronTime)) ?></td>
						<td><b><?php echo $cronContent['serverError'] == "no" ? "<span class=\"text-success\">OK ( " . $log->cronDuration . " )</span>" : "<span class=\"text-danger\">" . strtoupper($cronContent['serverError']) . " ( " . $log->cronDuration . " )</span>" ?></b></td>
						<td class="text-right"><b><?php echo isset($cronContent['validationAttemps']) ? "<span class=\"{$arrAttemptColor[$cronContent['validationAttemps']]}\">{$cronContent['validationAttemps']}</span>" : '-' ?></b></td>
						<td class="text-right"><?php echo number_format($claimsSent, 0, '.', ',') ?></td>
						<td class="text-right"><?php echo number_format($serverValidClaims, 0, '.', ',') ?></td>
						<td class="text-right"><?php echo number_format($invalidClaims, 0, '.', ',') ?></td>
					</tr>
					<?php

				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="4">&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr class="info">
					<td colspan="4" class="text-right">Total</td>
					<td class="text-right"><?php echo number_format($totalClaimsSent, 0, '.', ',') ?></td>
					<td class="text-right"><?php echo number_format($totalServerValidClaims, 0, '.', ',') ?></td>
					<td class="text-right"><?php echo number_format($totalInvalidClaims, 0, '.', ',') ?></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>