<?php

session_start();
require_once __DIR__ . '/../../src/controllers/user_controller.php';

$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérifier si le formulaire de connexion a été soumis
    if (isset($_POST['uname'], $_POST['psw'])) {
        $email = $_POST['uname'];
        $password = $_POST['psw'];
        $user = $userController->login($email, $password);
        if ($user !== null) {
            // L'utilisateur est connecté avec succès
            $_SESSION['utilisateur_connecte'] = $user->getEmail();
        } else {
            // Les identifiants sont incorrects
            //echo "E-mail ou mot de passe incorrect.";
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
			<li><a href="../index.php" >Accueil</a></li>
			<!-- <li><a href="Challenges.html">Challenges</a></li> -->
			<li><a href="/public/php/message.php">Contacts</a></li>
			<li><a href="/public/php/inscription.php" class="split">Inscription</a></li>
			<li>
				<!-- Button to open the modal login form -->
        		<button onclick="document.getElementById('id01').style.display='block'" id="loginbtn" class="split">Connexion</button>
			</li>
			<!-- <li><a href="Connexion.html" class="split"> -->
					<!-- <?php
					// if(isset($_SESSION['utilisateur_connecte'])){
					// 	echo('Déconnexion');
					// } else {
					// 	echo('Connexion');
					// }
				?>  -->
			<!-- </a></li> -->
			


		</ul>
	</nav>

</header>

<!-- The Modal -->
<div id="id01" class="modal">
			<span onclick="document.getElementById('id01').style.display='none'"
			class="close" title="Close Modal">&times;</span>

			<!-- Modal Content -->
			<form class="modal-content animate" action="/action_page.php">
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
				</div>

				<div class="containerBas" style="background-color:#f1f1f1">
					<button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Annuler</button>
					<!-- <span class="psw">Forgot <a href="#">password?</a></span> -->
				</div>
			</form>
    	</div>

