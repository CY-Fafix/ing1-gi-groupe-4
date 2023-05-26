<?php include('./header.php'); ?>


<html lang="fr">
	
	<head>
		<meta charset="UTF-8">
		
		<link href="../css/stylle.css" rel="stylesheet" />
		<link href="../css/inscription.css" rel="stylesheet" />
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />
	
		<title> Inscription </title>
	</head>
	
	
	
	<body>
		<div class="Main">
			
			<p id="Title"> <strong>N'hésitez plus, rejoignez-nous !</strong> </p>
			
			<form id="InscriptionFormulaire" name="inscriptionFormulaire" onsubmit="return verifDateDeNaissance()" method="POST" action="">
				<script type="text/javascript" src="../js/verificationDateDeNaissance.js" defer> </script>

				<p><strong>Inscription</strong></p>
				
				<label id="Nom" class="element"> Nom :
					<input class="Verify" id="NomZone" type="text" name="nom" placeholder="Entrez votre nom" required>
				</label>
				
				<label id="Prenom" class="element"> Prénom :
					<input class="Verify" id="PrenomZone" type="text" name="prenom" placeholder="Entrez votre prénom" required>
				</label>
				
				<!-- Partie retiree car non pertinent que pour la partie creation de compte par un administrateur,
				celui-ci puisse choisir le genre de la personne concernee.
				
				<label id="Genre"> Genre :
					<input class="Verify" id="GenreZone1" type="radio" name="genre" value="Homme" required>
						<label> Homme </label>
					<input class="Verify" id="GenreZone2" type="radio" name="genre" value="Femme" required>
						<label> Femme </label>
					<input class="Verify" id="GenreZone3" type="radio" name="genre" value="Non-binaire" required>
						<label> Non-binaire </label>
				</label>
				-->
				
				<label id="DateNaissance" class="element"> Date de naissance :
					<input class="Verify" id="DateNaissanceZone" type="date" name="dateNaissance" required>
					<br>
					<span class="Erreur" id="FormatDate1" aria-live="polite"> <em>Veuillez entrer une date inférieure à celle du jour (!)</em> </span>
					<br>
				</label>
				
				<label id="AnneeEtude" class="element"> Année d'études :
					<select id="AnneeEtudesZone" name="anneeEtudes">
						<option value="l1"> L1 </option>
						<option value="l2"> L2 </option>
						<option value="l3"> L3 </option>
						<option value="m1"> M1 </option>
						<option value="m2"> M2 </option>
						<option value="d"> D </option>
					</select>
				</label>
				
				<label id="Email" class="element"> E-mail :
					<div>
						<input class="Verify" id="EmailZone" type="mail" name="email" placeholder="Entrez votre mail" pattern="[a-zA-Z0-9._-]+@[a-zA-Z0-9_-]+.[a-zA-Z]{2,}" required>
					</div>
					
				</label>
				<div>
					<span class="Erreur" id="FormatMail1" aria-live="polite"> <em>Une adresse au format ___@___.__ est attendue</em> </span>
				</div>
				
				
				
				<input type="submit" id="valider_inscription" name="valider_inscription" value="S'INSCRIRE" />
				
				
			</form>
			
		</div>
	</body>
	
</html>

<div class="wave">
		
	</div>  
<?php include('./footer.php'); ?>


