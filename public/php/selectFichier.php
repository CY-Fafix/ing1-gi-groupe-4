    <?php
    include('./header.php');
    $_SESSION['user_id'] = 2; //--------TEMPORAIRE--------

    if (isset($_SESSION['user_id'])) { //----------CODE DANS LE CAS OU L'UTILISATEUR EST CONNECTE---------- ?>

    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        
        <link href="../css/stylle.css" rel="stylesheet" />
        <link href="../css/inscription.css" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />
    
        <title>  </title>
    </head>
    <body>

        <div class="Main">
            <form enctype="multipart/form-data" action="analyseFichier.php" method="POST">
            <input type="hidden" name="MAX_FILE_SIZE" value="50000" />
            Choisir le fichier: <input name="userfile" type="file" />
            <br><br>Entrez les mots donc vous souhaitez connaitre le nombre d'occurrences<br>
            <?php foreach(range(0, 9) as $valeur) { echo '<input type=text size=10 value="" name='.$valeur.' ><br>'; } ?><br>
            <input type="submit" value="Analyser le fichier" />
        </form>
        </div>
    </body>
    </html>
    <div class="wave"></div>  
    <?php include('./footer.php'); ?>

    <?php } else { //----------CODE DANS LE CAS OU L'UTILISATEUR N'EST PAS CONNECTE---------- ?> 

    <!DOCTYPE HTML>
    <html>
        <head>
            <meta charset="UTF-8">
            
            <link href="../css/stylle.css" rel="stylesheet" />
            <link href="../css/inscription.css" rel="stylesheet" />
            <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />
        
            <title>  </title>
        </head>
        <body>

            <div class="Main">
                Veuillez vous connecter pour accéder à cette page
            </div>
    </body>
    </html>
    <div class="wave"></div>  
    <?php include('./footer.php'); ?>
    <?php } ?>