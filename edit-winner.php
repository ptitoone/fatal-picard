<?php
session_start();
require 'database.php';
$admin = FALSE;
$success = FALSE;
echo '<script>setTimeout(function(){window.location.href = "admin.php"}, 4 * 1000);</script>';

function checkInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

function addWinner($pseudo, $score, $author)
{
    $db = Database::connect();
    $statement = $db->prepare('INSERT INTO winners (pseudo, score, author, date) VALUES (?, ?, ?, NOW())');
    $statement->execute(array($pseudo, $score, $author));
    Database::disconect();
}

if ($_SESSION['adminx'] > 0) {
    $admin = TRUE;
}

if (isset($_POST['pseudo']) && isset($_POST['score']) && $admin) {
    $pseudo = checkInput($_POST['pseudo']);
    $score = checkInput($_POST['score']);
    $success = TRUE;

    addWinner($pseudo, $score, $_SESSION['pseudo']);
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
                    <?php 
                    if ($success && isset($_POST['pseudo']) && isset($_POST['score']) && $admin) {
                        echo '<h2>Vainqueur de la semaine mis à jour avec succès !</h2>';
                    }
                    else {
                        echo '<h2>Access interdit</h2>';
                    }
                    ?>
               </div>
            </div>
        <footer class="text-center">
            <p>Copyright - 2020</p>
        </footer>
    </div>
    </div>

</body>

</html>