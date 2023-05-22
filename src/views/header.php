<?php

session_start();

?>

<!DOCTYPE html >
<html xml:lang="fr" lang="fr">
<meta charset="UTF-8">
<head>

    <link href="../../public/css/stylle.css" rel="stylesheet" />
    <link
    href="https://fonts.googleapis.com/css?family=Open+Sans"
    rel="stylesheet" />

    <title>Projet hackathon</title>
</head>
<body>
	 
	<header>
	   <div>
		<h1><em>Projet hackathon</em></h1> 
		<h4>Venez challenger vos données</h4>
	   </div>
		
	</header>
	<ul id="top"> 
		<li><a href="Acceuil.html" class="active">Accueil</a></li>
		<li><a href="Challenges.html">Challenges</a></li>
		<li><a href="Contacts.html">Contacts</a></li>
		<li><a href="Connexion.html" class="split">
			 <?php
				if(isset($_SESSION['utilisateur_connecte'])){
					echo('Déconnexion');
				} else {
					echo('Connexion');
				}
			?> 
		</a></li>
		


	</ul>
</body>
</html>
