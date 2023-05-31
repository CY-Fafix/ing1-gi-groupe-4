<?php

class Utilisateur {

  // Attributs communs
  protected $ID; // Ajouté
  protected $nom;
  protected $prenom;
  protected $email;
  protected $motDePasse;
  protected $telephone;
  protected $ville;
  protected $role;


  // Constructeur
  //créer une instance d'Utilisateur sans fournir d'arguments, et ensuite utiliser les méthodes de définition (setters) 
  //pour remplir les informations.
  public function __construct($ID = null, $nom = null, $prenom = null, $email = null, $motDePasse = null, $telephone = null, $ville = null, $role = null) {
    $this->ID = $ID;
    $this->nom = $nom;
    $this->prenom = $prenom;
    $this->email = $email;
    $this->motDePasse = $motDePasse;
    $this->telephone = $telephone;
    $this->ville = $ville;
    $this->role = $role;
}


  public function getID() {
    // Crée une nouvelle instance de Database
    $db = new Database();

    // Se connecte à la base de données
    $db->connect();

    // Récupère l'ID dans la BDD
    $sql = "SELECT ID FROM Utilisateurs WHERE Email='" . $this->email . "'";
    $result = $db->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Ferme la connexion à la base de données
            $db->close();

            return $row["ID"];
        }
    } else {
        // Ferme la connexion à la base de données
        $db->close();

        throw new Exception("Aucun utilisateur avec l'email : " . $this->email);
    }
  }
  
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

  public function getRole(){
    return $this->role;
  }
  // Setters
  public function setNom($nom) {
    $this->nom = $nom;
  }

  public function setID($id) {
    $this->ID = $id;
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
    $this->motDePasse = $motDePasse;
  }

  public function setTelephone($telephone){
    $this->telephone = $telephone;
  }

  public function setVille($ville){
    $this->ville = $ville;
  }
  public function setRole($role){
    $this->role = $role;
  }
}

?>
