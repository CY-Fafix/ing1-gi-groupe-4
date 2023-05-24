<?php

session_start();

?>
	 
<header>
	<div class="title">
		<h1><em>Projet hackathon</em></h1> 
		<h4>Venez challenger vos données</h4>
	</div>
	

	<nav>
		<ul id="top"> 
			<li><a href="Acceuil.html" class="active">Accueil</a></li>
			<li><a href="Challenges.html">Challenges</a></li>
			<li><a href="php/message.php">Contacts</a></li>
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
					<img src="assets/truc.jpg" alt="Avatar" class="avatar">
				</div>

				<div class="container">
					<label for="uname" class="label"><b>Nom d'utilisateur</b></label>
					<input type="text" class="input" placeholder="Enter Username" name="uname" required>

					<label for="psw"class="label"><b>Mot de passe</b></label>
					<input type="password" class="input" placeholder="Enter Password" name="psw" required>

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

