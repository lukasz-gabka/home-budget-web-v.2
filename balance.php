<?php
	session_start();
	
	if (!$_SESSION['loggedUserId']){
		header('Location: index.php');
		exit();
	}
		
	if (isset($_POST['submit1']) || isset($_POST['submit2'])){
		require_once "connect.php";
		try {
			$connection = new PDO('mysql:host='.$databaseHost.';dbname='.$databaseName.';charset=utf8', $databaseLogin, $databasePassword, [
				PDO::ATTR_EMULATE_PREPARES => false,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			]);
			
			
			
			if (isset($_POST['submit1'])){
				switch ($_POST['range']){
					case "Bieżący miesiąc": {						
						$firstDate = date("Y-m-d", strtotime("first day of this month"));
						$lastDate = date("Y-m-d", strtotime("last day of this month"));
					}
					break;
					case "Poprzedni miesiąc": {
						$firstDate = date("Y-m-d", strtotime("first day of previous month"));
						$lastDate = date("Y-m-d", strtotime("last day of previous month"));
					}
					break;
					case "Bieżący rok": {
						$firstDate = date("Y-m-d", strtotime("first day of January"));
						$lastDate = date("Y-m-d", strtotime("last day of December"));
					}
					break;
					default:
						$_SESSION['balanceError'] = "Wybierz zakres bilansu";
						header('Location: balance.php');
						exit();
				} 
			 } 
			else if (isset($_POST['submit2'])){
				$firstDate = $_POST['firstDate'];
				$lastDate = date($_POST['lastDate']);
				
				if ($lastDate < $firstDate){
					$_SESSION['balanceError'] = "Data końcowa nie może być wcześniejsza niż data początkowa";
					header('Location: balance.php');
					exit();
				}
			 }
			 
			 $statementIncomes = $connection->query("SELECT amount, date, category, comment FROM incomes WHERE date BETWEEN '$firstDate' AND '$lastDate' AND userId = ".$_SESSION['loggedUserId']." ORDER BY date ASC");
			 $statementExpenses = $connection->query("SELECT amount, date, category, comment FROM expenses WHERE date BETWEEN '$firstDate' AND '$lastDate' AND userId = ".$_SESSION['loggedUserId']." ORDER BY date ASC");
			 $statementIncomeCategories = $connection->query("SELECT ROUND(SUM(amount), 2), category FROM incomes WHERE userId = ".$_SESSION['loggedUserId']." AND date BETWEEN '$firstDate' AND '$lastDate' GROUP BY category ORDER BY category ASC");
			 $statementExpenseCategories = $connection->query("SELECT ROUND(SUM(amount), 2), category FROM expenses WHERE userId = ".$_SESSION['loggedUserId']." AND date BETWEEN '$firstDate' AND '$lastDate' GROUP BY category ORDER BY category ASC");
						
			$_SESSION['incomes'] = $statementIncomes->fetchAll(PDO::FETCH_ASSOC);
			$_SESSION['expenses'] = $statementExpenses->fetchAll(PDO::FETCH_ASSOC);
			$_SESSION['icomeCategories'] = $statementIncomeCategories->fetchAll(PDO::FETCH_ASSOC);
			$_SESSION['expenseCategories'] = $statementExpenseCategories->fetchAll(PDO::FETCH_ASSOC);
			$_SESSION['balanceToShow'] = true;
			$_SESSION['firstDate'] = date("d-m-Y", strtotime($firstDate));
			$_SESSION['lastDate'] = date("d-m-Y", strtotime($lastDate));
			
			header('Location: balanceResult.php');
			exit();
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
		<title>e-świnka - bilans</title>
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
					<a class="nav-item nav-link mx-3 active disabled" href="balance.php">
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
						<h1 class="text-center">Bilans przychodów i wydatków</h1>
					</header>
					
					<div class="container">
						<form method="post">
							<div class="row mt-5">
								<div class="col-12">
									<div class="input-group justify-content-center">
										<div class="input-group-prepend">
											<label class="input-group-text inputsPrepend" for="paymentOptions">
												<i class="icon-calendar"></i>
											</label>
										</div>
										
										<select class="custom-select inputs" id="paymentOptions" name="range" required>
											<option selected disabled value="">Wybierz zakres bilansu</option>
											<option>Bieżący miesiąc</option>
											<option>Poprzedni miesiąc</option>
											<option>Bieżący rok</option>
											<option data-toggle="modal" data-target="#customBalance">Niestandardowy</option>
										</select>
									</div>
									
									<?php
										if (isset($_SESSION['balanceError'])){
											echo '<div class="input-group justify-content-center text-danger small">'.$_SESSION['balanceError'].'</div>';
											unset($_SESSION['balanceError']);
										}
									?>
									
									<div class="d-flex justify-content-center my-5 d-inline-block">	
										<input type="submit" class="btn form-control inputs submitButtons" value="Wyświetl bilans" name="submit1">
									</div>
								</div>
							</div>
						</form>
						
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
												
												<div class="input-group justify-content-center mb-5">
													<div class="input-group-prepend">
														<span class="input-group-text inputsPrepend">
															<i class="icon-calendar"></i>
														</span>
													</div>
													
													<input placeholder="Data końcowa(kliknij, aby ustawić)" class="textbox-n form-control inputs" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" id="date2" name="lastDate"required>
												</div>
										</div>
										
										<div class="modal-footer">
											<input type="submit" class="btn form-control inputs submitButtons" value="Wyświetl bilans" name="submit2">
										
											<input type="reset" class="btn form-control inputs submitButtons bg-danger" style="border:none;" value="Wyczyść">
										</div>
									</form>
								</div>
							</div>
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
