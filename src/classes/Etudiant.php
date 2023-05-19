<?php
require_once 'Utilisateur.php';

class Etudiant extends Utilisateur {
  private $niveau;
  private $ecole;

  // Constructeur
  public function __construct($id, $nom, $prenom, $email, $motDePasse, $telephone, $ville, $role, $niveau, $ecole) {
    parent::__construct($id, $nom, $prenom, $email, $motDePasse, $telephone, $ville, $role);
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
    if (is_string($niveau)) {
      $this->niveau = $niveau;
    } else {
      throw new InvalidArgumentException('Le niveau doit être une chaîne de caractères.');
    }
  }

  public function setEcole($ecole) {
    if (is_string($ecole)) {
      $this->ecole = $ecole;
    } else {
      throw new InvalidArgumentException('L\'école doit être une chaîne de caractères.');
    }
  }

}

?>
