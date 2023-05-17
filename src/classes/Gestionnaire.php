<?php

class Gestionnaire extends Utilisateur {
    private $entreprise;
    private $debut; //Une date
    private $fin;   //Une date

    // Constructeur
    public function __construct($nom, $prenom, $email, $motDePasse, $telephone, $ville, $entreprise, $debut, $fin) {
        parent::__construct($nom, $prenom, $email, $motDePasse, $telephone, $ville);
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
        $this->entreprise = $entreprise;
    }

    public function setDebut($debut) {
        $this->debut = $debut;
    }

    public function setFin($fin) {
        $this->fin = $fin;
    }
}

?>
