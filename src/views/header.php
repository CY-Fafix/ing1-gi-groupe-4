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
			<li><a href="Contacts.html">Contacts</a></li>
			<li><a href="Connexion.html" class="split">
					<?php
					if(isset($_SESSION['utilisateur_connecte'])){
						echo('Déconnexion');
					} else {
						echo('Connexion');
					}
				?> 
			</a></li>
			


		</ul>
	</nav>

</header>

