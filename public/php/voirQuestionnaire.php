<?php
	include('header.php');
	session_start();
	
	require_once '../../src/classes/Database.php';
	require_once '../../src/classes/Utilisateur.php';
	require_once '../../src/classes/Gestionnaire.php';
	require_once '../../src/classes/Questionnaire.php';
	require_once '../../src/controllers/gestionnaire_controller.php';
	$controller = new GestionnaireController();
	$id_Gestionnaire = $_SESSION["user_id"];
?>

<html lang="fr">
	
	<head>
		<meta charset="UTF-8">
		<link href="../css/stylle.css" rel="stylesheet" />
		<link href="../css/questionnaire.css" rel="stylesheet" />
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />
		<title>Réponses</title>
	</head>
	
	<body>
		<div class="Main">
			<p id="Title"><strong>Les réponses à vos questions :</strong></p>
			<form method="POST">
				<?php
					$_SESSION['idEquipes'] = array();
					$_SESSION['idQuestions'] = array();
					$i = 1;
					$questions_Id = $controller->getQuestionIdByIdGest($id_Gestionnaire);
					foreach ($questions_Id as $id_Question) {
						$question = $controller->getQuestionContenuById($id_Question['ID']);
						echo "<p>Question : " . $question . "</p>";
						$tableau_reponse = $controller->viewResponses($id_Question['ID']);
						echo '<p>Reponses:</p>';
						foreach ($tableau_reponse as $reponse) {
							array_push($_SESSION['idEquipes'], $reponse["ID_Equipe"]);
							array_push($_SESSION['idQuestions'], $reponse["ID_Question"]);
							echo '<p>' . $reponse["Contenu"] . '</p>';
							?>
							<fieldset>
								<legend>Sélectionnez une note</legend>
								<?php for ($j = 0; $j <= 4; $j++) { ?>
									<input type="radio" id="note-<?php echo $i; ?>-<?php echo $j; ?>" name="note-<?php echo $i; ?>" value="<?php echo $j; ?>" >
									<label for="note-<?php echo $i; ?>-<?php echo $j; ?>"><?php echo $j; ?></label> 
								<?php } ?>
							</fieldset>
							<?php
							$i += 1;
						}
					}
				?>
				<input type="submit" value="Envoyer" class="envoi">
				<input type="reset" value="Annuler" class="annuler">
			</form>
			<?php
				if ($_SERVER['REQUEST_METHOD'] === 'POST') {
					var_dump($_POST);
					$i = 1;
					foreach ($questions_Id as $id_Question) {
						foreach ($tableau_reponse as $reponse) {
							if (isset($_POST['note-' . $i])) {
								$note = (int)$_POST['note-' . $i];
								$idEquipe = $_SESSION['idEquipes'][$i - 1];
								$idQuestion = $_SESSION['idQuestions'][$i - 1];
								$success=$controller->updateScore($idEquipe, $idQuestion, $note);
								$_SESSION['LE_SUCCES']=$success;
							}
						}
						$i += 1;
					}
				}
			?>
		</div>
	</body>
	
</html>

<?php
	include('footer.php');
ob_end_flush(); ?>
