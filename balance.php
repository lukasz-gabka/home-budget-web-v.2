<?php
	session_start();
	
	if (!isset($_SESSION['firstDate'])){
		header('Location: index.php');
		exit();
	}
	
	unset($_SESSION['balanceToShow']);
	
	require_once "connect.php";
	try {
		$connection = new PDO('mysql:host='.$databaseHost.';dbname='.$databaseName.';charset=utf8', $databaseLogin, $databasePassword, [
			PDO::ATTR_EMULATE_PREPARES => false,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		]);
		
		$incomes = $connection->query("SELECT amount, date, category, comment FROM incomes WHERE date BETWEEN '{$_SESSION['firstDate']}' AND '{$_SESSION['lastDate']}' AND userId = ".$_SESSION['loggedUserId']." ORDER BY date ASC");
		$expenses = $connection->query("SELECT amount, date, category, comment FROM expenses WHERE date BETWEEN '{$_SESSION['firstDate']}' AND '{$_SESSION['lastDate']}' AND userId = ".$_SESSION['loggedUserId']." ORDER BY date ASC");
		$incomeCategories = $connection->query("SELECT ROUND(SUM(amount), 2), category FROM incomes WHERE userId = ".$_SESSION['loggedUserId']." AND date BETWEEN '{$_SESSION['firstDate']}' AND '{$_SESSION['lastDate']}' GROUP BY category ORDER BY category ASC");
		$expenseCategories = $connection->query("SELECT ROUND(SUM(amount), 2), category FROM expenses WHERE userId = ".$_SESSION['loggedUserId']." AND date BETWEEN '{$_SESSION['firstDate']}' AND '{$_SESSION['lastDate']}' GROUP BY category ORDER BY category ASC");
					
		$incomes = $incomes->fetchAll(PDO::FETCH_ASSOC);
		$expenses = $expenses->fetchAll(PDO::FETCH_ASSOC);
		$incomeCategories = $incomeCategories->fetchAll(PDO::FETCH_ASSOC);
		$expenseCategories = $expenseCategories->fetchAll(PDO::FETCH_ASSOC);
		
		$firstDateReversed = date("d-m-Y", strtotime($_SESSION['firstDate']));
		$lastDateReversed = date("d-m-Y", strtotime($_SESSION['lastDate']));
	}
	catch (PDOException $error){
		//echo $error->getMessage();
		$_SESSION['databaseError'] = true;
		header('Location: error.php');
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
				<ul class="navbar-nav mx-auto">
					<li class="nav-item mx-3">
						<a class="nav-link" href="income.php">
							<i class="icon-up-big"></i> Dodaj przychód
						</a>
					</li>
					
					<li class="nav-item mx-3">
						<a class="nav-link" href="expense.php">
							<i class="icon-down-big"></i> Dodaj wydatek
						</a>
					</li>
					
					<li class="nav-item mx-3 dropdown">
						<a class="nav-link dropdown-toggle active" href="balance.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
			<div class="container-fluid mt-5">
				<article>
					<header>
						<h1 class="text-center">Bilans za okres: 
							<?php echo $firstDateReversed." - ".$lastDateReversed ?>
						</h1>
					</header>
					
					<div class="row mt-5 pb-0">
						<div class="col-lg-6">
							<h2 class="text-center">Przychody</h2>
							
							<div class="table-responsive">
								<table class="table table-sm table-bordered table-hover text-center small">
									<thead>
										<tr>
											<th scope="col">Kategoria</th>
											<th scope="col">Kwota</th>
											<th scope="col">Data</th>
											<th scope="col">Komentarz</th>
										</tr>
									</thead>
									
									<tbody>									
											<?php
												$incomeSum = 0;

												foreach ($incomes as $key => $value){
													echo'<tr><td>'.$value['category'].'</td>';
													echo '<td class="text-nowrap">'.number_format($value['amount'], 2, ',', ' ').' zł</td>';
													echo '<td>'.date('d-m-Y', strtotime($value['date'])).'</td>';
													echo '<td>'.$value['comment'].'</td>';
													echo '</tr>';
													$incomeSum += $value['amount'];
												}
											?>
									</tbody>
								</table>
							</div>
							
							<div class="row pb-5">
								<p class="h4 ml-auto mr-auto"><strong>Suma przychodów:</strong> <?php echo number_format($incomeSum, 2, ',', ' ') ?> zł</p>
							</div>
						</div>	
							
						<div class="col-lg-6">
							<h2 class="text-center">Wydatki</h2>
							
							<div class="table-responsive">
								<table class="table table-sm table-bordered text-center table-hover small">
									<tr>
										<thead>
											<th scope="col">Kategoria</th>
											<th scope="col">Kwota</th>
											<th scope="col">Data</th>
											<th scope="col">Komentarz</th>
										</thead>
									</tr>
									
									<tbody>
										<?php
											$expenseSum = 0;

											foreach ($expenses as $key => $value){
												echo'<tr><td>'.$value['category'].'</td>';
												echo '<td class="text-nowrap">'.number_format($value['amount'], 2, ',', ' ').' zł</td>';
												echo '<td>'.date('d-m-Y', strtotime($value['date'])).'</td>';
												echo '<td>'.$value['comment'].'</td>';
												echo '</tr>';
												$expenseSum += $value['amount'];
												
												$balance = $incomeSum - $expenseSum;
											}
										?>
									</tbody>
								</table>
							</div>
							
							<div class="row pb-5">
								<p class="h4 ml-auto mr-auto"><strong>Suma wydatków: </strong> <?php echo number_format($expenseSum, 2, ',', ' '); ?> zł</p>
							</div>
						</div>
					</div>
					
					<div class="row mb-3 pb-0">
						<p class="h3 mx-auto"><strong>Saldo:</strong> 
							<?php 
								$balance = $incomeSum - $expenseSum;
								$balance = number_format($balance, 2, ',', ' ');
								
								if ($balance >=0)
									echo '<span class="text-success">'.$balance.' zł</span>';
								else
									echo '<span class="text-danger">'.$balance.' zł</span>';
							?>
						</p>
					</div>
					
					<div class="row mb-5 pb-0">
						<?php
							if ($balance >=0)
								echo '<p class="mx-auto h4">Gratulacje! Świetnie zarządzasz finansami!</p>';
							else
								echo '<p class="mx-auto h4">Uważaj! Popadasz w długi!</p>';
						?>
					</div>
					
					<div class="row my-5">
						<div class="col-lg-6 mb-5">
							<h2 class="text-center">Przychody wg kategorii</h2>
							
							<div class="table-responsive">
								<table class="table table-sm table-bordered table-hover text-center small">
									<thead>
										<tr>
											<th scope="col">Kategoria</th>
											<th scope="col">Kwota</th>
										</tr>
									</thead>
									
									<tbody>									
										<?php
											foreach ($incomeCategories as $key => $value){
												echo'<tr><td>'.$value['category'].'</td>';
												echo '<td>'.number_format($value['ROUND(SUM(amount), 2)'], 2, ',', ' ').' zł</td>';
												echo '</tr>';
											}
										?>	
									</tbody>
								</table>
							</div>
						</div>
						
						<div class="col-lg-6">
							<h2 class="text-center">Wydatki wg kategorii</h2>
							
							<div class="table-responsive">
								<table class="table table-sm table-bordered table-hover text-center small">
									<thead>
										<tr>
											<th scope="col">Kategoria</th>
											<th scope="col">Kwota</th>
										</tr>
									</thead>
									
									<tbody>									
										<?php
											foreach ($expenseCategories as $key => $value){
												echo'<tr><td>'.$value['category'].'</td>';
												echo '<td>'.number_format($value['ROUND(SUM(amount), 2)'], 2, ',', ' ').' zł</td>';
												echo '</tr>';
											}
											
											unset ($_SESSION['firstDate']);
											unset ($_SESSION['lastDate']);
											unset ($_SESSION['balanceToShow']);
										?>	
									</tbody>
								</table>
							</div>
						</div>
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
					
					<form method="post" action="balanceCustom.php">
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
