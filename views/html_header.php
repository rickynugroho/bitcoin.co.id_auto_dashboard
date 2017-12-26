<?php
session_start();
$list_of_currency = array();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Bitcoin.co.id Auto Dashboard</title>
	
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
	<link rel="stylesheet" href="css/app.css">
</head>
<body>
	<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark mb-4">
		<a class="navbar-brand" href="<?php echo $base_url;?>">Bitcoin.co.id Auto Dashboard</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarCollapse">
			<ul class="navbar-nav mr-auto"></ul>
			
			<ul class="navbar-nav mt-2 mt-md-0">
				<?php
				if($requestUrl == 'dashboard'){
					?>
					<li class="nav-item bordered-right">
						<button type="button" class="btn btn-dark refresh-idr-value" data-toggle="tooltip" data-placement="bottom" title="Refresh IDR value"><span class="oi oi-reload"></span></button>
					</li>
					<li class="nav-item bordered-right">
						<button type="button" class="btn btn-dark pending-list-btn" data-toggle="tooltip" data-placement="bottom" title="Pending Order List"><span class="oi oi-media-pause"></span></button>
					</li>
					<li class="nav-item bordered-right">
						<a class="nav-link">
							<input type="hidden" id="idr-balance" value="<?php echo isset($getInfo['return']['balance']['idr']) ? $getInfo['return']['balance']['idr'] : 0;?>">
							<input type="hidden" id="total-asset" value="0">
							<b>IDR: <span id="idr-wallet"><?php echo number_format(isset($getInfo['return']['balance']['idr']) ? $getInfo['return']['balance']['idr'] : 0);?></span></b>
						</a>
					</li>
					<li class="nav-item bordered-right">
						<a class="nav-link">Estimates Asset:  
							<span id="estimates_asset">(loading...)</span>
						</a>
					</li>
					<li class="nav-item">
						<div class="dropdown">
							<button class="btn btn-dark dropdown-toggle" style="cursor:pointer;" type="button" id="accountDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<?php echo isset($getInfo['return']['name']) ? $getInfo['return']['name'] : '';?>
							</button>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="accountDropdown">
								<a class="dropdown-item" href="<?php echo $base_url . 'api_configuration';?>">Change API Configuration</a>
								<a class="dropdown-item" href="<?php echo $base_url . 'password_protection';?>">Password Protection</a>
								<?php
								if(isset($_SESSION["user_id"]) && $_SESSION["user_id"] != ''){
									?>
									<a class="dropdown-item" href="<?php echo $base_url . 'logout';?>">Logout</a>
									<?php
								}
								?>
							</div>
						</div>
					</li>
					<?php
				}
				?>
			</ul>
		</div>
	</nav>
	
	<div class="container-fluid" style="margin-top:90px;">
