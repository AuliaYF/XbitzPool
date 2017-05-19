<?php
$sess = $this->session->userdata('xrb_address');
$account = NULL;
if(NULL != $sess)
  $account = $this->model->isAccountExist($sess['address']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="<?php echo base_url('favicon.ico') ?>" />
  <title>XRB Pool</title>

  <link href="<?php echo base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.min.css') ?>">

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="<?php echo base_url('assets/js/jquery.min.js') ?>"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>

  <script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';
    var xrb_address = '<?php echo $this->config->item('xrb_address') ?>';
  </script>
</head>
<body>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="col-md-12">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo base_url() ?>">
            <span><img alt="XRB Pool" src="<?php echo base_url('assets/img/logo.png') ?>" width="20" height="20" style="display: inline-block; margin-top: -5px; margin-right: 5px"></span>
            XRB Pool
          </a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right">
            <li <?php echo $active == "home" ? "class='active'" : '' ?>><a href="<?php echo base_url() ?>">Home</a></li>
            <li <?php echo $active == "payouts" ? "class='active'" : '' ?>><a href="<?php echo base_url('payouts') ?>">Payouts</a></li>
            <?php
            if($this->uri->segment(1) != 'paylist'){
              if(isAdmin(@$account->accountAddress)){
                ?>
                <li class="dropdown <?php echo $active == "payout1432" || $active == "pending1432" ? "active" : '' ?>">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Payout <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="<?php echo base_url('payout1432/cli') ?>">Override Distribution</a></li>
                    <li><a href="<?php echo base_url('pending1432') ?>">Pending</a></li>
                  </ul>
                </li>
                <?php
              }
            }
            ?>
          	<li <?php echo $active == "logs" ? "class='active'" : '' ?>><a href="<?php echo base_url('logs') ?>">Logs</a></li>
            <?php
            if(NULL != $account){
              ?>
              <li <?php echo $active == "paylist" ? "class='active'" : '' ?>><a href="<?php echo base_url('paylist') ?>">Paylist</a></li>
              <li <?php echo $active == "faucet" ? "class='active'" : '' ?>><a href="<?php echo base_url('faucet') ?>">Faucet</a></li>
              <li class="dropdown <?php echo $active == "account" ? "active" : '' ?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="<?php echo base_url('profile') ?>">Profile</a></li>
                  <li role="separator" class="divider"></li>
                  <li><a href="<?php echo base_url('logout') ?>">Logout</a></li>
                </ul>
              </li>
              <?php
            }else{
              ?>
              <li <?php echo $active == "login" ? "class='active'" : '' ?>><a href="<?php echo base_url('login') ?>">Login</a></li>
              <?php
            }
            ?>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <?php
  if(!empty($content)){
    $this->load->view($content);
  }
  ?>
</body>
</html>