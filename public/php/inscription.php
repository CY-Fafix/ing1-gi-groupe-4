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

                <label id="MotDePasse" class="element"> Mot de passe :
                    <input class="Verify" id="MotDePasseZone" type="password" name="motDePasse" placeholder="Entrez votre mot de passe" required>
                </label>

                <label id="Telephone" class="element"> Téléphone :
                    <input class="Verify" id="TelephoneZone" type="tel" name="telephone" placeholder="Entrez votre numéro de téléphone" required>
                </label>

                <label id="Ville" class="element"> Ville :
                    <input class="Verify" id="VilleZone" type="text" name="ville" placeholder="Entrez votre ville" required>
                </label>

                <label id="Ecole" class="element"> École :
                    <input class="Verify" id="EcoleZone" type="text" name="ecole" placeholder="Entrez le nom de votre école" required>
                </label>
				
				<label id="AnneeEtude" class="element"> Année d'études :
					<select id="AnneeEtudesZone" name="anneeEtudes">
						<option value="L1"> L1 </option>
						<option value="L2"> L2 </option>
						<option value="L3"> L3 </option>
						<option value="M1"> M1 </option>
						<option value="M2"> M2 </option>
						<option value="D"> D </option>
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
