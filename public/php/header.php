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
	<div class="title">
		<h1><em>Projet hackathon</em></h1> 
		<h4>Venez challenger vos données</h4>
	</div>
	

    <nav>
        <ul id="top"> 
            <li><a href="/public/index.php" >Accueil</a></li>

            <?php if (!isset($_SESSION['role'])): ?>
                <li><a href="/public/php/inscription.php" class="split">Inscription</a></li>
                <li>
                    <!-- Button to open the modal login form -->
                    <button onclick="document.getElementById('id01').style.display='block'" id="loginbtn" class="split">Connexion</button>
                </li>
            <?php endif; ?>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'etudiant'): ?>
                <li><a href="/public/php/challenges.php">Challenges</a></li>
                <li><a href="/public/php/deconnexion.php">Déconnexion</a></li>
            <?php endif; ?>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'gestionnaire'): ?>
                <li><a href="/public/php/projets.php">Projets</a></li>
                <li><a href="/public/php/questionnaire.php">Questionnaire</a></li>
                <li><a href="/public/php/deconnexion.php">Déconnexion</a></li>
            <?php endif; ?>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                <li><a href="/public/php/challenges.php">Data Challenges</a></li>
                <li><a href="/public/php/projets.php">Tous les projets</a></li>
                <li><a href="/public/php/utilisateurs.php">Tous les utilisateurs</a></li>
                <li><a href="/public/php/deconnexion.php">Déconnexion</a></li>
            <?php endif; ?>

            <li><a href="/public/php/message.php">Contacts</a></li>
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
        <img src="/public/assets/truc.jpg" alt="Avatar" class="avatar">
    </div>
    <div class="container">
        <label for="uname" class="label"><b>E-mail</b></label>
        <input type="text" class="input" placeholder="Entrer votre e-mail" name="uname" required>

        <label for="psw"class="label"><b>Mot de passe</b></label>
        <input type="password" class="input" placeholder="Entrer votre mot de passe" name="psw" required>

        <button type="submit" class="loginbtn2">Connexion</button>
        <label class="label">
            <input type="checkbox" checked="checked" class="input" name="remember"> Se rappeler de moi
        </label>

        <?php if (!empty($error)): ?>
            <div id="loginError" style="display: none;"><?php echo $error; ?></div>
        <?php endif; ?>
    </div>

    <div class="containerBas" style="background-color:#f1f1f1">
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
