<?php 
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

$isSucces = true; 

// Variable presence check
if(!isset($_POST['pseudo']) || !isset($_POST['password']) || !isset($_POST['password-confirm']) || !isset($_POST['email']))
{
    exit('Acces INTERDIT');
}
// Input checks
if(empty($_POST['pseudo']))
{
    $isSucces = false;
    $pseudoError = 'Ce champ doit etre rempli';
}
if(empty($_POST['password']))
{
    $isSucces = false;
    $passwordError = 'Ce champ doit etre rempli';
}
if(empty($_POST['password-confirm']))
{
    $isSucces = false;
    $passwordConfirmError = 'Ce champ doit etre rempli';
}
if(empty($_POST['email']))
{
    $isSucces = false;
    $emailError = 'Ce champ doit etre rempli';
}
// If all feilds are correct
if($isSucces)
{
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
            echo 'Vous etes dejà inscrit';
        }
        else if($rowPseudo > 0)
        {
            echo 'Ce pseudo existe dejà';
        }
        else if($rowEmail > 0)
        {
            echo 'Cette adresse email existe dejà';
        }
        else
        {
            // Validate password
            $uppercase = preg_match('@[A-Z]@', $password);
            $lowercase = preg_match('@[a-z]@', $password);
            $number    = preg_match('@[0-9]@', $password);
            $specialChars = preg_match('@[^\w]@', $password);

            if($password == $passwordConfirm)
            {
                if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
                    $isSucces = false;
                    $passwordErrorFormat = 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.';
                    echo $passwordErrorFormat. '<br>';
                    echo $password;
                }
            }else
            {
                $isSucces = false;
                $passwordConfirmError = 'Les deux mots de passe ne sont pas identiques';
                echo $passwordConfirmError;
            }

            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $isSucces = false;
                $emailErrorFormat = 'Veuillez entrer une adresse email valide.';
                echo $emailErrorFormat;
            }
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
                sendMail($email, $result['id'], $activation);
                Database::disconect();
            } 
        }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>Fatal-Picard Inscription</title>
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
                <div class="col text-center">
                <h2>Inscription réussie!</h2>
                <p>Veuillez cliquer sur le lien d'activation qui vous à été envoyé par email.</p>
                    <a href="index.php" class="button-basic">Retour à l'acceuil</a>
               </div>
            </div>
        <footer class="text-center">
            <p>Copyright - 2020</p>
        </footer>
    </div>
    </div>

</body>

</html>