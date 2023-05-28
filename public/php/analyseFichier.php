    <?php
    include('./header.php');

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
            <?php
            if (isset($_FILES['userfile']['name'])) {
                $file = basename($_FILES['userfile']['name']);
                $ext = pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);

                if ($ext == "py") { //si l'extension est bien python on continue
                if (move_uploaded_file($_FILES['userfile']['tmp_name'], '../../src/java/src/fichier.py')) {
                    //Don des droits d'accès pour pouvoir modifier le document créé
                    chmod('../../src/java/src/fichier.py', 0777);
                    
                    //Lien généré grace a java
                    $url = 'http://localhost:8001/test';

                    //On récupère les mots renseignés par l'utilisateur dont il faut chercher le nombre d'occurrences
                    $tab = array();
                    foreach(range(0, 9) as $valeur) {
                        if (isset($_POST[$valeur]) && $_POST[$valeur] != "") {
                            array_push($tab, $_POST[$valeur]);
                        }
                    }

                    $postData = ['mots' => $tab];

                    //Initialisaiton du cURL
                    $curl = curl_init($url);

                    //Parametrage du cURL pour une requete Post
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); //renvoie la reponse en tant que String
                    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                    curl_setopt($curl, CURLOPT_POST, true); //indique qu'on utilise la methode Post
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData)); //ajoute les arguments pour la methode Post

                    //Envoie la requete
                    $response = curl_exec($curl);

                    //Verifie qu'aucune erreur n'est apparue
                    if ($response === false) {
                        $error = curl_error($curl);
                        echo "cURL Error: " . $error;
                    }

                    //Ferme le cURL
                    curl_close($curl);

                    //Gestion de la réponse
                    if ($response) {
                        //Cree un fichier temporaire pour y stocker le resultat
                        $tempFile = tempnam(sys_get_temp_dir(), 'api_response_');

                        //Ecrit la reponse dans le fichier temporaire
                        file_put_contents($tempFile, $response);
                        
                        //Inclut le fichier temporaire dans le code
                        include $tempFile;
                        
                        //Supprime le fichier temporaire
                        unlink($tempFile);
                    }

                    echo "Nombre de lignes : ".$valeurs['nbLignes']."<br>";
                    echo "Nombre de fonctions : ".$valeurs['nbFonc']."<br>";
                    echo "Nombre de lignes de la plus petite fonction : ".$valeurs['nbMin']."<br>";
                    echo "Nombre de lignes de la plus grande fonction : ".$valeurs['nbMax']."<br>";
                    echo "Nombre de lignes moyen des fonctions : ".$valeurs['nbMoy']."<br>";

                    //Création de l'affichage des valeurs
                    $dataPoints = array();

                    foreach ($valeurs['mots'] as $key => $value) {
                        array_push($dataPoints, array("y" => $value, "label" => $key));
                    }

                ?>
                <script> //Script pour le graph
                window.onload = function() {
                
                var chart = new CanvasJS.Chart("chartContainer", {
                    animationEnabled: true,
                    title:{
                        text: "Nombre d'occurences des mots recherchés"
                    },
                    axisY: {
                        title: "Nombre d'occurrences",
                        includeZero: true
                    },
                    axisX: {
                        title: "Mots recherchés",
                        includeZero: true
                    },
                    data: [{
                        type: "bar",
                        indexLabel: "{y}",
                        indexLabelPlacement: "inside",
                        indexLabelFontWeight: "bolder",
                        indexLabelFontColor: "white",
                        dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                    }]
                });
                chart.render(); }
                </script>
                <div id="chartContainer" style="height: 370px; width: 90%; margin:auto;"></div>
                <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

                <?php } else {
                    echo "Impossible de télécharger le fichier<br>";
                }
                } else {
                echo "Le fichier doit avoir l'extension .py<br>";
                }

                //On supprime la copie du fichier python faite au debut
                if (file_exists('../../src/java/src/fichier.py')) {
                unlink('../../src/java/src/fichier.py');
                } ?>

            <?php } else { echo "Aucun fichier a analyser renseigné<br>"; } ?>
        </div>
    </body>
    </html>
    <div class="wave"></div>  
    <?php include('./footer.php'); ?>
    

    <?php } else { //----------CODE DANS LE CAS OU L'UTILISATEUR N'EST PAS CONNECTE----------?>
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
