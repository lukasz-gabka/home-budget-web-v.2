<?php
	session_start();
	if ((isset($_SESSION['loggedUserId']))){
		header('Location: main.php');
		exit();
	}
?>

<!DOCTYPE html>
<html lang="pl">
	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		
		<meta name="description" content="Aplikacja wspomagająca zarządzanie bużetem domowym."/>
		<meta name="keywords" content="oszczędzanie, pieniąze, bużet domowy, budżet, dom, wydatki, przychody, bilans, saldo"/>
		
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<link rel="stylesheet" href="style.css"/>
		<link rel="stylesheet" href="css/fontello.css" type="text/css"/>
		
		<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700%7CTitan+One&display=swap&subset=latin-ext" rel="stylesheet">		
		<title>e-świnka</title>
	</head>
	
	<body>
		<nav class="navbar navbar-dark navbar-expand-sm navigation">
			<header>
				<a class="navbar-brand mr-1" href="index.php">
					<img src="img/piggy.png" height="60" alt="logo"><p class="align-middle"><span class="yellow">e</span>-świnka</p>
				</a>
			</header>
			
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu" aria-expanded="false" aria-label="Przełącznik nawigacji">
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<div class="collapse navbar-collapse" id="menu">
				<div class="navbar-nav ml-auto">
					<a class="nav-item nav-link" href="login.php">
						<i class="icon-login"></i> Logowanie
					</a>
					
					<a class="nav-item nav-link" href="register.php">
						<i class="icon-user-plus"></i> Rejestracja
					</a>
				</div>
			</div>
		</nav>
		
		<main>
			<div class="container mt-5">
				<article>
					<header>
						<h1 class="text-center">Witaj na <span class="font-weight-bold"><span class="yellow">e</span>-świnka</span>!</h1>
					</header>
					
					<div class="row mt-5">
						<div class="col-md-4 mb-4 my-auto">
							<img src="img/piggy2.jpg" class="img-fluid" alt="piggy">
						</div>
					
						<div class="col-md-8">
							<p class="lead text-center"><span class="font-weight-bold"><span class="yellow">e</span>-świnka</span> to aplikacja do zarządzania budżetem domowym.</p>
						
							<p class="text-justify">Dodawaj na bieżąco wszystkie swoje operacje pieniężne, a dzięki opcji ich wizualizacji szybko zorientujesz się, gdzie należy obnizyć swoje wydatki. Zdziwisz się, jak taka z pozoru prosta aplikacja pomoże Ci zaoszczędzić Twoje pieniądze.</p>
							
							<p class="text-justify"><span class="font-weight-bold"><span class="yellow">e</span>-świnka</span> to Twoja prywatna elektroniczna skarbonka do oszczędzania pieniędzy i zarządzania budżetem domowym.</p>
							
							<p class="lead text-center">Zarejestruj się i zacznij oszczędzać już dziś!</p>
						</div>
						
					</div>
				</article>
			</div>
		</main>
		
		<footer class="footer navigation">
			&copy; <span class="font-weight-bold"><span class="yellow">e</span>-świnka</span> 2020
		</footer>
	
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	</body>
</html>
