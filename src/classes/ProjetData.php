<?php

/*Classe qui correspond à un projet dans un Data challenge*/
class ProjetData {
    private $id;
    private $nom;
    private $description;
    private $image; // Chemin de l'image
    private $contacts; // Tableau d'objets Contact
    private $ressources; // Tableau d'objets Ressource

    // Constructeur
    public function __construct($id, $nom, $description, $image, $contacts, $ressources) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->image = $image;
        $this->contacts = $contacts;
        $this->ressources = $ressources;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getImage() {
        return $this->image;
    }

    public function getContacts() {
        return $this->contacts;
    }

    public function getRessources() {
        return $this->ressources;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function setContacts($contacts) {
        if (is_array($contacts)) {
            $this->contacts = $contacts;
        } else {
            throw new InvalidArgumentException('Les contacts doivent être un tableau d\'objets Contact.');
        }
    }

    public function setRessources($ressources) {
        if (is_array($ressources)) {
            $this->ressources = $ressources;
        } else {
            throw new InvalidArgumentException('Les ressources doivent être un tableau d\'objets Ressource.');
        }
    }
}

?>
