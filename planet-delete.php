<?php
session_start();
require('database.php');
if(!isset($_SESSION['loggedin']))
{
    header('Location: index.php');
}

//DELETE PLANET
if(isset($_POST['deleteConfirm']) == true)
{
    $db = Database::connect();
    $statement = $db->prepare('UPDATE players SET '.$_POST["slot"].' = "" WHERE pseudo = ?');
    $statement->execute(array($_POST['pseudo']));
    Database::disconect();
    echo '<script>setTimeout(function(){window.location.href = "planet-index.php"}, 4 * 1000);</script>';
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
    <div class="container-fluid wrapper-in p-0">
        <div class="container-fluid p-0">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <a class="navbar-brand" href="home.php">
                    <h1 class="fatal-logo">FATAL PICARD</h1>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link" href="home.php">Acceuil</a></li>
                        <?php 
                        if (isset($_SESSION['loggedin']) && isset($_SESSION['adminx']) > 0) {
                            echo '<li class="nav-item"><a class="nav-link" href="admin.php">Administration</a></li>';
                        } 
                        ?>
                        <li class="nav-item"><a class="nav-link" href="planet-index.php">Index Planètes</a></li>
                        <li class="nav-item"><a class="nav-link" href="outil-rentabilite.html">Outil Rentabilité</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="container">
            <div class="row">
                <div class="col text-center">

                    <div class="module">
                    <?php 
                    
                    if (isset($_POST['deleteConfirm']) == true)
                    {
                        echo '<h2>Planete supprimée avec success !!</h2>';
                    }
                    else
                    {
                        echo '<h2>Etes vous sur de voulour supprimer cette planete: '. $_POST['planet'] .'?</h2>';
                        echo '<form action="planet-delete.php" method="post">';
                        echo '<input type="hidden" name="slot" value="'. $_POST['slot'] .'">';
                        echo '<input type="hidden" name="pseudo" value="'. $_POST['pseudo'] .'">';
                        echo '<button type="submit" name="deleteConfirm" class="button-basic-warning" value="true">Supprimer</button>';
                        echo '<a href="planet-index.php" class="button-basic">Retour</a>';
                        echo '</form>';
                    }
                
                    ?>
                    </div>
                </div>
            </div>
        </div> 
    <footer class="text-center">
        <p>Copyright - 2020</p>
    </footer>
</body>
</html>