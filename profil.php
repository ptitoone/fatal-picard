<?php 
session_start();
require 'database.php';

function inputCheck($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function fetchProfile()
{
    $db = Database::connect();
    $statement = $db->prepare('SELECT * FROM members WHERE pseudo = ?');
    $statement->execute(array($_SESSION['pseudo']));
    $result = $statement->fetch();
    Database::disconect();
    $profil = array('pseudo' => '', 'password' => '', 'email' => '', 'registered_date' => '');
    $profil['pseudo'] = $result['pseudo'];
    $profil['password'] = $result['password'];
    $profil['email'] = $result['email'];
	$profil['registered_date'] = $result['registered_date'];
    return $profil;
}

function changePassword($newPassword, $pseudo)
{
	
	$hashedPass = password_hash($newPassword, PASSWORD_DEFAULT);
	$db = Database::connect();
	$statement = $db->prepare('UPDATE members SET password = ? WHERE pseudo  = ?');
	$statement->execute(array($hashedPass, $pseudo));
	Database::disconect();
	header('Location: pwcs.php');
}

if(!isset($_SESSION['loggedin']))
{
    header('Location: index.php');
}
$profil = fetchProfile();
$emPassword = $emNewPassword = $wPassword = FALSE; 

if(isset($_POST['pass']) && isset($_POST['newpass']))
{
	echo 'here';
	
	if(empty($_POST['pass']))
	{
		$emPassword = TRUE;
	}
	
	if(empty($_POST['newpass']))
	{
		$emNewPassword = TRUE;
	}
	
	if(!empty($_POST['pass']) && !empty($_POST['newpass']) && strlen($_POST['newpass']) < 80 && strlen($_POST['newpass']) > 5 && strlen($_POST['pass']) < 80 && strlen($_POST['pass']) > 5)
	{
		
		$password = inputCheck($_POST['pass']);
		$newPassword = inputCheck($_POST['newpass']);
			
		if (password_verify($password, $profil['password'])) {
			changePassword($newPassword, $profil['pseudo']);
		} 	
		else {
			$wPassword = TRUE;
		}
		

	}
	
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="UTF-8">
	<title>Fatal-Picard</title>
	<script src="https://kit.fontawesome.com/578986040a.js" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="stylesheets/screen.css">
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,700;1,300;1,400&display=swap" rel="stylesheet">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		$(function() {
			$('[data-toggle="tooltip"]').tooltip('show')
		})

	</script>
</head>

<body>
	<div class="container-fluid wrapper-in p-0">

		<?php include("elements/menu.php") ?>

		<div class="container">
			<div class="row">
				<div class="col col-element module" id="home-welcome">
					<h1>Bienvenu à toi <span class="pseudo"><?php echo $_SESSION['pseudo']; ?></span>.</h1>
					<h3>Profil</h3>
					<p>Tu peux modifier ton mot de passe ici et avoir une vue d'ensemble de tes informations.</p>
					<div>
						<h5>Email: <?php echo $profil['email']; ?></h5>
						<h5 style="padding-top:15px;">Inscrit le : <?php echo $profil['registered_date']; ?></h5>
						<form id="form-pass" action="profil.php" method="post">
							<div class="input-group">
								<label for="pass">
									<h5>Ancien mot de passe : </h5>
								</label>

								<input type="password" name="pass" placeholder="" <?php 
									   if($emPassword == TRUE && $_SESSION['confirm'] = TRUE)
									   {
										   echo 'data-toggle="tooltip" data-placement="bottom" data-trigger="manual" title="Champ vide" data-toggle="tooltip" data-placement="bottom" data-trigger="manual"';
									   }
								   
								   		if($wPassword == TRUE && $_SESSION['confirm'] = TRUE)
									   {
										   echo 'data-toggle="tooltip" data-placement="bottom" data-trigger="manual" title="Mot de passe incorrect" data-toggle="tooltip" data-placement="bottom" data-trigger="manual"'; 
										}
								   ?>>
							</div>

							<div class="input-group">
								<label for="newpass">
									<h5>Nouveau mot de passe : </h5>
								</label>
								<input type="password" name="newpass" placeholder="" <?php 
									   if($emNewPassword == TRUE && $_SESSION['confirm'] = TRUE)
									   {
										   echo 'data-toggle="tooltip" data-placement="bottom" data-trigger="manual" title="Champ vide" data-toggle="tooltip" data-placement="bottom" data-trigger="manual"'; 
										}
								   	?>>
							</div>

							<button class="button-basic" type="submit">Modifier</button>
						</form>


					</div>

				</div>
			</div>
			<footer class="text-center">
				<p>Copyright - 2020</p>
			</footer>
		</div>
	</div>
</body>

</html>

<?php $_SESSION['confirm'] = FALSE; ?>
