<?php
	session_start();
	
	if (!$_SESSION['loggedUserId']){
		header('Location: index.php');
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
		
		<nav class="navbar navbar-dark navbar-expand-md navigation pb-0">
			<header>
				<a class="navbar-brand mr-5" href="main.php">
					<img src="img/piggy.png" height="60" alt="logo"><p class="align-middle"><span class="yellow">e</span>-świnka</p>
				</a>
			</header>
			
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".menu" aria-expanded="false" aria-label="Przełącznik nawigacji">
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<div class="collapse navbar-collapse menu">
				<div class="navbar-nav ml-auto">
					<span class="nav-item my-auto mr-3 loogedUser">Zalogowany użytkownik: 
						<span class="font-weight-bold yellow"><?php echo $_SESSION['LoggedUserName']; ?>
						</span>
					</span>
					
					<a class="nav-item nav-link" href="logout.php">
						<i class="icon-logout"></i> Wyloguj
					</a>
				</div>
			</div>
		</nav>
		
		<nav class="navbar navbar-dark navbar-expand-md navigation2 py-0 py-md-2">	
			<div class="collapse navbar-collapse menu">
				<div class="navbar-nav mx-auto">
					<a class="nav-item nav-link mx-3" href="income.php">
						<i class="icon-up-big"></i> Dodaj przychód
					</a>
					<a class="nav-item nav-link mx-3" href="expense.php">
						<i class="icon-down-big"></i> Dodaj wydatek
					</a>
					<a class="nav-item nav-link mx-3" href="balance.php">
						<i class="icon-chart-bar"></i> Bilans
					</a>
					<a class="nav-item nav-link mx-3" href="#">
						<i class="icon-cog"></i> Ustawienia
					</a>
				</div>
			</div>
		</nav>
		
		<main>
			<div class="container mt-5">
				<article>
					<header>
						<h1 class="text-center">Witaj <?php echo $_SESSION['LoggedUserName']; ?></h1>
					</header>
					
					<div class="row mt-5">
						<div class="col-md-4 mb-4 my-auto">
							<img src="img/piggy3.jpg" class="img-fluid" alt="piggy">
						</div>
					
						<div class="col-md-8">
							<p class="text-center">Jak tam Twoje finanse?</p>
							
							<p class="text-center">Wybierz interesującą Cię opcję z menu powyżej</p>
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