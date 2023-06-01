/* Verification d'une date future entree par l'utilisateur */

erreur1 = document.getElementsByClassName("Erreur")[0];
erreur1.style.visibility = "hidden";

erreur2 = document.getElementsByClassName("Erreur")[1];
erreur2.style.visibility = "hidden";



function verifDateUlterieure() {
	const dateUlterieure1 = document.getElementsByClassName("Verify")[0];
	const dateEtudiee1 = new Date(dateUlterieure1.value);
	
	const dateUlterieure2 = document.getElementsByClassName("Verify")[1];
	const dateEtudiee2 = new Date(dateUlterieure2.value);
	
	const dateDuJour = new Date().getTime();
	
	if (dateEtudiee1 < dateDuJour) {
		erreur1.style.visibility = "visible";
	} else {
		erreur1.style.visibility = "hidden";
	}
	
	
	if (dateEtudiee2 < dateDuJour) {
		erreur2.style.visibility = "visible";
	} else {
		erreur2.style.visibility = "hidden";
	}
	
	
	if ((dateEtudiee1 < dateDuJour) || (dateEtudiee2 < dateDuJour)) {
		return(false);
	} else {
		return(true);
	}
}


