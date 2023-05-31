<?php
    include('./header.php');

    //$_SESSION['user_id'] = 2; //--------TEMPORAIRE--------

    //Si l'utilisateur n'est pas connecté on ne va pas sur cette page
    if (!isset($_SESSION['user_id'])){
        if ($_SESSION['role'] != 'Etudiant'){  
            echo  
            header('Location: ../index.php');
            exit;
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link href="../css/stylle.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />
    <link href="../css/selectFichier.css" rel="stylesheet" />

    <title>  </title>
</head>
<body>

    <div class="Main">
        <div class="cadre">
            <form enctype="multipart/form-data" action="analyseFichier.php" method="POST">
            <input type="hidden" name="MAX_FILE_SIZE" value="50000" />
            Choisir le fichier: <input name="userfile" type="file" />
            <br><br>Entrez les mots donc vous souhaitez connaitre le nombre d'occurrences<br>
            <?php foreach(range(0, 9) as $valeur) { echo '<input type=text size=10 value="" name='.$valeur.' ><br>'; } ?><br>
            <input type="submit" value="Analyser le fichier" />
            </form>
        </div>
    </div>
</body>
</html>
<?php include('./footer.php'); ?>
