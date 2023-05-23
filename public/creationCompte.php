<?php include('./header.php'); ?>


<html lang="fr">
	
	<head>
		<meta charset="UTF-8">
		
		<link href="./css/stylle.css" rel="stylesheet" />
		<link href="./css/contact.css" rel="stylesheet" />
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />
	
		<title> Création d'un nouveau compte </title>
	</head>
	
	
	
	<body>
		<div class="Main">
			
			<p id="Title"> <strong>Création d'un nouveau compte</strong> </p>
			
			<form id="ContactFormulaire" name="contactFormulaire" onsubmit="return verifDateDeNaissance()" method="POST" action="">
			<script type="text/javascript" src="./js/verificationDateDeNaissance.js" defer> </script>
				
				<label id="Nom"> Nom :
					<input class="Verify" id="NomZone" type="text" name="nom" placeholder="Entrez votre nom" required>
				</label>
				
				<label id="Prenom"> Prénom :
					<input class="Verify" id="PrenomZone" type="text" name="prenom" placeholder="Entrez votre prénom" required>
				</label>
				
				<label id="Genre"> Genre :
					<input class="Verify" id="GenreZone1" type="radio" name="genre" value="Homme" required>
						<label> Homme </label>
					<input class="Verify" id="GenreZone2" type="radio" name="genre" value="Femme" required>
						<label> Femme </label>
					<input class="Verify" id="GenreZone3" type="radio" name="genre" value="Non-binaire" required>
						<label> Non-binaire </label>
				</label>
				
				<label id="DateNaissance"> Date de naissance :
					<input class="Verify" id="DateNaissanceZone" type="date" name="dateNaissance" required>
					<span class="Erreur" id="FormatDate1" aria-live="polite"> <em>Veuillez entrer une date inférieure à celle du jour (!)</em> </span>
				</label>
				
				<label id="Statut"> Statut :
					<select id="StatutZone" name="statut">
						<option value="admin"> Administrateur </option>
						<option value="gestionnaire"> Gestionnaire </option>
						<option value="etudiant"> Étudiant </option>
					</select>
				</label>
				
				<label class="Email" id="Email"> E-mail :
					<input class="Verify" id="EmailZone" type="mail" name="email" placeholder="Entrez votre mail" pattern="[a-zA-Z0-9._-]+@[a-zA-Z0-9_-]+.[a-zA-Z]{2,}" required>
					<span class="Erreur" id="FormatMail1" aria-live="polite"> <em>Une adresse au format ___@___.__ est attendue</em></span>
				</label>
				
				
				
				<input type="submit" id="Ok" name="ok" value="OK" />
				<input type="reset" id="Reset" name="reset" value="Annuler" />
				
			</form>
			
		</div>
	</body>
	
</html>


<?php include('./footer.php'); ?>


