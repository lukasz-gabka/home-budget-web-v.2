<?php
	session_start();
	
	if (!$_SESSION['loggedUserId']){
		header('Location: index.php');
		exit();
	}
	
	if (isset($_POST['amount'])){
		//connect with host
		require_once "connect.php";
		try {
			$connection = new PDO('mysql:host='.$databaseHost.';dbname='.$databaseName.';charset=utf8', $databaseLogin, $databasePassword, [
				PDO::ATTR_EMULATE_PREPARES => false,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			]);
			
			$amount = $_POST['amount'];
			$date = $_POST['date'];
			$payment = $_POST['payment'];
			$category = $_POST['category'];
			if (isset($_POST['comment'])) $comment = $_POST['comment'];
			
			$userQuery = $connection->prepare('INSERT INTO expenses VALUES (NULL, :amount, :date, :payment, :category, :comment, :userId)');
			$userQuery->bindValue(':amount', $amount, PDO::PARAM_STR);
			$userQuery->bindValue(':date', $date, PDO::PARAM_STR);
			$userQuery->bindValue(':payment', $payment, PDO::PARAM_STR);
			$userQuery->bindValue(':category', $category, PDO::PARAM_STR);
			$userQuery->bindValue(':comment', $comment, PDO::PARAM_STR);
			$userQuery->bindValue(':userId', $_SESSION['loggedUserId'], PDO::PARAM_STR);
			$userQuery->execute();
			
			$_SESSION['operation'] = "wydatek";
			header('Location: operationAdd.php');
		}
		catch (PDOException $error){
			//echo $error->getMessage();
			$_SESSION['databaseError'] = true;
			header('Location: error.php');
			exit();
		}
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
		<title>e-świnka - dodaj wydatek</title>
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
					<a class="nav-item nav-link mx-3 active disabled" href="expense.php">
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
						<h1 class="text-center">Dodaj wydatek</h1>
					</header>
					
					<div class="container">
						<form method="post" enctype="application/x-www-form-urlencoded">
							<div class="row mt-5">
								<div class="col-12">
									<div class="input-group justify-content-center mb-5">
										<div class="input-group-prepend">
											<span class="input-group-text inputsPrepend">
												<i class="icon-money"></i>
											</span>
										</div>
										
										<input type="number" class="form-control inputs" placeholder="Kwota wydatku" step="0.01" min="0.01" name="amount" required>
									</div>
									
									<div class="input-group justify-content-center mb-5">
										<div class="input-group-prepend">
											<span class="input-group-text inputsPrepend">
												<i class="icon-calendar"></i>
											</span>
										</div>
										
										<input placeholder="Data(kliknij, aby ustawić)" class="textbox-n form-control inputs" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" id="date" name="date" required>
									</div>
									
									<div class="input-group justify-content-center mb-5">
										<div class="input-group-prepend">
											<label class="input-group-text inputsPrepend" for="paymentOptions">
												<i class="icon-wallet"></i>
											</label>
										</div>
										
										<select class="custom-select inputs" id="paymentOptions" name="payment" required>
											<option selected disabled value="">Wybierz sposób płatności</option>
											<option>Gotówka</option>
											<option>Przelew</option>
											<option>Karta kredytowa</option>
											<option>Karta debetowa</option>
										</select>
									</div>
								
									<div class="input-group justify-content-center mb-5">
										<div class="input-group-prepend">
											<label class="input-group-text inputsPrepend" for="category">
												<i class="icon-tag"></i>
											</label>
										</div>
										
										<select class="custom-select inputs" id="category" name="category" required>
											<option selected disabled value="">Wybierz kategorię wydatku</option>
											<option>Jedzenie</option>
											<option>Mieszkanie</option>
											<option>Transport</option>
											<option>Telekomunikacja</option>
											<option>Opieka zdrowotna</option>
											<option>Ubranie</option>
											<option>Higiena</option>
											<option>Dzieci</option>
											<option>Rozrywka</option>
											<option>Wycieczka</option>
											<option>Szkolenia</option>
											<option>Książki</option>
											<option>Oszczędności</option>
											<option>Na złotą jesień, czyli emeryturę</option>
											<option>Spłata długów</option>
											<option>Darowizna</option>
											<option>Inne wydatki</option>
										</select>
									</div>
									
									<div class="input-group justify-content-center mb-5">
										<div class="input-group-prepend">
											<span class="input-group-text inputsPrepend">
												<i class="icon-comment"></i>
											</span>
										</div>
										
										<textarea class="form-control inputs" maxlength="40" placeholder="Komentarz(opcjonalnie)" name="comment"></textarea>
									</div>
									
									<div class="d-flex justify-content-center mb-5 d-inline-block">	
										<input type="submit" class="btn form-control inputs submitButtons mr-5" value="Dodaj">
										
										<input type="reset" class="btn form-control inputs submitButtons bg-danger ml-5" style="border:none;" value="Wyczyść">
									</div>
								</div>
							</div>
						</form>
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