<?php
session_start();
require 'database.php';

if (isset($_SESSION['loggedin']) && isset($_SESSION['adminx']) < 1) {
    header('Location: home.php');}
elseif (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
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

if ($_SESSION['admin'] = 0) {
    header('Location: index.php');
}

$db = Database::connect();
$statement = $db->query('SELECT pseudo FROM members');
$rows = $statement->rowCount();


$members = array();

for ($i = 0 ; $i < $rows ; $i++)
{
    $result = $statement->fetch();
    array_push($members, $result['pseudo'],);
}

Database::disconect();

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
    <script src="https://cdn.tiny.cloud/1/qjewkig72hcfqy4sy95hjdy7kj0t6b3zt0j6uxkm653jcjpn/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: '#message'
      });
    </script>
</head>

<body>
<div class="container-fluid wrapper-in p-0">
	
	<?php include("elements/menu.php") ?>

</div>
<div class="container d-flex justifiy-contnent-center">
    <div class="row">
        <div class="col-12 text-center ">
            <h1>Administration</h1>
            <h2>Bienvenu à toi <span class="pseudo"><?php echo $_SESSION['pseudo']; ?></span> ;)</h2>
        </div>
    <div class="row w-100 mx-auto">
        <div class="col-md-6">
            <div class="module col-element">
                <h2>Modifier méssage d'acceuil</h2>
                <form action="edit-welcome.php" method="post">
                    <label for="message">Message:</label><br>
                    <textarea id="message" name="message" rows="15">
                    <?php echo htmlspecialchars_decode(stripslashes($news['message'])); ?>
                    </textarea><br>
                    <button type="submit" class="button-basic">Mettre à jour</button>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="module col-element">
                <h2>Modifier vainqueur de la semaine</h2>
                <form action="edit-winner.php" method="post">
                    <label for="pseudo">Pseudo:</label><br>
                    <select name="pseudo" id="pseudo">
                        <?php 
                            for ($i = 0 ; $i < count($members) ; $i++) {
                                echo '<option value="'.$members[$i].'">'.$members[$i].'</option>';
                            }
                        ?>
                    </select><br>
                    <label for="score">Pourcentage: </label><br>
                    <input type="number" name="score"><br>
                    <!-- <label for="prize">Recompense: </label><br>
                    <input type="number" name="prize"><br> -->
                    <button type="submit" class="button-basic">Mettre a jour</button>
                </form>
            </div>
        </div> 
    </div>
    </div>
</div>
<footer class="text-center">
    <p>Copyright - 2020</p>
</footer>
</body>
</html>
