<?php

class Etudiant extends Utilisateur {
  private $niveau;
  private $ecole;

  // Constructeur
  public function __construct($nom, $prenom, $email, $motDePasse, $telephone, $ville, $niveau, $ecole) {
    parent::__construct($nom, $prenom, $email, $motDePasse, $telephone, $ville);
    $this->niveau = $niveau;
    $this->ecole = $ecole;
  }

  // Getters
  public function getNiveau() {
    return $this->niveau;
  }

  public function getEcole() {
    return $this->ecole;
  }

  // Setters
  public function setNiveau($niveau) {
    $this->niveau = $niveau;
  }

  public function setEcole($ecole) {
    $this->ecole = $ecole;
  }

}

?>
