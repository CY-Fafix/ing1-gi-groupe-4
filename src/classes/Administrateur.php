<?php

class Administrateur extends Utilisateur {

    //Des informations en plus ?

  // Constructeur
  public function __construct($nom, $prenom, $email, $motDePasse, $telephone, $ville) {
    parent::__construct($nom, $prenom, $email, $motDePasse, $telephone, $ville);
  }
  
}

?>
