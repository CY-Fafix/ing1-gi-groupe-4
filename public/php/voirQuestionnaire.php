<?php
	include('header.php');
	 session_start();
	
	require_once '../../src/classes/Database.php';
	require_once '../../src/classes/Utilisateur.php';
	require_once '../../src/classes/Gestionnaire.php';
	require_once '../../src/classes/Questionnaire.php';
	require_once '../../src/controllers/gestionnaire_controller.php';
    $controller = new GestionnaireController();
    $id_Gestionnaire=$_SESSION["user_id"];
?>

<html lang="fr">
	
	<head>
		<meta charset="UTF-8">
		
		<link href="../css/stylle.css" rel="stylesheet" />
		<link href="../css/questionnaire.css" rel="stylesheet" />
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />
	
		<title> Réponses </title>
	</head>
	
	
	
	<body>
		<div class="Main">
			
			<p id="Title"> <strong>Les réponses</strong> </p>
			
			<form >
				<?php
                    $questions_Id = $controller -> getQuestionIdByIdGest($id_Gestionnaire);
					foreach($questions_Id as $id_Question){
                        $question = $controller->getQuestionContenuById($id_Question['ID']);
                        echo("<p>Question : " .$question. " </p>");
                        $tableau_reponse = $controller->viewResponses($id_Question['ID']);
                        echo('<p> Reponses:');
                        foreach($tableau_reponse as $reponse){
                            echo('<p>' .$reponse["Contenu"]. ' </p>');
                        }
                    }
				?>
			</form>
		</div>
	</body>
	
</html>


<?php include('./footer.php');
ob_end_flush(); ?>
