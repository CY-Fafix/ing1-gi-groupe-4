<?php
require_once 'Utilisateur.php';

class Administrateur extends Utilisateur {

    //Des informations en plus ?

  // Constructeur
  public function __construct($nom, $prenom, $email, $motDePasse, $telephone, $ville, $role) {
    parent::__construct($nom, $prenom, $email, $motDePasse, $telephone, $ville, $role);
  }
  
}

?>
