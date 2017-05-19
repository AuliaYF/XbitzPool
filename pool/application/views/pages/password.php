<div class="container-fluid">
	<div class="col-md-6 col-md-offset-3">
		<form action="<?php echo base_url('login') ?>" method="POST">
			<legend>Login</legend>
			<div class="form-group">
				<label for="inputAddress">XRB address</label>
				<input type="text" class="form-control" id="inputAddress" placeholder="Your XRB Address" name="inputAddress" value="<?php echo $address ?>">
			</div>
			<div class="form-group">
				<label for="inputPassword">Password</label>
				<input type="password" class="form-control" id="inputPassword" placeholder="Admin Password" name="inputPassword">
			</div>
			<button type="submit" name="submit" value="register" class="btn btn-primary col-md-12 col-xs-12">Login</button>
		</form>
	</div>
</div>