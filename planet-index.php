<?php 
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
}

require('database.php');

$planetFormatError = $searchEmpty = $pseudoEmpty = $planetEmpty = FALSE;
$feildEmptyErrorMsg = "Ce champ doit etre rempli";

//FUNCTIONS
function checkInput($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data); 
    return $data;
}

function checkPlanetFormat($data){
    $re = '/^([1-2])x([1-4]{0,1}[0-9]{0,1}[0-9]{0,1})x([1-9]{0,1}[0-5]{0,1})$/m';
    $str = $data;
    return preg_match($re, $str); 
}

function buildPlanetList($input)
{
    $db = Database::connect();
    $statement = $db->prepare('SELECT planet FROM planets WHERE pseudo = ?');
    $statement->execute(array($input));
    $rows = $statement->rowCount();
    

    //CHECK IF EXISTS
    if ($rows > 0) { 
        //SET PLANETS IN ARRAY
        $planetList = array();


        for ($i = 1 ; $i <= $rows ; $i++)
        {
                $result = $statement->fetch();
                $planetList["planet_".$i] = $result['planet'];
            
        }   
        //SORT ARRAY BY VALUES
        asort($planetList, SORT_NATURAL);
    }
    Database::disconect();
    return $planetList;
}

//CHECK IF PLANETS ARE NOT EMPTY STRINGS
function emptyPlanetCheck($list)
{
    foreach ($list as $value)
    {
        if ($value != "") {
            break;
            return TRUE;       
        }
    }
}
//--------------------------------------
//SEARCH
if (isset($_POST['search'])) { 
    $success = TRUE;
    $search = checkInput($_POST['search']);
    $planetList = buildPlanetList($search);

    if (empty($planetList)) {
        $success = FALSE;
    }
}
elseif (empty($_POST['search'])) {
    $searchEmpty = TRUE;
} 
else {
    $success = FALSE;
}
//--------------------------------------
//UPDATE OR ADD PLAYER

if (isset($_POST['pseudo']) && isset($_POST['planet'])) {
        $pseudo = checkInput($_POST['pseudo']);
        $planet = checkInput($_POST['planet']);

    if (!checkPlanetFormat($planet)) {
        $planetFormatError = TRUE;
        echo 'fuuuuk';
    }
    if (empty($_POST['pseudo']) && empty($_POST['planet'])) {
        $pseudoEmpty = TRUE;
        $planetEmpty = TRUE;
    }
    elseif (empty($_POST['planet'])) {
        $planetEmpty = TRUE;
    }
    elseif (empty($_POST['pseudo'])) {
        $pseudoEmpty = TRUE;
    }
    else {
        $success = TRUE;

        $db = Database::connect();
        $statement = $db->prepare('SELECT * FROM players WHERE pseudo = ?');
        $statement->execute(array($pseudo));
        $rows = $statement->rowCount();

        if (checkPlanetFormat($planet)) {
            //CHECK IF EXISTS
            if ($rows > 0) {
                $result = $statement->fetch();
                //CHECK EMPTY PLANET SLOT
                $emptySlot = 0;
                $slotFound = FALSE;
        
                if ($result['planet_1'] == "") {
                    $slotFound = TRUE;
                    $emptySlot = 1;
                }
                else {
                    for ($i = 1 ; $result['planet_'.$i] !== "" ; $i++)
                    {
                        $slotFound = TRUE;
                        $emptySlot = $i+1;
                    }  
                }

                //ADD PLANET TO SLOT
                if ($slotFound === TRUE) {
                    $statement = $db->prepare('UPDATE players SET planet_'. $emptySlot. '= ? WHERE pseudo = ?');
                    $statement->execute(array($_POST['planet'], $pseudo));
                }
                else {
                    echo 'Error, slot not found Slotfound = '.$slotFound;
                }
            }
            //ADD NEW PLAYER
            else {
                $statement = $db->prepare('INSERT INTO players (pseudo, planet_1) VALUES (?,?)');
                $statement->execute(array($pseudo, $planet));
            }
            Database::disconect();
        }
        else {
            $planetFormatError = TRUE;
            $planetFormatErrorMsg = 'Format non valide! (1x1x1)';
        }
        
    }
}

//DELETE PLANET
if (isset($_POST['delete'])) {
    $db = Database::connect();
    $statement = $db->prepare('UPDATE players SET '.$_POST["delete"].' = "" WHERE pseudo = ?');
    $statement->execute(array($_POST['pseudodel']));
    echo "SUCCESS";
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
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $('[data-toggle="tooltip"]').tooltip('show')
        })
    </script>
</head>

<body>
<div class="container-fluid wrapper-in p-0">
	
    <?php include("elements/menu.php") ?>
    
	<div class="container">
        <div class="row">
            <div class="col">
                <div class="module">
                    <div id="planet-search">
                        <h2>Recherche de joueur</h2>
                        <p>Utilisez ce formulaire pour afficher les planètes d'un joueur. Vous pouvez supprimer une planète avec le bouton dédié.</p>
                        <form action="planet-index.php" method="post">
                            <div>
                                <label for="search">Pseudo à rechercher:</label><br>
                                <input type="text" name="search" placeholder="Pseudo..">
                            <?php 
                            if ($searchEmpty && isset($_POST['search'])) {
                                echo '<span class="search">'. $feildEmptyErrorMsg .'</span>'; 
                            }
                            ?>
                                <button class="button-basic" type="submit">Rechercher</button>
                            </div>
                             
                         </form>
                         <div id="planet-display">
                            <?php
                            if(isset($_POST['search']) && $success) {
                                echo '<h2 class="pseudo">'. $_POST['search']. '</h2>';
                            }
                            ?>
                            <ul>
                            <?php 
                            if (isset($_POST['search']) && $success) {
                                foreach ($planetList as $k => $value)
                                {
                                    echo '<li class="planet-element">';
                                    echo '<p>'. $value . '</p>';

                                    // <form action="planet-delete.php" method="post">
                                    // <button class="btn-none" type="submit" name="slot" value="'. $k .'"> <i class="fas fa-trash"></i></button>
                                    // <input type="hidden" name="planet" value="'. $value .'">
                                    // <input type="hidden" name="pseudo"value="'. $_POST['search'] .'">
                                    // </form>
                                    //';
                                    echo '</li>';
                                }
                            }
                            elseif (isset($_POST['search']) && !$success) {
                                echo "Aucune entrée";
                            }
                            ?>
                            </ul>
                        </div>
                    </div>
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
