<?php
session_start();

$file = basename($_FILES['userfile']['name']);
$ext = pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);

if ($ext == "py") { //si l'extension est bien python on continue
   if (move_uploaded_file($_FILES['userfile']['tmp_name'], '../../src/java/src/fichier.py')) {
      //Don des droits d'accès pour pouvoir modifier le document créé
      chmod('../../src/java/src/fichier.py', 0777);
      
      //Lien généré grace a java
      $url = 'http://localhost:8001/test';

      //Arguments pour la requete Post  ------------------------------A MODIFIER------------------------------
      $postData = ['mots' => ['def','for','if','#']];

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
   } else {
   echo "Impossible de télécharger le fichier<br>";
   }
} else {
   echo "Le fichier doit avoir l'extension .py<br>";
}

//On supprime la copie du fichier python faite au debut
unlink('../../src/java/src/fichier.py');

print_r($_SESSION['valeurs']);
echo '<br>';
$dataPoints = array();

foreach ($_SESSION['valeurs']['mots'] as $key => $value) {
   array_push($dataPoints, array("y" => $value, "label" => $key));
}

?>
<!DOCTYPE HTML>
<html>
<head>
<script>
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
chart.render();
 
}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 90%; margin:auto;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>  
