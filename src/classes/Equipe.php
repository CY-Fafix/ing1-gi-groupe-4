<?php

class Equipe {
  private $id;
  private $nom;
  private $membres; // Un tableau d'ID d'utilisateur
  private $chefEquipe; // ID de l'utilisateur chef d'équipe

  // Constructeur
  public function __construct($id, $nom, $membres, $chefEquipe) {
    $this->id = $id;
    $this->nom = $nom;
    $this->membres = $membres;
    $this->chefEquipe = $chefEquipe;
  }

  // Getters
  public function getId() {
    return $this->id;
  }

  public function getNom() {
    return $this->nom;
  }

  public function getMembres() {
    return $this->membres; // Dans un contexte réel, vous chargeriez probablement les objets Utilisateur de la base de données ici.
  }

  public function getChefEquipe() {
    return $this->chefEquipe; // Dans un contexte réel, vous chargeriez probablement l'objet Utilisateur de la base de données ici.
  }

  // Setters
  public function setNom($nom) {
    if (is_string($nom)) {
      $this->nom = $nom;
    } else {
      throw new InvalidArgumentException('Le nom de l\'équipe doit être une chaîne de caractères.');
    }
  }

  public function setMembres($membres) {
    if (is_array($membres)) {
      $this->membres = $membres;
    } else {
      throw new InvalidArgumentException('Les membres de l\'équipe doivent être un tableau d\'ID d\'utilisateur.');
    }
  }

  public function setChefEquipe($chefEquipe) {
    if (is_int($chefEquipe)) {
      $this->chefEquipe = $chefEquipe;
    } else {
      throw new InvalidArgumentException('L\'ID du chef de l\'équipe doit être un entier.');
    }
  }
}

?>
