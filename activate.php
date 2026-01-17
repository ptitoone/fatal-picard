<?php 
require ('database.php');

if(isset($_GET['id']) && isset($_GET['activation']))
{
    $id = inputCheck($_GET['id']);
    $activation = inputCheck($_GET['activation']);
    
    $db = Database::connect();
    $statement = $db->prepare('SELECT status FROM members WHERE id = ?');
    $statement->execute(array($id));

    if($statement->rowCount() > 0)
    {
        $result = $statement->fetch();

        if($result['status'] == 1)
        {
            echo 'Compte deja active';
        }
        else
        {
            echo 'OK ! Compte active';
            $statementActiv = $db->prepare('UPDATE members SET status = 1, activation = 0 WHERE id = ? AND activation = ?');
            $statementActiv->execute(array($id, $activation));
        }
    }
    else
    {
        echo 'fail';
    }

    Database::disconect();
    
}else{
    exit('Acces INTERDIT');
}


function inputCheck($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
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
                <div class="col">
                <h1>Compte activé!</h1>
                <p>Vous pouvez maintenant vous connecter.</p>
                    <a href="index.php" class="button-basic">Connexion</a>
               </div>
            </div>
        <footer class="text-center">
            <p>Copyright - 2020</p>
        </footer>
    </div>
    </div>

</body>

</html>