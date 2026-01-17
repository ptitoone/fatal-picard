<?php 
session_start();
require('database.php');

function inputCheck($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function sendMail($email, $id, $activation)
{
    $to = $email;
    $subject = 'Activez votre compte Fatal Picard';
    $msg = '<a href="http://fatal-picard.lescigales.org/activate.php?id='. $id .'&activation='. $activation .'">Click ici pour activer ton compte!<a/>';
    $msg = wordwrap($msg, 70);
    
    // Always set content-type when sending HTML email
    $headers = "From: <noreply@fatal-picard.lescigales.org>\r\n";
    $headers .= "Reply-To: <fatal-picard.lescigales.org>\r\n";
    $headers .= "CC: ptit.antho@gmail.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    mail($to,$subject,$msg,$headers);   
}

if(isset($_SESSION['loggedin']))
{
    header('Location: home.php');
}

$isSucces = TRUE;
$isConfirm = FALSE;
$aRegistered = $aPseudo = $fPassword = $aPasswordC = $aEmail = FALSE;
$ePseudo = $ePassword = $ePasswordC = $eEmail = $fEmail = FALSE;

if(isset($_POST['pseudo']) && empty($_POST['pseudo'])){
    $ePseudo = TRUE;
    $isSucces = FALSE;
}

if(isset($_POST['password']) && empty($_POST['password'])){
    $ePassword = TRUE;
    $isSucces = FALSE;
}

if(isset($_POST['password-confirm']) && empty($_POST['password-confirm'])){
    $ePasswordC = TRUE;
    $isSucces = FALSE;
}

if(isset($_POST['email']) && empty($_POST['email'])){
    $eEmail = TRUE;
    $isSucces = FALSE;
}

if (isset($_POST['pseudo']) && isset($_POST['password']) && isset($_POST['password-confirm']) && isset($_POST['email'])){
// Database conect
    $db = Database::connect();
// Checked variables
    $pseudo = inputCheck($_POST['pseudo']);
    $password = inputCheck($_POST['password']);
    $passwordConfirm = $_POST['password-confirm'];
    $email = inputCheck($_POST['email']);

 
 // Check for existing member
    $tryEmail = $db->prepare('SELECT * FROM members WHERE email = ?');
    $tryEmail->execute(array($email));
    $rowEmail = $tryEmail->rowCount();
 
    $tryPseudo = $db->prepare('SELECT * FROM members WHERE pseudo = ?');
    $tryPseudo->execute(array($pseudo));
    $rowPseudo = $tryPseudo->rowCount();
    if($rowPseudo > 0 && $rowEmail > 0)
    {
        $aRegistered = TRUE;
        $isSucces = FALSE;
    }
    if($rowPseudo > 0)
    {
        $aPseudo = TRUE;
        $isSucces = FALSE;
    }
    if($rowEmail > 0)
    {
        $aEmail = TRUE;
        $isSucces = FALSE;
    }

    if($password == $passwordConfirm)
    {
        if(strlen($password) < 5) {
            $fPassword = TRUE;
            $isSucces = FALSE;
        }
    }
    else
    {
        $aPasswordC = TRUE;
        $isSucces = FALSE;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $fEmail = TRUE;
        $isSucces = FALSE;
    }


// If all feilds are correct
   
    // Register member
    if($isSucces)
    {
        $activation = md5(rand());
        $hashedPass = password_hash($password, PASSWORD_DEFAULT);
        $db = Database::connect();
        $statement = $db->prepare('INSERT INTO members (pseudo, password, email, activation, registered_date) VALUES (?, ?, ?, ?, NOW())');
        $statement->execute(array($pseudo, $hashedPass, $email, $activation));
        $statement = $db->prepare('SELECT id FROM members WHERE pseudo = ?');
        $statement->execute(array($pseudo));
        $result = $statement->fetch();
        //SEND ACTIVATION MAIL
        //sendMail($email, $result['id'], $activation);
        Database::disconect();
        $_SESSION['confirm'] = TRUE;
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
               <h3 class="text-center">Inscription</h3>
               <?php 
               if (!$_SESSION['confirm']) {
                    echo '<form id="form-connexion" action="inscription.php" method="post">';
                    echo '<p class="pseudo font-italic">Le mot de passe doit faire minimum 5 caractèress.</p>';
                    echo '<div class="input-group">';
                    echo '<label for="pseudo"><i class="fas fa-user"></i></label>';
                    echo '<input  type="text" name="pseudo" placeholder="Pseudo.."';
                    if ($ePseudo) {
                        echo ' data-toggle="tooltip" data-placement="bottom" data-trigger="manual" title="Ce champ doit être rempli"';
                    }
                    elseif ($aPseudo) {
                        echo 'data-toggle="tooltip" data-placement="bottom" data-trigger="manual" title="Ce pseudo existe déja"';
                    }
                    echo '>';
                    echo '</div>';

                    echo '<div class="input-group">';
                    echo ' <label for="password"><i class="fas fa-key"></i></label>';
                    echo ' <input type="password" name="password" placeholder="Mot de passe.."';
                    if ($ePassword) {
                        echo 'data-toggle="tooltip" data-placement="bottom" data-trigger="manual" title="Ce champ doit être rempli"';
                    }
                    elseif ($fPassword) {
                        echo 'data-toggle="tooltip" data-placement="bottom" data-trigger="manual" title="Mot de passe invalide"';
                    }
                    echo '>';
                    echo '</div>';

                    echo '<div class="input-group">';
                    echo '<label for="password-confirm"><i class="fas fa-key"></i></label>';
                    echo '<input type="password" name="password-confirm" placeholder="Confirmer mot de passe.."';
                    if ($ePasswordC) {
                        echo 'data-toggle="tooltip" data-placement="bottom" data-trigger="manual" title="Ce champ doit etre rempli"';
                    }
                    elseif ($aPasswordC) {
                        echo 'data-toggle="tooltip" data-placement="bottom" data-trigger="manual" title="Mots de passe différents"';
                    }
                    echo '>';
                    echo '</div>';

                    echo '<div class="input-group">';
                    echo '<label for="email"><i class="fas fa-envelope"></i></label>';
                    echo '<input  type="text" name="email" placeholder="Email.."';
                    if ($eEmail)
                    {
                        echo 'data-toggle="tooltip" data-placement="bottom" data-trigger="manual" title="Ce champ doit être rempli"';
                    }
                    elseif ($aEmail) {
                        echo 'data-toggle="tooltip" data-placement="bottom" data-trigger="manual" title="Cet email existe déjà"';
                    }
                    elseif ($fEmail) {
                        echo 'data-toggle="tooltip" data-placement="bottom" data-trigger="manual" title="Email invalide"';
                    }
                    echo '>';
                    echo '</div>';
        
                    echo '<button class="button-basic" type="submit">S\'inscrire</button>';
                    echo '</form>';
                }
                else {
                    echo' <div class="col text-center">';
                    echo '<h2>Inscription réussie!</h2>';
                    echo '<p>Veuillez demander a Antchech_Komb le lien d\'activation sur le groupe messenger de l\'ally.</p>';
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
