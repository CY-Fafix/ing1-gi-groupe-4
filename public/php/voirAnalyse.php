<?php
    include('./header.php');
    require_once("../../src/controllers/etudiant_controller.php");
    
    if (!isset($_POST['team_id'])) {
        echo header('Location: ../index.php');
        exit;
    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link href="../css/stylle.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />
    <link href="../css/voirAnalyse.css" rel="stylesheet" />
</head>
<body>
    <div class="Main">
        <div class="cadre">
        <?php
            //On récupère les analyses de code resneignées dans la base de données
            $controller = new EtudiantController();
            $valeurs = $controller->getAnalyses($_POST['team_id']);
            
            if ($valeurs[0] == NULL) {
                echo "Aucun code n'a été analysé";
            } else {
                echo "<div class='titre'>Analyse du code :</div>";
                echo "Nombre de lignes : ".$valeurs[0]."<br>";
                echo "Nombre de fonctions : ".$valeurs[1]."<br>";
                echo "Nombre de lignes de la plus petite fonction : ".$valeurs[2]."<br>";
                echo "Nombre de lignes de la plus grande fonction : ".$valeurs[3]."<br>";
                echo "Nombre de lignes moyen des fonctions : ".$valeurs[4]."<br>";
            }
            
            
        ?>
        </div>
    </div>
</body>
</html>