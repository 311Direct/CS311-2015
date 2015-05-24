<?php include_once('templates/ensure-logged-in.php'); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Direct.</title>
		<link href="css/reset.css" rel="stylesheet" media="all">
		<link href="css/bootstrap.css" rel="stylesheet" media="all">		
		<link href="css/jquery-ui.css" rel="stylesheet" media="all">
		<link href="css/fonts.css" rel="stylesheet">		
		<link href="css/style.css" rel="stylesheet" media="all">
		<link href="css/style-desktop.css" rel="stylesheet" media="all">
		<link href="css/notifications.css" rel="stylesheet" media="all">
		<link href="css/scrollbar.css" rel="stylesheet">
		<link href="css/activity-precedence-network.css" rel="stylesheet">
		<link href="css/cocomo1.css" rel="stylesheet">
		<link href="css/dhtmlxgantt.css" rel="stylesheet">
		<link href="css/gantt.css" rel="stylesheet">		
		<!-- put some javascript includes here, if it needs to be loaded first before anything else -->
		<script src="js/jquery.js"></script>
		<script src="js/load-content.js"></script>
	</head>
	<body class="html-body">
		<div class="container-fluid">
			<header class="row">
				<h1 class="col-xs-9 col-md-4 product-title">
					<a href="task-list.php" class="logo-anchor"><img src="img/logo.png" alt="" class="logo"/><span class="page-type-container"> your <span class="page-type"></span></span></a>
				</h1>

				<?php
				$atLoginPg = (strpos($_SERVER['PHP_SELF'], 'login') !== false);
				if (!$atLoginPg) {
				?>				
				<div class="col-md-4 col-md-offset-4 visible-md visible-lg quicklink-tray">					
					<a class="col-md-2" href="search.php">
						<span class="glyphicon glyphicon-search"></span>
					</a>
					<a class="col-md-2 btn-print" href="javascript:void(0)">
						<span class="glyphicon glyphicon-print"></span>
					</a>
					<a class="col-md-2 btn-notifications" href="javascript:void(0)">
						<span class="glyphicon glyphicon-comment"></span>
					</a>
					<a class="col-md-2 btn-edit" href="javascript:void(0)">
						<span class="glyphicon glyphicon-pencil"></span>
					</a>
					<a class="col-md-2 btn-logout" href="javascript:void(0)">
						<span class="glyphicon glyphicon-log-out"></span>
					</a>
				</div>
				<div class="col-xs-3 mobile-menu-container visible-xs visible-sm">
					<span class="glyphicon glyphicon-th-list"></span>
				</div>
				<?php
				}
				?>				
				<nav class="col-xs-12">
					<?php
					$atLoginPg = (strpos($_SERVER['PHP_SELF'], 'login') !== false);
					if (!$atLoginPg) {
					?>
									
					<ul class="row">
						<li class="col-xs-3">
							<a href="search.php">
								<span class="glyphicon glyphicon-search"></span>
							</a>
						</li>
						<li class="col-xs-3 btn-notifications">
							<span class="glyphicon glyphicon-comment"></span>
						</li>
						<li class="col-md-3 btn-edit">
							<span class="glyphicon glyphicon-pencil"></span>					
						</li>
						<li class="col-xs-3 btn-logout">
							<span class="glyphicon glyphicon-log-out"></span>
						</li>										
					</ul>
					
					<?php
					}
					?>					
				</nav>
			</header>
			<div class="row visible-md visible-lg header-border-bottom"></div>
			
			<?php
			$atLoginPg = (strpos($_SERVER['PHP_SELF'], 'login') !== false);
			if (!$atLoginPg) {
			?>			
			<div class="row content-container">
				<div class="col-xs-12 breadcrumbs"></div>
			</div>
			<?php
			}
			?>			
			
			<div class="row notify">
				<div class="col-xs-10 col-md-12 notify-message"></div>
				<span class="col-xs-2 col-md-12 btn-refresh glyphicon glyphicon-refresh"></span>
			</div>	
			<?php include_once('templates/notifications.php'); ?>
			