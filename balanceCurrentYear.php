<?php
	session_start();
		
	$_SESSION['firstDate'] = date("Y-m-d", strtotime("first day of January"));
	$_SESSION['lastDate'] = date("Y-m-d", strtotime("last day of December"));

	$_SESSION['balanceToShow'] = true;
	header('Location: balance.php');
	exit();
?>
