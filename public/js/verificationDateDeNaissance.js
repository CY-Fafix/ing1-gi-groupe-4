/* Verification de la date de naissance entree par l'utilisateur */

erreur = document.getElementsByClassName("Erreur")[0];
erreur.style.visibility = "hidden";



function verifDateDeNaissance() {
	const dateDeNaissance = document.getElementsByClassName("DateNaissanceVerif")[0];
	const dateNaissance = new Date(dateDeNaissance.value);
	const dateDuJour = new Date().getTime();
	
	if (dateNaissance > dateDuJour) {
		erreur = document.getElementsByClassName("Erreur")[0];
		erreur.style.visibility = "visible";
		return(false);
	} else {
		return(true);
	}
}


