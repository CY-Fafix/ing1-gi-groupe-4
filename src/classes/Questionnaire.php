<?php

class Questionnaire {
    private $id;
    private $questions; // Un tableau d'objets Question
    private $dateDebut;
    private $dateFin;

    // Constructeur
    public function __construct($id, $nom, $questions, $dateDebut, $dateFin) {
        $this->id = $id;
        $this->questions = $questions;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
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
}

?>
