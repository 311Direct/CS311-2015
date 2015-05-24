<?php
	$urlPath = $_SERVER['PHP_SELF'];
	if (strpos($urlPath, 'login') === false && strpos($urlPath, 'desktop') === false) {
		session_start();
		if (!isset($_SESSION['username'])) header('Location: login.php');	
	}
?>