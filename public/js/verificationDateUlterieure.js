/* Verification d'une date future entree par l'utilisateur */

erreur = document.getElementsByClassName("Erreur")[0];
erreur.style.visibility = "hidden";



function verifDateUlterieure() {
	const dateUlterieure = document.getElementsByClassName("Verify")[0];
	const dateEtudiee = new Date(dateUlterieure.value);
	const dateDuJour = new Date().getTime();
	
	if (dateEtudiee < dateDuJour) {
		erreur = document.getElementsByClassName("Erreur")[0];
		erreur.style.visibility = "visible";
		return(false);
	} else {
		return(true);
	}
}


