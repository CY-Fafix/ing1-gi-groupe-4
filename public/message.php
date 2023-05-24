<?php include('./header.php'); ?>


<html lang="fr">
	
	<head>
		<meta charset="UTF-8">
		
		<link href="./css/stylle.css" rel="stylesheet" />
		<link href="./css/contact.css" rel="stylesheet" />
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />
	
		<title> Message </title>
	</head>
	
	
	
	<body>
		<div class="Main">
			
			<p id="Title"> <strong>Envoi de message</strong> </p>
			
			<form id="ContactFormulaire" name="contactFormulaire" onsubmit="return verifDateFuture()" method="POST" action="">
			<script type="text/javascript" src="./js/verificationDateFuture.js" defer> </script>
				
				<label id="DateDebut"> Date de début :
					<input class="Verify" id="DateDebutZone" type="date" name="dateDebut" required>
					<span class="Erreur" id="FormatDate1" aria-live="polite"> <em>Veuillez entrer une date supérieure à celle du jour (!)</em> </span>
				</label>
				
				<!-- On connait normalement deja le statut puisque la personne est connectee.
				
				<label id="Statut"> Statut :
					<select id="StatutZone" name="statut">
						<option value="admin"> Administrateur </option>
						<option value="gestionnaire"> Gestionnaire </option>
						<option value="etudiant"> Étudiant </option>
					</select>
				</label>
				-->
				
				<label class="Email" id="Email"> E-mail :
					<input class="Verify" id="EmailZone" type="mail" name="email" placeholder="Entrez votre mail" pattern="[a-zA-Z0-9._-]+@[a-zA-Z0-9_-]+.[a-zA-Z]{2,}" required>
					<span class="Erreur" id="FormatMail1" aria-live="polite"> <em>Une adresse au format ___@___.__ est attendue</em></span>
				</label>
				
				<label id="Sujet"> Sujet :
					<input class="Verify" id="SujetZone" type="text" name="objet" placeholder="Objet de votre demande" required>
				</label>
				
				<label id="Contenu"> Contenu :
					<textarea class="Verify" id="ContenuZone" name="objet" rows="16" cols="80" wrap=hard placeholder="Entrez votre message ici" spellcheck="False" required></textarea>
				</label>
				
				
				
				<input type="submit" id="Ok" name="ok" value="OK" />
				<input type="reset" id="Reset" name="reset" value="Annuler" />
				
			</form>
			
		</div>
	</body>
	
</html>


<?php include('./footer.php'); ?>


