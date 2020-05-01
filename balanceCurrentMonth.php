<?php
	session_start();
		
	$_SESSION['firstDate'] = date("Y-m-d", strtotime("first day of this month"));
	$_SESSION['lastDate'] = date("Y-m-d", strtotime("last day of this month"));

	$_SESSION['balanceToShow'] = true;
	header('Location: balance.php');
	exit();
?>
