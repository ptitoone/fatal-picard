<?php
session_start();
require('database.php');

if(!isset($_POST['pseudo']) || !isset($_POST['password']))
{
    exit('Access INTERDIT');
}

if($_POST['pseudo'] == "" || $_POST['password'] == "")
{
    exit('Veuillez remplir tout le champs');
}
else
{
    $db = Database::connect();
    $statement = $db->prepare('SELECT id, pseudo, password, admin FROM members WHERE pseudo = ?');
    $statement->execute(array($_POST['pseudo']));
    $result = $statement->fetch();
    
    if(isset($result['pseudo']) == $_POST['pseudo']){
        
        if(!password_verify($_POST['password'], $result['password']))
        {
            echo 'Mot de passe incorrect!<br>';
        }
        else if($result['pseudo'] == $_POST['pseudo'] && password_verify($_POST['password'], $result['password']))
        {
            $id = $result['id'];
            session_regenerate_id();
            $_SESSION['adminx'] = $result['admin'];
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['pseudo'] = $_POST['pseudo'];
            $_SESSION['id'] = $id;

            echo '<script>setTimeout(function(){window.location.href = "home.php"}, 4 * 1000);</script>';
        }
        
    }else{
        echo 'Pseudo incorect!';
    }
    Database::disconect();
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
</head>

<body>
    <div class="container-fluid wrapper-out">
        <div class="container login">
            <div class="row">
                <h1 class="fatal-logo">FATAL PICARD</h1>
            </div>
            <div class="row">
                <div class="col">
                <h2>Connexion reussie!</h2>
               </div>
            </div>
        <footer class="text-center">
            <p>Copyright - 2020</p>
        </footer>
    </div>
    </div>

</body>

</html>