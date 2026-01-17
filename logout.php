<?php 
session_start();
session_destroy();
echo '<script>setTimeout(function(){window.location.href = "home.php"}, 4 * 1000);</script>';
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
                <h2>Déconnexion réussie</h2>
               </div>
            </div>
        <footer class="text-center">
            <p>Copyright - 2020</p>
        </footer>
    </div>
    </div>

</body>

</html>