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
			$category = $_POST['category'];
			if (isset($_POST['comment'])) $comment = $_POST['comment'];
			
			$userQuery = $connection->prepare('INSERT INTO incomes VALUES (NULL, :amount, :date, :category, :comment, :userId)');
			$userQuery->bindValue(':amount', $amount, PDO::PARAM_STR);
			$userQuery->bindValue(':date', $date, PDO::PARAM_STR);
			$userQuery->bindValue(':category', $category, PDO::PARAM_STR);
			$userQuery->bindValue(':comment', $comment, PDO::PARAM_STR);
			$userQuery->bindValue(':userId', $_SESSION['loggedUserId'], PDO::PARAM_STR);
			$userQuery->execute();
			
			$_SESSION['operation'] = "przychód";
			header('Location: operationAdd.php');
		}
		catch (PDOException $error){
			//echo $error->getMessage();
			$_SESSION['databaseError'] = true;
			header('Location: error.php');
			exit();
		}
	}
	
	if (isset($_POST['submit'])){
		$_SESSION['firstDate'] = $_POST['firstDate'];
		$_SESSION['lastDate'] = $_POST['lastDate'];
		
		if ($_SESSION['lastDate'] < $_SESSION['firstDate']){
			$_SESSION['balanceError'] = "Data końcowa nie może być wcześniejsza niż data początkowa";
			$_SESSION['showModal'] = true;
			unset($_SESSION['firstDate']);
			unset($_SESSION['lastDate']);
			header('Location: income.php');
			exit();
		}
		
		$_SESSION['balanceToShow'] = true;		
		header('Location: balance.php');
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
		<link rel="stylesheet" href="./style.css"/>
		<link rel="stylesheet" href="css/fontello.css" type="text/css"/>
		
		<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700%7CTitan+One&display=swap&subset=latin-ext" rel="stylesheet">
				
		<title>e-świnka - dodaj przychód</title>
	</head>
	
	<body>
		<nav class="navbar navbar-dark navbar-expand-md navigation pb-0">
			<header>
				<a class="navbar-brand mr-1" href="main.php">
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
				<ul class="navbar-nav mx-auto">
					<li class="nav-item mx-3">
						<a class="nav-link active disabled" href="income.php">
							<i class="icon-up-big"></i> Dodaj przychód
						</a>
					</li>
					
					<li class="nav-item mx-3">
						<a class="nav-link" href="expense.php">
							<i class="icon-down-big"></i> Dodaj wydatek
						</a>
					</li>
					
					<li class="nav-item mx-3 dropdown">
						<a class="nav-link dropdown-toggle" href="balance.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="icon-chart-bar"></i> Bilans
						</a>
						
						<div class="dropdown-menu navigation2" aria-labelledby="navbarDropdown">
						  <a class="dropdown-item nav-link" href="balanceCurrentMonth.php">Bieżący miesiąc</a>
						  <a class="dropdown-item nav-link" href="balancePreviousMonth.php">Poprzedni miesiąc</a>
						  <a class="dropdown-item nav-link" href="balanceCurrentYear.php">Bieżący rok</a>
						  <a class="dropdown-item nav-link" href="#" data-toggle="modal" data-target="#customBalance">Niestandardowy...</a>
						</div>
					</li>
					
					<li class="nav-item mx-3">
						<a class="nav-link" href="#">
							<i class="icon-cog"></i> Ustawienia
						</a>
					</li>
				</ul>
			</div>
		</nav>
		
		<main>
			<div class="container mt-5">
				<article>
					<header>
						<h1 class="text-center">Dodaj przychód</h1>
					</header>
					
					<div class="container">
						<form method="post" enctype="application/x-www-form-urlencoded">
							<div class="row mt-5">
								<div class="col-12">
									<div class="input-group justify-content-center">
										<div class="input-group-prepend">
											<span class="input-group-text inputsPrepend">
												<i class="icon-money"></i>
											</span>
										</div>
										
										<input type="number" class="form-control inputs" placeholder="Kwota przychodu" step="0.01" min="0.01" name="amount" required>
									</div>
									
									<div class="input-group justify-content-center mt-5">
										<div class="input-group-prepend">
											<span class="input-group-text inputsPrepend">
												<i class="icon-calendar"></i>
											</span>
										</div>
										
										<input placeholder="Data(kliknij, aby ustawić)" class="textbox-n form-control inputs" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" id="date" name="date" required>
									</div>
									
									<div class="input-group justify-content-center mt-5">
										<div class="input-group-prepend">
											<label class="input-group-text inputsPrepend" for="paymentOptions">
												<i class="icon-tag"></i>
											</label>
										</div>
										
										<select class="custom-select inputs" id="paymentOptions" name="category" required>
											<option selected disabled value="">Wybierz kategorię przychodu</option>
											<?php
												foreach ($_SESSION['LoggedUserIncomeCategories'] as $value) {
													echo "<option>{$value['name']}</option>";
												}
											?>
										</select>
									</div>
									
									<div class="input-group justify-content-center mt-5">
										<div class="input-group-prepend">
											<span class="input-group-text inputsPrepend">
												<i class="icon-comment"></i>
											</span>
										</div>
										
										<textarea class="form-control inputs" maxlength="40" placeholder="Komentarz(opcjonalnie)" name="comment"></textarea>
									</div>
									
									<div class="d-flex justify-content-center my-5 d-inline-block">	
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
		
		<div class="modal" id="customBalance" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header navigation">
						<h2>Ustaw zakres bilansu</h2>
						
						<button type="button" class="close" data-dismiss="modal" aria-label="Zamknij">
							<span aria-hidden="true">x</span>
						</button>
					</div>
					
					<form method="post">
						<div class="modal-body">
							<div class="input-group justify-content-center my-5">
								<div class="input-group-prepend">
									<span class="input-group-text inputsPrepend">
										<i class="icon-calendar"></i>
									</span>
								</div>
								
								<input placeholder="Data początkowa(kliknij, aby ustawić)" class="textbox-n form-control inputs" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" id="date1" name="firstDate" required>
							</div>
							
							<div class="input-group justify-content-center">
								<div class="input-group-prepend">
									<span class="input-group-text inputsPrepend">
										<i class="icon-calendar"></i>
									</span>
								</div>
								
								<input placeholder="Data końcowa(kliknij, aby ustawić)" class="textbox-n form-control inputs" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" id="date2" name="lastDate"required>
							</div>
							
							<?php
								if (isset($_SESSION['balanceError'])){
									echo '<div class="input-group justify-content-center text-danger small">'.$_SESSION['balanceError'].'</div>';
									unset($_SESSION['balanceError']);
								}
							?>
						</div>
						
						<div class="modal-footer mt-5">
							<input type="submit" class="btn form-control inputs submitButtons" value="Wyświetl bilans" name="submit">
						
							<input type="reset" class="btn form-control inputs submitButtons bg-danger" style="border:none;" value="Wyczyść">
						</div>
					</form>
				</div>
			</div>
		</div>
		
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
		
		<?php
			if (isset($_SESSION['showModal']))
			{
				echo '<script type="text/javascript">
						$(document).ready(function(){
						$("#customBalance").modal("show");
						});
						</script>';
				unset($_SESSION['showModal']);
			}
		?>
	</body>
</html>
