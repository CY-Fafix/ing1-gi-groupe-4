<?php

class Message {
  private $ID;
  private $emetteur; // Un objet Utilisateur
  private $contenu;
  private $dateEnvoi;
  private $equipe; // Un objet Equipe

  // Constructeur
  public function __construct($ID, $emetteur, $contenu, $equipe) {
    $this->ID = $ID;
    $this->emetteur = $emetteur;
    $this->contenu = $contenu;
    $this->dateEnvoi = new DateTime();
    $this->equipe = $equipe;
  }

  // Getters
  public function getID() {
    return $this->ID;
  }

  public function getEmetteur() {
    return $this->emetteur;
  }

  public function getContenu() {
    return $this->contenu;
  }

  public function getDateEnvoi() {
    return $this->dateEnvoi->format('d/m/Y H:i:s');
  }

  public function getEquipe() {
    return $this->equipe;
  }

  // Setters
  public function setID($ID) {
    $this->ID = $ID;
  }

  public function setEmetteur($emetteur) {
    if ($emetteur instanceof Utilisateur) {
      $this->emetteur = $emetteur;
    } else {
      throw new InvalidArgumentException('L\'emetteur doit être un objet Utilisateur.');
    }
  }

  public function setContenu($contenu) {
    if (is_string($contenu)) {
      $this->contenu = $contenu;
    } else {
      throw new InvalidArgumentException('Le contenu du message doit être une chaîne de caractères.');
    }
  }

  public function setEquipe($equipe) {
    if ($equipe instanceof Equipe) {
      $this->equipe = $equipe;
    } else {
      throw new InvalidArgumentException('L\'equipe doit être un objet Equipe.');
    }
  }

  //La date d'envoi est définie lors de la création du message, donc pas de setter pour elle.
}

?>
