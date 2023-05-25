public class Fonctions {
	
	//Normaliser le code correspond à retirer les commentaires, les lignes vides et les virgules en fin de ligne inutiles
	/*
	public static void main(String[] args) {
		//Exemple de code python avec des commentaires un peu partout et des ;
		String code =
			"from functools import reduce\n" + 
			"import math\n" +
			"def defGcd(numbers):\n" + 
			"	\n" +
			"	\n" +
			"	test;test;test\n" +
			"	return reduce(math.gcd, numbers)\n" +
			"	#def est en commentaire\n" + 
			"	#on a toujours def en commentaire; lorem ipsum\n" +
			"defGcd([24,108,90]) #def\n" +
			"\n" +
			"def compteur3():\n" + 
			"    i = 0\n" + 
			"    while i < 3:\n" + 
			"        print(i)\n" +
			"\n" +
			"def compteur3():\n" + 
			"    i = 0\n" + 
			"    while i < 3:\n" + 
			"        print(i)\n" + 
			"        i = i + 1" +
			"#le code finit pardef";

		System.out.println("Code normalise :\n-----\n" + normalisation(code) + "\n-----\n");
		System.out.println("Nombre de lignes : " + nbLignes(code));
		System.out.println("Nombre de fonctions : " + nbFonctions(code));
		System.out.println("Nombre de lignes de la plus petite fonction : " + nbMin(code));
		System.out.println("Nombre de lignes de la plus grande fonction : " + nbMax(code));
		System.out.println("Nombre de lignes moyen des fonctions : " + nbMoy(code));
		System.out.println("Nombre d'occurrences de def : " + nbOcc(code, "def"));
		
		//Serveur.main(args);
	}*/
	
	//------------------------------Fonctions auxiliaires------------------------------
	
	//Retourne vrai si le caractère i de str est dans l'alphabet
	private static boolean inAlph(String str, int i) {
		return str.toLowerCase().charAt(i) >= 'a' && str.toLowerCase().charAt(i) <= 'z';
	}
	
	//Retourne le code sans les commentaires
	private static String sansComm(String str) {
		String res = "";	
		int i = 0;	
		int debut = i; //debut du bout de code a copier
		boolean pasFin = false; //ajout de la ligne finale ou non
		while (i < str.length()-1) {
			if (str.substring(i, i+1).equals("#")) { //si on arrive sur un #, on rajoute tout le bout de code qui le precede
				res = res.concat(str.substring(debut, i)); 
				while (i < str.length()-1 && !str.substring(i, i+1).equals("\n")) { //on cherche la fin du commentaire
					i++;
				}
				if (i == str.length()-1) { //si la derniere ligne est un commentaire, on ne la comptabilise pas
					pasFin = true;
				}
				debut = i;
			}
			i++;
		}
		if (!pasFin)
			res = res.concat(str.substring(debut, i+1)); //on rajoute la fin du code si ce n'est pas un commentaire
		
		return res;
	}
	
	//Retourne le code sans ligne vide
	private static String sansLigneVide(String str) {
		String res = "";
		int i = 0;
		int debut = 0;
		while (i < str.length()-1) {
			if (str.substring(i, i+1).equals("\n") || str.substring(i, i+1).equals(";")) { //marquer de fin de ligne, on regarde ce qu'on a entre lui et le precedent marqueur
				boolean queDesEsp = true;
				for (int j=debut+1; j<i; j++) {
					if (!str.substring(j, j+1).equals(" ") && !str.substring(j, j+1).equals("	")) { //on regarde si on a que des espaces et des tabulations
						queDesEsp = false;
					}
				}
				if (!queDesEsp) { //s'il y a au moins un caractere different de espace ou tab, on comptabilise cette ligne
					res = res.concat(str.substring(debut, i));
				}
				debut = i;
			}
			i++;
		}

		res = res.concat(str.substring(debut, i+1)); //on rajoute la fin du code
		
		return res;
	}

	//Normalise le code
	private static String normalisation(String str) {
		return sansLigneVide(sansComm(str));
	}
	
	//Precondition : le premier caractere est 'def' et le code est normalisé
	//Renvoie le nombre de lignes de la premiere fonction trouvee dans str a partir du caractere d'indice depart
	public static int nbLignesDef(String str, int depart) {
		int i = depart; //debut de l'indentation
		int j; //fin de l'indentation
		int compteur = 1; //compte le nombre de lignes
		String indent; //pour savoir si une ligne est dans une fonction, on doit regarder si l'indentation est celle de la première ligne apres le def
		boolean dansDef = true; //vrai tant qu'on est dans la fonction
		
		while (i < str.length()-1 && !(str.substring(i, i+1).equals("\n"))) { //on cherche la fin de la ligne def
			i++;
		}
		i++;
		j = i;
		while (j < str.length()-1 && ((str.substring(j, j+1).equals(" ") || str.substring(j, j+1).equals("	")))) { //on cherche la fin de l'indentation
			j++;
		}
		indent = str.substring(i, j);
		//Tant que les lignes commencent par tabul, on les compte
		while (i < str.length()-1 && dansDef) {
			if (str.substring(i, i+1).equals(";")) {
				compteur++;
			}
			if (str.substring(i, i+1).equals("\n")) { //en fin de ligne on regarde l'indentation suivante
				compteur++;
				if (i+indent.length() < str.length()-1 && !str.substring(i+1, i+1+indent.length()).equals(indent)) {
					dansDef = false;
				}
			}
			i++;
		}
		if (i == str.length()-1 && dansDef) { //si la derniere ligne est dans la fonction
			compteur++;
		}
		return compteur;
	}
	
	
	
	//------------------------------Fonctions principales------------------------------
	
	//Retourne le nombre de lignes du code en paramètre
	public static int nbLignes(String str) {
		int res = 1;
		str = normalisation(str);
		str = str.replaceAll(" ", ""); //enlève les espaces
		str = str.replaceAll("	", ""); //enlève les tabulations
		for (int i=0; i<str.length()-1; i++) {
			//On compte une ligne à partir du moment où on a un marquer de fin de ligne '\n' ou ';'
			if ((str.substring(i, i+1).equals("\n") || str.substring(i, i+1).equals(";"))
				 && !str.substring(i+1, i+2).equals("\n") && !str.substring(i+1, i+2).equals(";")) {
				res += 1;
			}
		}
		return res;
	}
	
	//Retourne le nombre de fonctions du code en paramètre
	public static int nbFonctions(String str) {
		//On compte les 'def' mais on doit vérifier qu'ils soient bien en dehors des commentaires et pas au sein d'un mot plus gros
		int res = 0;
		int i = 0;
		str = normalisation(str);
		while (i<str.length()-2) {
			//Si on lit un def, on vérifie qu'il n'y a pas une lettre de l'alphabet a sa gauche ou a sa droite
			if (str.substring(i, i+3).equals("def") //on lit un def
				&& (i == 0 || !inAlph(str, i-1)) //le caractère avant le def n'est pas dans l'alphabet
				&& (i+3 == str.length() || !inAlph(str, i+3))) { //le caractère apres le def n'est pas dans l'alphabet
				res += 1;
			}
			i++;
		}
		return res;
	}
	
	//Retourne le nombre de lignes minimum des fonctions
	public static int nbMin(String str) {
		int min = -1;
		int i = 0;
		str = normalisation(str);
		while (i<str.length()-2) {
			//Si on lit un def, on vérifie qu'il n'y a pas une lettre de l'alphabet a sa gauche ou a sa droite
			if (str.substring(i, i+3).equals("def") //on lit un def
				&& (i == 0 || !inAlph(str, i-1)) //le caractère avant le def n'est pas dans l'alphabet
				&& (i+3 == str.length() || !inAlph(str, i+3))) { //le caractère apres le def n'est pas dans l'alphabet
				int valeur = nbLignesDef(str, i);
				if (min == -1) {
					min = valeur;
				} else {
					if (valeur < min) {
						min = valeur;
					}
				}
			}
			i++;
		}
		return min;
	}
	
	//Retourne le nombre de lignes maximum des fonctions
	public static int nbMax(String str) {
		int max = -1;
		int i = 0;
		str = normalisation(str);
		while (i<str.length()-2) {
			//Si on lit un def, on vérifie qu'il n'y a pas une lettre de l'alphabet a sa gauche ou a sa droite
			if (str.substring(i, i+3).equals("def") //on lit un def
				&& (i == 0 || !inAlph(str, i-1)) //le caractère avant le def n'est pas dans l'alphabet
				&& (i+3 == str.length() || !inAlph(str, i+3))) { //le caractère apres le def n'est pas dans l'alphabet
				int valeur = nbLignesDef(str, i);
				if (max == -1) {
					max = valeur;
				} else {
					if (valeur > max) {
						max = valeur;
					}
				}
			}
			i++;
		}
		return max;
	}
	
	//Retourne le nombre de lignes moyen des fonctions
	public static float nbMoy(String str) {
		float som = 0;
		float nb = 0;
		int i = 0;
		str = normalisation(str);
		while (i<str.length()-2) {
			//Si on lit un def, on vérifie qu'il n'y a pas une lettre de l'alphabet a sa gauche ou a sa droite
			if (str.substring(i, i+3).equals("def") //on lit un def
				&& (i == 0 || !inAlph(str, i-1)) //le caractère avant le def n'est pas dans l'alphabet
				&& (i+3 == str.length() || !inAlph(str, i+3))) { //le caractère apres le def n'est pas dans l'alphabet
				int valeur = nbLignesDef(str, i);
				som += valeur;
				nb += 1;
			}
			i++;
		}
		if (nb == 0) {
			return 0;
		} else {
			return som/nb;
		}
	}
	
	//Retourne le nombre d'occurrences de mot dans str
	public static int nbOcc(String str, String mot) {
		int res = 0;
		int i = 0;
		while (i<str.length()-mot.length()+1) {
			if (str.substring(i, i+mot.length()).equals(mot)) {//on lit le mot
				res += 1;
			}
			i++;
		}
		return res;
	}
	
}
