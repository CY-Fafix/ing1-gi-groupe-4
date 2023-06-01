<?php include('./header.php'); ?>


<html lang="fr" style="background-color:#147cb4;">
	
	<head>
		<meta charset="UTF-8">
		
		<link href="../css/stylle.css" rel="stylesheet" />
		<link href="../css/message.css" rel="stylesheet" />
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />
	
		<title> Message </title>
	</head>
	
	
	
	<body>
		<div class="Main">
			<div id="Message_contact">
				<p id="message1"> TOUJOURS A VOTRE ECOUTE </p>
				<p id="message2">Contactez-nous!</p>
			</div>
			<div class="contact_fiche">
			
				<p id="Title"> <strong>Envoi de message</strong> </p>
				<div id="carte">
			
					<form id="ContactFormulaire" class="element" name="contactFormulaire" onsubmit="return verifDateFuture()" method="POST" action="">
						<script type="text/javascript" src="../js/verificationDateUlterieure.js" defer> </script>
						
						<!-- On connait normalement deja le statut puisque la personne est connectee.
						
						<label id="Statut"> Statut :
							<select id="StatutZone" name="statut">
								<option value="admin"> Administrateur </option>
								<option value="gestionnaire"> Gestionnaire </option>
								<option value="etudiant"> Étudiant </option>
							</select>
						</label>
						-->
						
						<label class="Email" id="Email" class="element" > E-mail :
							<input class="Verify" id="EmailZone" type="mail" name="email" placeholder="Entrez votre mail" pattern="[a-zA-Z0-9._-]+@[a-zA-Z0-9_-]+.[a-zA-Z]{2,}" required>
							<br>
							<span class="Erreur" id="FormatMail1" aria-live="polite"> <em>Une adresse au format ___@___.__ est attendue</em> </span>
						</label>
						
						<br>
						<label id="Sujet" class="element"> Sujet :
							<input class="Verify" id="SujetZone" type="text" name="SujetZone" placeholder="Objet de votre demande" required>
						</label>

						<br>
						
						<label id="Contenu" class="element"> Contenu :
							<br>
							<textarea class="Verify" id="ContenuZone" name="ContenuZone" rows="16" cols="80" wrap=hard placeholder="Entrez votre message ici" spellcheck="False" required> </textarea>
						</label>
						
						<br>
						<div class="form_btn">
							<input type="submit" id="Ok" name="ok" value="OK" />
							<input type="reset" id="Reset" name="reset" value="Annuler" />
						</div>
					</form>
				</div>

			</div>
			
		</div>
	</body>
	
</html>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$email = $_POST["email"];
	$objet = $_POST["SujetZone"];
	$contenu = $_POST["ContenuZone"];
	$_SESSION['contenu']=$contenu;
	$_SESSION['emailpls']=$email;
	
	$controller = new userController();
	$controller->contact($email,$objet,$contenu);

}
 include('./footer.php'); ?>


