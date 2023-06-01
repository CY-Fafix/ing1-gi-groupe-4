<?php

class Questionnaire {
    private $id;
    private $questions; // Un tableau d'objets Question
    private $dateDebut;
    private $dateFin;
    private $idProjet;

    // Constructeur
    public function __construct($id, $questions, $dateDebut, $dateFin, $idProjet) {
        $this->id = $id;
        $this->questions = $questions;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->idProjet = $idProjet;
    }

    // Getters
    public function getId() {
        return $this->id;
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

    public function getIdProjet() {
        return $this->idProjet;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
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

    public function setIdProjet($idProjet) {
        $this->idProjet = $idProjet;
    }
}

?>
