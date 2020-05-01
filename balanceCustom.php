<?php
	session_start();
		$_SESSION['firstDate'] = $_POST['firstDate'];
		$_SESSION['lastDate'] = $_POST['lastDate'];
		
		if ($_SESSION['lastDate'] < $_SESSION['firstDate']){
			$_SESSION['balanceError'] = "Data końcowa nie może być wcześniejsza niż data początkowa";
			$_SESSION['showModal'] = true;
			unset($_SESSION['firstDate']);
			unset($_SESSION['lastDate']);
			header('Location: main.php');
			exit();
		}
		
		$_SESSION['balanceToShow'] = true;
		header('Location: balance.php');
		exit();
?>
