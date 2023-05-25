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
    private $table_reponse = "Reponses";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
        if ($this->conn) {
            echo "Database connection successful.";
        } else {
            echo "Database connection failed.";
        }
    }
    //fonction appelée par la fonction createQuestionnaire
    public function createQuestion(Questionnaire $questionnaire){
        try{
            // ajout des questions dans la base de données et les lie au questionnaire correspondant
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
            //ajout des informations liées au questionnaire en base de données (dates, id)
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
    //fonction appelée par la fonction deleteQuestionnaire
    public function deleteQuestion($ID_Questionnaire){
        try{
            //suppression des questions de la base de donnée
            $sql = "DELETE FROM". $this->table_questions. "WHERE ID_Questionnaire =". $ID_Questionnaire;
            $stmt = $this->conn->prepare($sql);
            if($stmt === false) {
                die('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            if ($stmt->execute()){
                return true;
            } else {
                return false;
            }
            
        } catch(Exception $e) {
            echo "Error: " . $e->getMessage();
        }   
    }
    public function deleteQuestionnaire($ID_Questionnaire){
        try{
            //suppression des informations du questionnaire de la base de donnée
            $sql = "DELETE FROM". $this->table_questionnaire. "WHERE ID =". $ID_Questionnaire;
            $stmt = $this->conn->prepare($sql);
            if($stmt === false) {
                die('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            if ($stmt->execute()){
                $value=$this->deleteQuestion($ID_Questionnaire);
                return $value;
            } else {
                return false;
            }
              
        } catch(Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function updateScore($ID_Equipe, $ID_Question,$nouvelle_Note){
        try{
            //suppression des informations du questionnaire de la base de donnée
            $sql = "UPDATE". $this->table_reponse. "SET Note=".$nouvelle_Note."WHERE ID_Equipe =". $ID_Equipe."AND WHERE ID_Question = ".$ID_Question;
            $stmt = $this->conn->prepare($sql);
            if($stmt === false) {
                die('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            if ($stmt->execute()){
                return true;
            } else {
                return false;
            }
              
        } catch(Exception $e) {
            echo "Error: " . $e->getMessage();
        }

    }
    public function viewResponses(){
        try{
            //récupération des réponses dans un tableau en sortie
            $sql="SELECT Contenu FROM". $this->table_reponse;
            $res = mysqli_query($this->conn, $sql) or die('Request error : '.$sql);
            if (mysqli_num_rows($res) > 0) {
                while($row = mysqli_fetch_assoc($res)) {
                    $tableau[] =  $row;
                }
            return($tableau);
            } else {
                return false;
            }
              
        } catch(Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function sendMessages(){
        // A FAIRE
    }
}

?>