<?php

class Questionnaire {
    private $id;
    private $nom;
    private $questions; // Un tableau d'objets Question
    private $dateDebut;
    private $dateFin;

    // Constructeur
    public function __construct($id, $nom, $questions, $dateDebut, $dateFin) {
        $this->id = $id;
        $this->nom = $nom;
        $this->questions = $questions;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getQuestions() {
        return $this->questions;
    }

    public function getDateDebut() {
        return $this->dateDebut;
    }

    public function getDateFin() {
        return $this->dateFin;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setQuestions($questions) {
        if (is_array($questions)) {
            $this->questions = $questions;
        } else {
            throw new InvalidArgumentException('Les questions doivent être un tableau d\'objets Question.');
        }
    }

    public function setDateDebut($dateDebut) {
        $this->dateDebut = $dateDebut;
    }

    public function setDateFin($dateFin) {
        $this->dateFin = $dateFin;
    }
}

?>
