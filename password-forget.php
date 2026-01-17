<?php
session_start();
require('database.php');

if(isset($_SESSION['loggedin']))
{
    header('Location: home.php');
}

function inputCheck($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function sendMail($email, $password)
{
    $to = $email;
    $subject = 'Mot de passe oublié';
    $msg = '<p>Votre nouveau mot de passe est le : '. $password .'</p>';
    $msg = wordwrap($msg, 70);
    
    // Always set content-type when sending HTML email
    $headers = "From: <noreply@fatal-picard.lescigales.org>\r\n";
    $headers .= "Reply-To: <fatal-picard.lescigales.org>\r\n";
    $headers .= "CC: ptit.antho@gmail.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    mail($to,$subject,$msg,$headers);
    
}

function generateRandomString($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$eEmail = $aEmail = FALSE;
$_SESSION['confirm'] = FALSE;

if (isset($_POST['email']) && !empty($_POST['email'])) {
    $success = TRUE;
    $db = Database::connect();
    $statement = $db->prepare('SELECT pseudo, email FROM members WHERE email = ?');
    $statement->execute(array($_POST['email']));
    $rows = $statement->rowCount();
    if ($rows > 0) {
        $result = $statement->fetch();
        $password = generateRandomString();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        sendMail($result['email'], $password);
        $statement = $db->prepare('UPDATE members SET password = ? WHERE email = ?');
        $statement->execute(array($hashedPassword, $result['email']));
        Database::disconect();
        $_SESSION['confirm'] = TRUE;
    } 
    else {
        $success = FALSE;
        $aEmail = TRUE;
    }
} 
elseif (isset($_POST['email']) && empty($_POST['email'])) {
    $eEmail = TRUE;
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
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $('[data-toggle="tooltip"]').tooltip('show')
        })
    </script>
</head>

<body>
    <div class="container login">
        <div class="row">
            <h1 class="fatal-logo">FATAL PICARD</h1>
        </div>
        <div class="row">
            <div class="col">
                <?php
                if (!$_SESSION['confirm']) {
                    echo '<h3 class="text-center">Récuperation de mot de passe</h3>';
                }
                if (!$_SESSION['confirm']) {
                    echo '<form id="form-connexion" action="password-forget.php" method="post">';
                    echo '<p class="pseudo font-italic mx-auto">Veuillez-entrer votre email.</p>';
                    echo '<div class="input-group">';
                    echo '<label for="email"><i class="fas fa-user"></i></label>';
                    echo '<input type="text" name="email" placeholder="Email.." value="'; if (isset($_POST['email'])){echo $_POST['email'];} echo '" ';
                    if ($eEmail)
                    {
                        echo 'data-toggle="tooltip" data-placement="bottom" data-trigger="manual" title="Ce champ doit etre rempli"';
                    }
                    elseif ($aEmail) {
                        echo 'data-toggle="tooltip" data-placement="bottom" data-trigger="manual" title="Cet email n\'existe pas"';
                    }
                    echo '>';
                    echo '</div>';
        
                    echo '<button class="button-basic" type="submit">Envoyer</button>';
                    echo '</form>';
                }
                else {
                    echo' <div class="col text-center">';
                    echo '<h2>Mot de passe mis à jour!</h2>';
                    echo '<p>Veuillez vérifier vos mails pour découvrir votre nouveau mot de passe.</p>';
                    echo '<a href="index.php" class="button-basic">Retour à l\'acceuil</a>';
                    echo '</div>';
                    $_SESSION['confirm'] = FALSE;
                }
                ?>
            </div>
        </div>
    </div>
    <footer class="text-center">
        <p>Copyright - 2020</p>
    </footer>
</body>
</html>