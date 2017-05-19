<div class="container-fluid">
	<div class="col-md-6 col-md-offset-3">
		<form action="<?php echo base_url('login') ?>" method="POST">
			<?php
			if(NULL != $this->input->get('error')){
				?>
				<div class="alert alert-danger text-center" role="alert">
					<?php echo urldecode($this->input->get('error')) ?>
				</div>
				<?php
			}
			?>
			<legend>Sign In</legend>
			<div class="alert alert-info" role="alert">
				<p><b>Make sure</b> you use valid address.</p>
			</div>
			<div class="form-group">
				<label for="inputAddress">XRB address</label>
				<input type="text" class="form-control" id="inputAddress" placeholder="Your XRB Address" name="inputAddress">
			</div>
			<button type="submit" name="submit" value="login" class="btn btn-primary col-md-12 col-xs-12">Login</button>
		</form>
	</div>
</div>