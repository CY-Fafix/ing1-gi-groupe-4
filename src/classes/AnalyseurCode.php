<?php

class AnalyseurCode {
    private $id;
    private $nbLignes;
    private $nbFonc;
    private $nbMin;
    private $nbMax;
    private $nbMoy;
    private $idEquipe;

    //Constructeur
    public function __construct($id, $nbLignes, $nbFonc, $nbMin, $nbMax, $nbMoy, $idEquipe) {
        $this->id = $id;
        $this->nbLignes = $nbLignes;
        $this->nbFonc = $nbFonc;
        $this->nbMin = $nbMin;
        $this->nbMax = $nbMax;
        $this->nbMoy = $nbMoy;
        $this->idEquipe = $idEquipe;
    }

    //Getters
    public function getID() {
        return $this->id;
    }

    public function getNbLignes() {
        return $this->nbLignes;
    }

    public function getNbFonc() {
        return $this->nbFonc;
    }

    public function getNbMin() {
        return $this->nbMin;
    }

    public function getNbMax() {
        return $this->nbMax;
    }

    public function getNbMoy() {
        return $this->nbMoy;
    }

    public function getIdEquipe() {
        return $this->idEquipe;
    }
}