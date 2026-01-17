<?php 
session_start();
if(!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="">

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

        <div class="ressources">
            <h3>Ressources dans le champ de débris</h3>
            <label for="metal">Metal:</label>
            <input id="inputmetal" type="text" name="metal" placeholder="0" onkeyup="recycleLog()">
            <label for="crystal">Crystal:</label>
            <input id="inputcrystal" type="text" name="crystal" placeholder="0" onkeyup="recycleLog()">
        </div>

        <div class="ressources">
            <h3>Ressources obtenues pour l'attaque</h3>
            <label for="inputmetalattack">Metal:</label>
            <input id="inputmetalattack" type="text" name="inputmetalattack" placeholder="0" onkeyup="ressourcesLog()">
            <label for="crystalattack">Crystal:</label>
            <input id="inputcrystalattack" type="text" name="inputcrystalattack" placeholder="0" onkeyup="ressourcesLog()">
            <label for="deuteriumattack">Deuterium:</label>
            <input id="inputdeuteriumattack" type="text" name="deuteriumattack" placeholder="0" onkeyup="ressourcesLog()">
        </div>

        <div class="ressources">
            <h3>Deuterium dépensé pour l'attaque</h3>
            <label for="deuteriumspent">Deuterium:</label>
            <input id="deuteriumspent" type="text" name="deuteriumspent" placeholder="0" onkeyup="deuteriumSpentLog()">
        </div>

        <table>
            <tr id="entitle">
                <td>
                    <p>Vaisseau</p>
                </td>
                <td>
                    <p>Nombre début</p>
                </td>
                <td>
                    <p>Nombre fin</p>
                </td>
                <td>
                    <p>Pertes</p>
                </td>
                <td>
                    <p>Cout Métal</p>
                </td>
                <td>
                    <p>Cout Crystal</p>
                </td>
                <td>
                    <p>Cout Deuterium</p>
                </td>
            </tr>
            <tr id="smallcargo">
                <td class="ship-name">
                    <p>Small Cargo</p>
                </td>
                <td class="count-before"><input width="30" type="text" placeholder="0" onkeyup="calcs(smallcargo['name'])">
                </td>
                <td class="count-after"><input width="30" type="text" placeholder="0" onkeyup="calcs(smallcargo['name'])">
                </td>
                <td class="remaining"><span></span></td>
                <td class="cost-metal"><span></span></td>
                <td class="cost-crystal"><span></span></td>
                <td class="cost-deuterium"><span></span></td>
            </tr>

            <tr id="largecargo">
                <td class="ship-name">
                    <p>Large Cargo</p>
                </td>
                <td class="count-before"><input width="30" type="text" placeholder="0" onkeyup="calcs(largecargo['name'])">
                </td>
                <td class="count-after"><input width="30" type="text" placeholder="0" onkeyup="calcs(largecargo['name'])">
                </td>
                <td class="remaining"><span></span></td>
                <td class="cost-metal"><span></span></td>
                <td class="cost-crystal"><span></span></td>
                <td class="cost-deuterium"><span></span></td>
            </tr>

            <tr id="lightfighter">
                <td class="ship-name">
                    <p>Light Fighter</p>
                </td>
                <td class="count-before"><input width="30" type="text" placeholder="0" onkeyup="calcs(lightfighter['name'])"></td>
                <td class="count-after"><input width="30" type="text" placeholder="0" onkeyup="calcs(lightfighter['name'])">
                </td>
                <td class="remaining"><span></span></td>
                <td class="cost-metal"><span></span></td>
                <td class="cost-crystal"><span></span></td>
                <td class="cost-deuterium"><span></span></td>
            </tr>

            <tr id="heavyfighter">
                <td class="ship-name">
                    <p>Heavy Fighter</p>
                </td>
                <td class="count-before"><input width="30" type="text" placeholder="0" onkeyup="calcs(heavyfighter['name'])"></td>
                <td class="count-after"><input width="30" type="text" placeholder="0" onkeyup="calcs(heavyfighter['name'])">
                </td>
                <td class="remaining"><span></span></td>
                <td class="cost-metal"><span></span></td>
                <td class="cost-crystal"><span></span></td>
                <td class="cost-deuterium"><span></span></td>
            </tr>

            <tr id="cruiser">
                <td class="ship-name">
                    <p>Cruiser</p>
                </td>
                <td class="count-before"><input width="30" type="text" placeholder="0" onkeyup="calcs(cruiser['name'])">
                </td>
                <td class="count-after"><input width="30" type="text" placeholder="0" onkeyup="calcs(cruiser['name'])"></td>
                <td class="remaining"><span></span></td>
                <td class="cost-metal"><span></span></td>
                <td class="cost-crystal"><span></span></td>
                <td class="cost-deuterium"><span></span></td>
            </tr>

            <tr id="battleship">
                <td class="ship-name">
                    <p>Battleship</p>
                </td>
                <td class="count-before"><input width="30" type="text" placeholder="0" onkeyup="calcs(battleship['name'])">
                </td>
                <td class="count-after"><input width="30" type="text" placeholder="0" onkeyup="calcs(battleship['name'])">
                </td>
                <td class="remaining"><span></span></td>
                <td class="cost-metal"><span></span></td>
                <td class="cost-crystal"><span></span></td>
                <td class="cost-deuterium"><span></span></td>
            </tr>

            <tr id="colonyship">
                <td class="ship-name">
                    <p>Colony Ship</p>
                </td>
                <td class="count-before"><input width="30" type="text" placeholder="0" onkeyup="calcs(colonyship['name'])">
                </td>
                <td class="count-after"><input width="30" type="text" placeholder="0" onkeyup="calcs(colonyship['name'])">
                </td>
                <td class="remaining"><span></span></td>
                <td class="cost-metal"><span></span></td>
                <td class="cost-crystal"><span></span></td>
                <td class="cost-deuterium"><span></span></td>
            </tr>

            <tr id="recycler">
                <td class="ship-name">
                    <p>Recycler</p>
                </td>
                <td class="count-before"><input width="30" type="text" placeholder="0" onkeyup="calcs(recycler['name'])">
                </td>
                <td class="count-after"><input width="30" type="text" placeholder="0" onkeyup="calcs(recycler['name'])">
                </td>
                <td class="remaining"><span></span></td>
                <td class="cost-metal"><span></span></td>
                <td class="cost-crystal"><span></span></td>
                <td class="cost-deuterium"><span></span></td>
            </tr>

            <tr id="probe">
                <td class="ship-name">
                    <p>Espionnage Probe</p>
                </td>
                <td class="count-before"><input width="30" type="text" placeholder="0" onkeyup="calcs(probe['name'])"></td>
                <td class="count-after"><input width="30" type="text" placeholder="0" onkeyup="calcs(probe['name'])"></td>
                <td class="remaining"><span></span></td>
                <td class="cost-metal"><span></span></td>
                <td class="cost-crystal"><span></span></td>
                <td class="cost-deuterium"><span></span></td>
            </tr>

            <tr id="bomber">
                <td class="ship-name">
                    <p>Bomber</p>
                </td>
                <td class="count-before"><input width="30" type="text" placeholder="0" onkeyup="calcs(bomber['name'])"></td>
                <td class="count-after"><input width="30" type="text" placeholder="0" onkeyup="calcs(bomber['name'])"></td>
                <td class="remaining"><span></span></td>
                <td class="cost-metal"><span></span></td>
                <td class="cost-crystal"><span></span></td>
                <td class="cost-deuterium"><span></span></td>
            </tr>

            <tr id="destroyer">
                <td class="ship-name">
                    <p>Destroyer</p>
                </td>
                <td class="count-before"><input width="30" type="text" placeholder="0" onkeyup="calcs(destroyer['name'])">
                </td>
                <td class="count-after"><input width="30" type="text" placeholder="0" onkeyup="calcs(destroyer['name'])">
                </td>
                <td class="remaining"><span></span></td>
                <td class="cost-metal"><span></span></td>
                <td class="cost-crystal"><span></span></td>
                <td class="cost-deuterium"><span></span></td>
            </tr>

            <tr id="deathstar">
                <td class="ship-name">
                    <p>Deathstar</p>
                </td>
                <td class="count-before"><input width="30" type="text" placeholder="0" onkeyup="calcs(deathstar['name'])">
                </td>
                <td class="count-after"><input width="30" type="text" placeholder="0" onkeyup="calcs(deathstar['name'])">
                </td>
                <td class="remaining"><span></span></td>
                <td class="cost-metal"><span></span></td>
                <td class="cost-crystal"><span></span></td>
                <td class="cost-deuterium"><span></span></td>
            </tr>

            <tr id="battlecruiser">
                <td class="ship-name">
                    <p>Battlecruiser</p>
                </td>
                <td class="count-before"><input width="30" type="text" placeholder="0" onkeyup="calcs(battlecruiser['name'])"></td>
                <td class="count-after"><input width="30" type="text" placeholder="0" onkeyup="calcs(battlecruiser['name'])"></td>
                <td class="remaining"><span></span></td>
                <td class="cost-metal"><span></span></td>
                <td class="cost-crystal"><span></span></td>
                <td class="cost-deuterium"><span></span></td>
            </tr>

            <tr id="megacargo">
                <td class="ship-name">
                    <p>Mega Cargo</p>
                </td>
                <td class="count-before"><input width="30" type="text" placeholder="0" onkeyup="calcs(megacargo['name'])">
                </td>
                <td class="count-after"><input width="30" type="text" placeholder="0" onkeyup="calcs(megacargo['name'])">
                </td>
                <td class="remaining"><span></span></td>
                <td class="cost-metal"><span></span></td>
                <td class="cost-crystal"><span></span></td>
                <td class="cost-deuterium"><span></span></td>
            </tr>

            <tr id="xp">
                <td class="ship-name">
                    <p>XP0</p>
                </td>
                <td class="count-before"><input width="30" type="text" placeholder="0" onkeyup="calcs(xp['name'])"></td>
                <td class="count-after"><input width="30" type="text" placeholder="0" onkeyup="calcs(xp['name'])"></td>
                <td class="remaining"><span></span></td>
                <td class="cost-metal"><span></span></td>
                <td class="cost-crystal"><span></span></td>
                <td class="cost-deuterium"><span></span></td>
            </tr>

            <tr id="total">
                <td class="ship-name">
                    <p><strong>TOTAL</strong></p>
                </td>
                <td class="count-before"></td>
                <td class="count-after"></td>
                <td class="remaining"><span></span></td>
                <td class="cost-metal"><span></span></td>
                <td class="cost-crystal"><span></span></td>
                <td class="cost-deuterium"><span></span></td>
            </tr>

        </table>

        <div id="report">
            <p>Apres avoir reconstruit votre flotte vous aurez <span id="reportmetal"></span> Métal, <span id="reportcrystal"></span> Crystal et <span id="reportdeuterium"></span> Deuterium.</p>
            <p>Vous aurez besoin de <span id="reportrecyclers"></span> Recycleurs.</p>
            <p>Vous aurez besoin de <span id="reportmegacarg"></span> Mega Cargos.</p>
        </div>
        <script type="text/javascript" src="script.js"></script>
</body>

</html>