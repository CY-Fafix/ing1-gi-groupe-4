<?php
// Inclusion des fichiers nécessaires pour accéder à la base de données et gérer les utilisateurs
require_once '../classes/Database.php';
require_once '../classes/Utilisateur.php';
require_once 'user_controller.php';

class GestionnaireController extends UserController{
    private $conn;
    private $table_name = "Utilisateurs";
    private $table_questionnaire = "Questionnaires";
    private $table_questions = "Questions";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
        if ($this->conn) {
            echo "Database connection successful.";
        } else {
            echo "Database connection failed.";
        }
    }
    public function createQuestion(Questionnaire $questionnaire){
        try{
            $sql = "INSERT INTO " . $this->table_questions . " (Contenu, ID_Questionnaire) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            if($stmt === false) {
                die('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $Contenu = $questionnaire->getQuestions();
            $ID_Questionnaire = $questionnaire-> getId();
            $stmt->bind_param("si",$Contenu, $ID_Questionnaire);
            if ($stmt->execute()){
                return true;
            } else {
                return false;
            }
        } catch(Exception $e) {
            echo "Error: " . $e->getMessage();
        }

    }
    public function createQuestionnaire(Questionnaire $questionnaire, $id_Gest){
        try{
            $sql = "INSERT INTO " . $this->table_questionnaire . " (DateDebut, DateFin, ID_Gestionnaire) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            if($stmt === false) {
                die('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $dateDebut = $questionnaire->getDateDebut();
            $dateFin = $questionnaire -> getDateFin();
            $ID_Gestionnaire = $id_Gest;
            $stmt->bind_param("ddi", $dateDebut, $dateFin,$ID_Gestionnaire);
            if ($stmt->execute()) {
                $value=$this->createQuestion($questionnaire);
                return $value;
            } else {
                return false;
            }
        } catch(Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }   
}

?>