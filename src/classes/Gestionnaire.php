<?php
require_once 'User.php';

class Gestionnaire extends Utilisateur {
    private $entreprise;
    private $debut; //Une date
    private $fin;   //Une date

    // Constructeur
    public function __construct($ID, $nom, $prenom, $email, $motDePasse, $telephone, $ville, $role, $entreprise, $debut, $fin) {
        parent::__construct($ID, $nom, $prenom, $email, $motDePasse, $telephone, $ville, $role);
        $this->entreprise = $entreprise;
        $this->debut = $debut;
        $this->fin = $fin;
    }

    // Getters
    public function getEntreprise() {
        return $this->entreprise;
    }

    public function getDebut() {
        return $this->debut;
    }

    public function getFin() {
        return $this->fin;
    }

    // Setters
    public function setEntreprise($entreprise) {
        // Vérifie que l'entreprise est une chaîne de caractères
        if (is_string($entreprise) && !empty(trim($entreprise))) {
            // Si c'est le cas, assigne la valeur à l'attribut entreprise
            $this->entreprise = $entreprise;
        } else {
            // Si ce n'est pas le cas, lance une exception
            throw new InvalidArgumentException('L\'entreprise doit être une chaîne de caractères non vide.');
        }
    }
    public function setDebut($debut) {
        // Crée une instance de DateTime à partir de la valeur fournie
        $date = DateTime::createFromFormat('d/m/Y', $debut);
        // Vérifie que la date a été créée correctement et que la valeur correspond au format spécifié
        if ($date && $date->format('d/m/Y') === $debut) {
            // Si c'est le cas, assigne la valeur à l'attribut debut
            $this->debut = $debut;
        } else {
            // Si ce n'est pas le cas, lance une exception
            throw new InvalidArgumentException('La date de début doit être une date valide au format JJ/MM/AAAA.');
        }
    }

    public function setFin($fin) {
        // Crée une instance de DateTime à partir de la valeur fournie
        $date = DateTime::createFromFormat('d/m/Y', $fin);
        // Vérifie que la date a été créée correctement et que la valeur correspond au format spécifié
        if ($date && $date->format('d/m/Y') === $fin) {
            // Si c'est le cas, assigne la valeur à l'attribut fin
            $this->fin = $fin;
        } else {
            // Si ce n'est pas le cas, lance une exception
            throw new InvalidArgumentException('La date de fin doit être une date valide au format JJ/MM/AAAA.');
        }
    }

}

?>
