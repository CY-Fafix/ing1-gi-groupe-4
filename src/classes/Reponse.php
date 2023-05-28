<?php
class Reponse {
    private $id;
    private $idQuestion;
    private $idEtudiant;
    private $contenu;
    private $note; // Ajout de l'attribut note

    public function __construct($id, $idQuestion, $idEtudiant, $contenu, $note = null) {
        $this->id = $id;
        $this->idQuestion = $idQuestion;
        $this->idEtudiant = $idEtudiant;
        $this->contenu = $contenu;
        $this->note = $note; // Ajout de la note dans le constructeur
    }

    public function getId() {
        return $this->id;
    }

    public function getIdQuestion() {
        return $this->idQuestion;
    }

    public function getIdEtudiant() {
        return $this->idEtudiant;
    }

    public function getContenu() {
        return $this->contenu;
    }

    public function setContenu($contenu) {
        $this->contenu = $contenu;
    }

    public function getNote() {
        return $this->note;
    }

    public function setNote($note) {
        $this->note = $note;
    }
}
?>
