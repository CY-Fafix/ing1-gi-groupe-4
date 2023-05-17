<?php

class Utilisateur {
  // Attributs communs
  protected $nom;
  protected $prenom;
  protected $email;
  protected $motDePasse;
  protected $telephone;
  protected $ville;
  //Rajouter un role;

  // Constructeur
  public function __construct($nom, $prenom, $email, $motDePasse, $telephone, $ville) {
    $this->nom = $nom;
    $this->prenom = $prenom;
    $this->email = $email;
    $this->motDePasse = $motDePasse;
    $this->telephone = $telephone;
    $this->ville = $ville;
  }

  // Getters
  public function getNom() {
    return $this->nom;
  }

  public function getPrenom() {
    return $this->prenom;
  }

  public function getEmail() {
    return $this->email;
  }

  public function getMotDePasse() {
    return $this->motDePasse;
  }

  public function getTelephone(){
    return $this->telephone;
  }

  public function getVille(){
    return $this->ville;
  }

  // Setters
  public function setNom($nom) {
    $this->nom = $nom;
  }

  public function setPrenom($prenom) {
    $this->prenom = $prenom;
  }

  public function setEmail($email) {
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $this->email = $email;
    } else {
      throw new Exception("Email non valide");
    }
  }

  public function setMotDePasse($motDePasse) {
    // Assurez-vous de mettre en œuvre une certaine logique de hachage sécurisée pour le mot de passe
    $this->motDePasse = password_hash($motDePasse, PASSWORD_DEFAULT);
  }

  public function setTelephone($telephone){
    $this->telephone = $telephone;
  }

  public function setVille($ville){
    $this->ville = $ville;
  }
}

?>
