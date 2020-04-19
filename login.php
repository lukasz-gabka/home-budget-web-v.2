<?php
	session_start();
	if (isset($_POST['email']) && isset($_POST['password'])){
		//connect with host
		require_once "connect.php";
		try {
			$connection = new PDO('mysql:host='.$databaseHost.';dbname='.$databaseName.';charset=utf8', $databaseLogin, $databasePassword, [
				PDO::ATTR_EMULATE_PREPARES => false,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			]);
			
			$email = $_POST['email'];
			$password = $_POST['password'];
			
			$userQuery = $connection->prepare('SELECT id, name, password FROM users WHERE email= :email');
			$userQuery->bindValue(':email', $email, PDO::PARAM_STR);
			$userQuery->execute();
			
			if ($userQuery->rowCount()){
				$user = $userQuery->fetch();
				
				if (password_verify($password, $user['password'])) {
					$_SESSION['loggedUserId'] = $user['id'];
					$_SESSION['LoggedUserName'] = $user['name'];
					$_SESSION['LoggedUserEmail'] = $email;
					header('Location: main.php');
				}
				else {
					$_SESSION['passwordError'] = 'Podane hasło jest nieprawidłowe';
					$_SESSION['emailMemorized'] = $email;
				}
			}
			else{
				$_SESSION['emailError'] = 'Użytkownik o takim adresie e-mail nie istnieje';
				$_SESSION['emailMemorized'] = $email;
			}
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
		<title>e-świnka - logowanie</title>
	</head>
	
	<body>
		
		<nav class="navbar navbar-dark navbar-expand-sm navigation">
			<header>
				<a class="navbar-brand mr-5" href="index.php">
					<img src="img/piggy.png" height="60" alt="logo"><p class="align-middle"><span class="yellow">e</span>-świnka</p>
				</a>
			</header>
			
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu" aria-expanded="false" aria-label="Przełącznik nawigacji">
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<div class="collapse navbar-collapse" id="menu">
				<div class="navbar-nav ml-auto">
					<a class="nav-item nav-link active disabled" href="login.php">
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
						<h1 class="text-center">Zaloguj się</h1>
					</header>
					
					<div class="container">
						<form method="post">
							<div class="row mt-5">
								<div class="col-12">
									<div class="input-group justify-content-center mb-5">
										<div class="input-group-prepend">
											<span class="input-group-text inputsPrepend">
												<i class="icon-mail-alt"></i>
											</span>
										</div>
										
										<input type="email" class="form-control inputs" placeholder="e-mail" name="email" value="<?php
											if (isset($_SESSION['emailMemorized'])){
												echo $_SESSION['emailMemorized'];
												unset($_SESSION['emailMemorized']);
											}
										?>" required>
										
										<?php
											if (isset($_SESSION['emailError'])){
												echo '<div class="input-group justify-content-center text-danger small">'.$_SESSION['emailError'].'</div>';
												unset($_SESSION['emailError']);
											}
										?>
									</div>
									
									<div class="input-group justify-content-center mb-5">
										<div class="input-group-prepend">
											<span class="input-group-text inputsPrepend">
												<i class="icon-lock"></i>
											</span>
										</div>
										
										<input type="password" class="form-control inputs" placeholder="Hasło" name="password" required>
										
										<?php
											if (isset($_SESSION['passwordError'])){
												echo '<div class="input-group justify-content-center text-danger small">'.$_SESSION['passwordError'].'</div>';
												unset($_SESSION['passwordError']);
											}
										?>
									</div>
									
									<div class="input-group justify-content-center mb-5">	
										<input type="submit" class="btn form-control inputs submitButtons" value="Zaloguj się">
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
