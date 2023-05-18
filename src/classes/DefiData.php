<?php

class DefiData {
    private $id;
    private $libelle;
    private $dateDebut;
    private $dateFin;
    private $idAdmin;
    private $projets; // Liste des projets de données liés (tableau d'objets ProjetData)

    // Constructeur
    public function __construct($id, $libelle, $dateDebut, $dateFin, $idAdmin, $projets) {
        $this->id = $id;
        $this->libelle = $libelle;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->idAdmin = $idAdmin;
        $this->projets = $projets;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getLibelle() {
        return $this->libelle;
    }

    public function getDateDebut() {
        return $this->dateDebut;
    }

    public function getDateFin() {
        return $this->dateFin;
    }

    public function getIdAdmin() {
        return $this->idAdmin;
    }

    public function getProjets() {
        return $this->projets;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setLibelle($libelle) {
        $this->libelle = $libelle;
    }

    public function setDateDebut($dateDebut) {
        $this->dateDebut = $dateDebut;
    }

    public function setDateFin($dateFin) {
        $this->dateFin = $dateFin;
    }

    public function setIdAdmin($idAdmin) {
        $this->idAdmin = $idAdmin;
    }

    public function setProjets($projets) {
        $this->projets = $projets;
    }
}

?>
