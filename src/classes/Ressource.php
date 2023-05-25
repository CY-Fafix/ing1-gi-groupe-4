<?php

class Ressource {
    private $id;
    private $url;
    private $format; //format de la ressource (notebook, pdf, html, video)
    private $titre;
    private $description;

    // Constructeur
    public function __construct($id, $url, $format, $titre, $description) {
        $this->id = $id;
        $this->url = $url;
        $this->format = $format;
        $this->titre = $titre;
        $this->description = $description;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getUrl() {
        return $this->url;
    }

    public function getFormat() {
        return $this->format;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function getDescription() {
        return $this->description;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function setFormat($format) {
        $this->format = $format;
    }

    public function setTitre($titre) {
        $this->titre = $titre;
    }

    public function setDescription($description) {
        $this->description = $description;
    }
}

?>
