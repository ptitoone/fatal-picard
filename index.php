<?php 
session_start();
require('database.php');

if (isset($_SESSION['loggedin'])) {
    header('Location: home.php');
}

$_SESSION['ePseudo'] = FALSE;
$_SESSION['ePassword'] = FALSE;
$_SESSION['success'] = FALSE;


if (!empty($_POST['pseudo']) && !empty($_POST['password']) && isset($_POST['pseudo']) && isset($_POST['password'])) {

    $pseudo = strtolower($_POST['pseudo']);
    $db = Database::connect();
    $statement = $db->prepare('SELECT id, pseudo, password, admin FROM members WHERE pseudo = ?');
    $statement->execute(array($_POST['pseudo']));
    $result = $statement->fetch();
    $lowPseudo = strtolower($result['pseudo']);
    if(isset($lowPseudo) == $pseudo){
        
        if (!password_verify($_POST['password'], $result['password']))
        {
            $_SESSION['ePassword'] = TRUE;
        }
        else if($lowPseudo == $pseudo && password_verify($_POST['password'], $result['password']))
        {
            $id = $result['id'];
            session_regenerate_id();
            $_SESSION['adminx'] = $result['admin'];
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['pseudo'] = $result['pseudo'];
            $_SESSION['id'] = $id;
            $_SESSION['success'] = TRUE;
        }
        
    }
    else {
        $_SESSION['ePseudo'] = TRUE;
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
    <script type="text/javascript">
        $(function () {
            $('[data-toggle="tooltip"]').tooltip('show')
        })
    </script>
</head>

<body>
    <div class="container-fluid wrapper-out">
        <div class="container login">
            <div class="row">
                <h1 class="fatal-logo">FATAL PICARD</h1>
            </div>
            <div class="row">
                <div class="col">
                    <?php 
                        if ($_SESSION['ePseudo']) {
                             echo 'Pseudo inconu';
                             echo '<script>setTimeout(function(){window.location.href = "index.php"}, 3 * 1000);</script>';
                        }
                        elseif ($_SESSION['ePassword']) {
                            echo 'Identifiants invlides!';
                            echo '<script>setTimeout(function(){window.location.href = "index.php"}, 3 * 1000);</script>';
                        }elseif ($_SESSION['success']) {
                            echo 'Connexion reussie!';
                            echo '<script>setTimeout(function(){window.location.href = "index.php"}, 3 * 1000);</script>';
                        }
                        else {
                            echo '<form id="form-connexion" action="index.php" method="post">';
                            echo '<div class="input-group">';
                            echo '<label for="login"><i class="fas fa-user"></i></label>';
                            echo '<input type="text" name="pseudo" placeholder="Pseudo.."'; if (isset($_POST['pseudo']) && empty($_POST['pseudo'])){echo ' data-toggle="tooltip" data-placement="bottom" data-trigger="manual" title="Ce champ doit être rempli"';} 
                            echo '>';
                            echo '</div>';
                            echo '<div class="input-group">';
                            echo '<label for="password"><i class="fas fa-key"></i></label>';
                            echo '<input type="password" name="password" placeholder="Mot de passe.."'; if (isset($_POST['password']) && empty($_POST['password'])){ echo ' data-toggle="tooltip" data-placement="bottom" data-trigger="manual" title="Ce champ doit être rempli"';} 
                            echo '>';
                            echo '</div>';
                            echo '<button class="button-basic" type="submit">Connexion</button>';
                            echo '</form>';
                        }
                    ?>
                    
                </div>
            </div>
            <div class="row">
                <div class="col text-center form-links">
                    <a href="password-forget.php" class="d-block">Mot de passe oublié?</a>
                    <a href="inscription.php" class="d-block">S'enregistrer</a>
                </div>
            </div>
        </div>
        <footer class="text-center">
            <p>Copyright - 2020</p>
        </footer>
    </div>

</body>

</html>
