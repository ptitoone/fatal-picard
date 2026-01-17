<?php 
session_start();
require 'database.php';

function fetchLastWin()
{
    $db = Database::connect();
    $statement = $db->prepare('SELECT * FROM winners ORDER BY id DESC LIMIT 1');
    $statement->execute();
    $result = $statement->fetch();
    Database::disconect();
    $winner = array('pseudo' => '', 'score' => '', 'author' => '', 'date' => '');
    $winner['pseudo'] = $result['pseudo'];
    $winner['score'] = $result['score'];
    $winner['author'] = $result['author'];
    $winner['date'] = $result['date'];
    return $winner;
}

function fetchLastNews()
{
    $db = Database::connect();
    $statement = $db->prepare('SELECT message, author, date FROM news ORDER BY id DESC LIMIT 1');
    $statement->execute();
    $result = $statement->fetch();
    Database::disconect();
    $news = array('message' => '', 'author' => '', 'date' => '');
    $news['message'] = $result['message'];
    $news['author'] = $result['author'];
    $news['date'] = $result['date'];
    return $news;
}

if(!isset($_SESSION['loggedin']))
{
    header('Location: index.php');
}
$winner = fetchLastWin();
$news = fetchLastNews();
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

		<?php include("elements/menu.php") ?>

        <div class="container">
            <div class="row">
                <div class="col col-element module" id="home-welcome">
                    <h1>Bienvenu à toi <span class="pseudo"><?php echo $_SESSION['pseudo']; ?></span>.</h1>
                    <h3>Annonce du moment</h3>
                    <?php echo htmlspecialchars_decode(stripslashes($news['message'])); ?>
                    <p class="text-right font-italic pseudo">Par <?php echo $news['author']. ' le ' .$news['date']; ?> </p>
                </div>
                <div class="row w-100 mx-auto">
                    <div class="col">
                        <div class="col-element module">
                            <h2>Vainqueur de la semaine</h2>
                            <h3>Félicitations à <span class="pseudo"><?php echo $winner['pseudo']; ?></span></h3>
                            <p>Il emporte la victoire avec une progression de <?php echo $winner['score']; ?> %.</p>
                            <p class="text-right font-italic pseudo">Par <?php echo $winner['author']. ' le ' .$winner['date']; ?> </p>
                        </div>
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
