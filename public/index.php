<!DOCTYPE html >
<html xml:lang="fr" lang="fr">
<meta charset="UTF-8">
<head>

    <link href="css/stylle.css" rel="stylesheet" />
    <link
    href="https://fonts.googleapis.com/css?family=Open+Sans"
    rel="stylesheet" />

    <title>Projet hackathon</title>
</head>

<body>

    <?php

    include ('../src/views/header.php');
    ?>

	<div class="body">
		<div class="parag1">
			
			<a href="" class=""> <B>Data Challenge</B></a>
			<p>Lorem ipsum kdpoefjpzok</p>
			
			<div class="projet_data">
				<a href="">
					<img src="assets/truc.jpg" class="challenge" alt="bonbon miel">
				</a>
				<a href="">
					<img src="assets/truc.jpg" class="challenge" alt="bonbon miel">
				</a>
				<a href="">
					<img src="assets/truc.jpg" class="challenge" alt="bonbon miel">
				</a>
			</div>
		</div>
		
		<div class="parag1">
			
			<a href="" class=""> <B>Data Challenge</B></a>
			<p>Lorem ipsum2 kdpoefjpzok</p>
			
			<div class="projet_data">
				<a href="">
					<img src="assets/truc.jpg" class="challenge" alt="bonbon miel">
				</a>
				<a href="">
					<img src="assets/truc.jpg" class="challenge" alt="bonbon miel">
				</a>
				<a href="">
					<img src="assets/truc.jpg" class="challenge" alt="bonbon miel">
				</a>
			</div>
		</div>
		
		<div class="parag1">
			
			<a href="" class=""> <B>Data Challenge</B></a>
			<p>Lorem ipsum kdpoefjpzok</p>
			
			<div class="projet_data">
				<a href="">
					<img src="assets/truc.jpg" class="challenge" alt="bonbon miel">
				</a>
				<a href="">
					<img src="assets/truc.jpg" class="challenge" alt="bonbon miel">
				</a>
				<a href="">
					<img src="assets/truc.jpg" class="challenge" alt="bonbon miel">
				</a>
			</div>
		</div>
		<br>
		
	

        <!-- Button to open the modal login form -->
        <button onclick="document.getElementById('id01').style.display='block'">Login</button>

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
                <label for="uname"><b>Username</b></label>
                <input type="text" placeholder="Enter Username" name="uname" required>

                <label for="psw"><b>Password</b></label>
                <input type="password" placeholder="Enter Password" name="psw" required>

                <button type="submit">Login</button>
                <label>
                    <input type="checkbox" checked="checked" name="remember"> Remember me
                </label>
            </div>

            <div class="container" style="background-color:#f1f1f1">
                <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
                <span class="psw">Forgot <a href="#">password?</a></span>
            </div>
        </form>
    </div>


</body>
