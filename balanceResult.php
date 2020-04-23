<?php
	session_start();
	
	if (!isset($_SESSION['balanceToShow'])){
		header('Location: index.php');
		exit();
	}
	
	unset($_SESSION['balanceToShow']);
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
			<div class="container-fluid mt-5">
				<article>
					<header>
						<h1 class="text-center">Bilans za okres: 
							<?php echo $_SESSION['firstDate']." - ".$_SESSION['lastDate'] ?>
						</h1>
					</header>
					
					<div class="row mt-5 pb-0">
						<div class="col-lg-6">
							<h2 class="text-center">Przychody</h2>
							
							<div class="table-responsive">
								<table class="table table-sm table-bordered table-hover text-center">
									<thead style="background-color: #9FFF87">
										<tr>
											<th scope="col">L.p.</th>
											<th scope="col">Kategoria</th>
											<th scope="col">Kwota</th>
											<th scope="col">Data</th>
											<th scope="col">Komentarz</th>
										</tr>
									</thead>
									
									<tbody>									
											<?php
												$incomeSum = 0;
												$cardinalNumber = 1;

												foreach ($_SESSION['incomes'] as $key => $value){
													echo '<tr><th scope="row">'.$cardinalNumber.'.</th>';
													echo'<td>'.$value['category'].'</td>';
													echo '<td>'.$value['amount'].' zł</td>';
													echo '<td>'.date('d-m-Y', strtotime($value['date'])).'</td>';
													echo '<td>'.$value['comment'].'</td>';
													echo '</tr>';
													$cardinalNumber++;
													$incomeSum += $value['amount'];
												}
											?>
									</tbody>
								</table>
							</div>
							
							<div class="row pb-5">
								<p class="h4 ml-auto mr-auto"><strong>Suma przychodów:</strong> <?php echo $incomeSum ?> zł</p>
							</div>
							
							<h2 class="text-center">Przychody wg kategorii</h2>
							
							<div class="table-responsive">
								<table class="table table-sm table-bordered table-hover text-center">
									<thead style="background-color: #9FFF87">
										<tr>
											<th scope="col">L.p.</th>
											<th scope="col">Kategoria</th>
											<th scope="col">Kwota</th>
										</tr>
									</thead>
									
									<tbody>									
											
									</tbody>
								</table>
							</div>
						</div>
						
						<div class="col-lg-6">
							<h2 class="text-center">Wydatki</h2>
							
							<div class="table-responsive">
								<table class="table table-sm table-bordered text-center table-hover">
									<tr>
										<thead style="background-color: #9FFF87">
											<th scope="col">L.p.</th>
											<th scope="col">Kategoria</th>
											<th scope="col">Kwota</th>
											<th scope="col">Data</th>
											<th scope="col">Komentarz</th>
										</thead>
									</tr>
									
									<tbody>
										<?php
											$expenseSum = 0;
											$cardinalNumber = 1;

											foreach ($_SESSION['expenses'] as $key => $value){
												echo '<tr><th scope="row">'.$cardinalNumber.'.</th>';
												echo'<td>'.$value['category'].'</td>';
												echo '<td>'.$value['amount'].' zł</td>';
												echo '<td>'.date('d-m-Y', strtotime($value['date'])).'</td>';
												echo '<td>'.$value['comment'].'</td>';
												echo '</tr>';
												$cardinalNumber++;
												$expenseSum += $value['amount'];
												
												$balance = $incomeSum - $expenseSum;
											}
										?>
									</tbody>
								</table>
							</div>
							
							<div class="row pb-5">
								<p class="h4 ml-auto mr-auto"><strong>Suma wydatków: </strong> <?php echo $expenseSum; ?> zł</p>
							</div>
							
							<h2 class="text-center">Wydatki wg kategorii</h2>
							
							<div class="table-responsive">
								<table class="table table-sm table-bordered table-hover text-center">
									<thead style="background-color: #9FFF87">
										<tr>
											<th scope="col">L.p.</th>
											<th scope="col">Kategoria</th>
											<th scope="col">Kwota</th>
										</tr>
									</thead>
									
									<tbody>									
											
									</tbody>
								</table>
							</div>
						</div>
					</div>
					
					<div class="row mb-3 pb-0">
						<p class="h3 mx-auto"><strong>Saldo:</strong> 
							<?php 
								$balance = $incomeSum - $expenseSum;
								
								if ($balance >=0)
									echo '<span class="text-success">'.$balance.' zł</span>';
								else
									echo '<span class="text-danger">'.$balance.' zł</span>';
							?>
						</p>
					</div>
					
					<div class="row mb-5">
						<?php
							if ($balance >=0)
								echo '<p class="mx-auto h4">Gratulacje! Świetnie zarządzasz finansami!</p>';
							else
								echo '<p class="mx-auto h4">Uważaj! Popadasz w długi!</p>';
						?>
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
