<div class="container-fluid">
	<div class="col-md-12">
		<?php
		$pendings = $this->db->select('t2.accountName, t2.accountAddress, t2.accountThreshold, t1.payoutId, t1.payoutQty, t1.payoutProfit')->join('app_account t2', 't2.accountId = t1.accountId')->where('t1.payoutStatus', 'p')->get('payout_history t1')->result();
		$cmd = 0;
		$fee = 0;
		foreach ($pendings as $row) {
			$cmd++;
			$fee += $row->payoutProfit;
			?>
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-3 control-label">Fullname</label>
							<div class="col-sm-9">
								<p class="form-control-static" style="word-wrap: break-word;"><?php echo $row->accountName ?></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">XRB Address</label>
							<div class="col-sm-9">
								<p class="form-control-static" style="word-wrap: break-word;"><?php echo $row->accountAddress ?></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Amount</label>
							<div class="col-sm-9">
								<p class="form-control-static" style="word-wrap: break-word;"><?php echo number_format($row->payoutQty, 6, '.', ',') ?> Mrai</p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Block</label>
							<div class="col-sm-9">
								<input type="hidden" id="inputAccount[<?php echo $row->payoutId ?>]" value="<?php echo $row->accountAddress ?>">
								<input type="hidden" id="inputAmount[<?php echo $row->payoutId ?>]" value="<?php echo number_format($row->payoutQty, 6, '.', '') ?>">
								<input type="text" class="form-control hash-field" id="inputBlock[<?php echo $row->payoutId ?>]" placeholder="Transaction hash">
							</div>
						</div>
						<div class="row">
							<div class="col-sm-offset-3 col-sm-9">
								<div class="row">
									<div class="col-xs-6">
										<button id="send[<?php echo $row->payoutId ?>]" class="btn btn-warning col-xs-12 send-btn"><i class="fa fa-refresh fa-spin" style="display: none; margin-right: 5px;"></i>Send</button>
									</div>
									<div class="col-xs-6">
										<button id="submit[<?php echo $row->payoutId ?>]" class="btn btn-primary col-xs-12 process-btn"><i class="fa fa-refresh fa-spin" style="display: none; margin-right: 5px;"></i>Process</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		if($cmd == 0){
			?>
			<div class="page-header">
				<h1>No Pending Payouts</h1>
			</div>
			<?php
		}else{
			?>
			<div class="page-header">
				<h1>Take Fee</h1>
			</div>
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-3 control-label">Amount</label>
							<div class="col-sm-9">
								<p class="form-control-static" style="word-wrap: break-word;"><?php echo number_format($fee, 6, '.', ',') ?> Mrai</p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Account</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="inputAccount[fee]" value="<?php echo $this->session->userdata('xrb_address')['address']; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Block</label>
							<div class="col-sm-9">
								<input type="hidden" id="inputAmount[fee]" value="<?php echo number_format($fee, 6, '.', '') ?>">
								<input type="text" class="form-control hash-field" id="inputBlock[fee]" placeholder="Transaction hash" readonly="readonly">
							</div>
						</div>
						<div class="row">
							<div class="col-sm-offset-3 col-sm-9">
								<div class="row">
									<div class="col-xs-12">
										<button id="send[fee]" class="btn btn-warning col-xs-12 send-btn"><i class="fa fa-refresh fa-spin" style="display: none; margin-right: 5px;"></i>Send</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>
<script type="text/javascript">
	var lastSend = new Date().getTime();
	function makeBaseAuth(user, pswd){ 
		var token = user + ':' + pswd;
		var hash = "";
		if (btoa) {
			hash = btoa(token);
		}
		return "Basic " + hash;
	}
	$(document).ready(function(){
		$('.send-btn').on('click', function(){
			if(lastSend != ''){
				var execTime = new Date().getTime();
				var seconds = (execTime - lastSend) / 1000;
				if(seconds >= 10){

					var $this = $(this);
					var payoutId = $(this).attr('id').replace('send[', '').replace(']', '');
					var $hashField = $(".hash-field#inputBlock\\[" + payoutId + "\\]");
					var $accountField = $("#inputAccount\\[" + payoutId + "\\]");
					var $amountField = $("#inputAmount\\[" + payoutId + "\\]");

					$this.prop('disabled', 'disabled');
					$("i", $this).css('display', 'inline-block');

					$.ajax({
						type: "POST",
						url: "<?php echo $this->config->item('api_url') ?>",
						dataType : "JSON",
						data: "payoutId=" + payoutId + "&account=" + $accountField.val() + "&amount=" + $amountField.val(),
						crossDomain: true,
						success: function(data){
							if(data.message != "error"){
								$hashField.val(data.message.block)
								$hashField.prop('disabled', 'disabled');
								$hashField.parent().parent().addClass('has-success');
								$this.remove();
							}else if(data.message == "double"){
								alert('WARNING!!!\nAlready spent. Copy block from raiblock.');
								$this.remove();
							}else{
								$hashField.parent().parent().addClass('has-error');
								$("i", $this).css('display', 'none');
								$this.removeAttr('disabled');
							}

							lastSend = new Date().getTime();
						},
						error: function(data){
							$hashField.parent().parent().addClass('has-error');
							$("i", $this).css('display', 'none');
							$this.removeAttr('disabled');
						}
					});
				}else{
					alert('Please wait 10 seconds...');
				}
			}
		});

		$('.process-btn').on('click', function(){
			var $this = $(this);
			var payoutId = $(this).attr('id').replace('submit[', '').replace(']', '');
			var $hashField = $(".hash-field#inputBlock\\[" + payoutId + "\\]");
			if($hashField.val() == ''){
				$hashField.parent().parent().addClass('has-error');
				return;
			}
			
			$this.prop('disabled', 'disabled');
			$("i", $this).css('display', 'inline-block');
			
			$.ajax({
				type: "POST",
				url: "<?php echo base_url('pending1432/process') ?>",
				dataType : "JSON",
				data: "payoutId=" + payoutId + "&payoutHash=" + $hashField.val(),
				success: function(data){
					if(data.status == "1"){
						$hashField.prop('disabled', 'disabled');
						$hashField.parent().parent().addClass('has-success');
						$this.remove();
					}else{
						$hashField.parent().parent().addClass('has-error');
					}
				},
				error: function(data){
					$hashField.parent().parent().addClass('has-error');
				}
			});
		});

		$('.hash-field').on('keyup', function(){
			$(this).parent().parent().removeClass('has-error');
		});
	});
</script>