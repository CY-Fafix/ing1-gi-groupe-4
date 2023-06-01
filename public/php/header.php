<?php

session_start();    
require_once __DIR__ . '/../../src/controllers/user_controller.php';

$userController = new UserController();
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['uname'], $_POST['psw'])) {
        $email = $_POST['uname'];
        $password = $_POST['psw'];
        $user = $userController->login($email, $password);
        if ($user !== null) {
			//connexion bien effectuée.
			/*
				Les données de session sont : 
				$_SESSION['user_id'];
            	$_SESSION['role'];
			*/
        } else {
            $error = "E-mail ou mot de passe incorrect.";
        }
    }
}
?>
<header>
    <nav>
        <ul id="top"> 
            <li><a href="/public/index.php" >Accueil</a></li>

            <?php if (!isset($_SESSION['role'])): ?>
            	<!-- Rediriger vers l'accueil une fois connecte -->
                <li><a href="/public/php/inscription.php" class="split">Inscription</a></li>
                <li>
                    <!-- Button to open the modal login form -->
                    <button onclick="document.getElementById('id01').style.display='block'" id="loginbtn" class="split">Connexion</button>
                </li>
            <?php endif; ?>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'Etudiant'): ?>
                <li><a href="/public/php/challenges.php">Challenges</a></li> <!-- Voir les challenges ou il est inscrit -->
                <li><a href="/public/php/equipe.php">Equipes</a></li> <!-- Voir son équipe si pas d'équipe, peut créer-->
                <li><a href="/public/php/profile.php">Profil</a></li> <!-- Voir son profil/modifier infos -->
                <li><a href="/public/php/deconnexion.php">Déconnexion</a></li> 
            <?php endif; ?>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'Gestionnaire'): ?>
                <li><a href="/public/php/projets.php">Projets</a></li> <!-- Le gestionnaire voit tous les projets -->
                <li><a href="/public/php/choixNbQuestions.php">Créer Questionnaire</a></li> <!-- Permet de créer un questionnaire -->
                <li><a href="/public/php/voirQuestionnaire.php">Voir Reponses</a></li><!-- Permet de voir les réponses -->
                <li><a href="/public/php/profile_gestionnaire.php">Profile</a></li> <!-- Voir son profil/modifier infos -->
                <li><a href="/public/php/deconnexion.php">Déconnexion</a></li> 
            <?php endif; ?>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'Admin'): ?>
                <li><a href="/public/php/update_challenges.php">Data Challenges</a></li> <!--Adrien Permet de voir/modifier tous les DataChallenges -->
                <li><a href="/public/php/projets.php">Tous les projets</a></li> <!-- Permet de voir/modifier tous les projets -->
                <li><a href="/public/php/utilisateurs.php">Tous les utilisateurs</a></li> <!-- Ana Permet de voir/modifier tous les utilisateurs -->
                <li><a href="/public/php/modifRessource.php">Ajouter Ressource</a></li> <!-- Permet de void/modifier les resosurces -->
                <li><a href="/public/php/deconnexion.php">Déconnexion</a></li>
            <?php endif; ?>

            <li><a href="/public/php/message.php">Contact</a></li>
        </ul>
    </nav>

</header>
<!-- The Modal -->
<div id="id01" class="modal">
    <span onclick="document.getElementById('id01').style.display='none'"
    class="close" title="Close Modal">&times;</span>

    <!-- Modal Content -->
	<form class="modal-content animate" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <div class="imgcontainer">
    <img src="https://img.freepik.com/vecteurs-libre/homme-affaires-caractere-avatar-isole_24877-60111.jpg?w=360" class="Avatar" alt="bonbon miel">
    </div>
    <div class="container">
        <label for="uname" class="label"><b>E-mail</b></label>
        <input type="text" class="input" placeholder="Entrer votre e-mail" name="uname" required>

        <label for="psw"class="label"><b>Mot de passe</b></label>
        <input type="password" class="input" placeholder="Entrer votre mot de passe" name="psw" required>

        <button type="submit" class="loginbtn2">Connexion</button>
        <!-- <label class="label">
            <input type="checkbox" checked="checked" class="input" name="remember"> Se rappeler de moi
        </label> -->

        <?php if (!empty($error)): ?>
            <div id="loginError" style="display: none;"><?php echo $error; ?></div>
        <?php endif; ?>
    </div>

    <div class="containerBas" style="background-color:white">
        <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Annuler</button>
    </div>
</form>

</div>
<script>
window.onload = function() {
    var errorDiv = document.getElementById('loginError');
    if (errorDiv) {
        document.getElementById('id01').style.display='block';
        alert(errorDiv.innerText);
    }
}
</script>
