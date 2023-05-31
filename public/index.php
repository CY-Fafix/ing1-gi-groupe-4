<?php
require_once __DIR__ . '/../src/classes/Database.php';
require_once __DIR__ . '/../src/classes/DefiData.php';
require_once __DIR__ . '/../src/classes/ProjetData.php';
$db = new Database();
$conn = $db->connect();

$sqlChallenges = "SELECT * FROM DataChallenges";
$resultChallenges = $conn->query($sqlChallenges);

$defisData = array();

if ($resultChallenges->num_rows > 0) {
    while($row = $resultChallenges->fetch_assoc()) {
        $defiData = new DefiData($row['ID'], $row['Libelle'], $row['DateDebut'], $row['DateFin'], $row['ID_Admin'], array());

        $sqlProjets = "SELECT * FROM Projets WHERE ID_DataChallenge = " . $row['ID'];
        $resultProjets = $conn->query($sqlProjets);

        $projetsData = array();
        if ($resultProjets->num_rows > 0) {
            while($projectRow = $resultProjets->fetch_assoc()) {
                $projetData = new ProjetData($projectRow['ID'], $projectRow['Libelle'], $projectRow['Description'], $projectRow['ImageURL'], array(), array());
                array_push($projetsData, $projetData);
            }
        }

        $defiData->setProjets($projetsData);
        array_push($defisData, $defiData);
    }
} else {
    echo "0 results";
}
?>


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
    include ('php/header.php');
    ?>

    <div class="body">
        <div class="title">
            <h1><em>Projet hackathon</em></h1> 
            <h4>Venez challenger vos données</h4>
        </div>
        <div class="presentation">
            <h1 class="titre">
                La reference des Data Challenges :
            </h1>
            <p id="text_presentation">
            Prêt à relever le défi de l'intelligence artificielle en équipe ? <br> <br> Participez à nos Data Challenges sur DataChallenger et gagnez 3000 euros ! 
            Rejoignez notre communauté passionnée, résolvez des problèmes réels avec des données complexes, développez des modèles d'IA innovants et collaborez avec des talents du monde entier. 
            Ne manquez pas cette opportunité unique de mettre vos compétences à l'épreuve et de remporter une récompense incroyable. Rejoignez Hackaton maintenant et préparez-vous à un voyage captivant dans le monde de l'IA.</p>
        </div>

        <div class="all_challenge">
            <h1 class="titre" id="jsp">
                Challenges du moment
            </h1>

                <!-- PHP to dynamically display Data Challenges and their associated projects -->
                <?php
                foreach ($defisData as $defi) {
                    echo '<div class="parag1">';
                    echo '<a href="" class=""> <B>'.$defi->getLibelle().'</B></a>';

                    echo '<div class="projet_data">';
                    echo '<ul class="sujet">';

                    foreach ($defi->getProjets() as $projet) {
                        echo '<li>';
                        echo '<a href="php/project_details.php?id=' . $projet->getId() . '">';                        
                        echo '<article class="card"style="background-image: url('.$projet->getImage().')">';
                        echo '<div class="temporary_text">';
                        echo '</div>';
                        echo '<div class="card_content">';
                        echo '<span class="card_title">'.$projet->getNom().'</span>';
                        echo '<p class="card_description">'.$projet->getDescription().'</p>';
                        echo '</div>';
                        echo '</article>';
                        echo '</a>';
                        echo '</li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>


        </div>
        <br>
        <div class="howTo">
            <h1 class="titre">
                Comment faire ? 
            </h1>
            <p>1. Écoutez les pitchs des porteurs industriels présentant leurs projets et leurs objectifs, soit en personne soit via une vidéo, tout en vous familiarisant avec les données fournies.</p>
            <p>2. Constituez une équipe de 3 à 8 étudiants aux profils complémentaires (maths, informatique, design) et choisissez un projet parmi ceux proposés.</p>
            <p>3. Pendant l'étude, vous aurez accès à des experts de l'association IAPAU ainsi qu'éventuellement aux porteurs de projets pour poser vos questions et obtenir un suivi sur l'étude en cours.</p>
            <p>4. À la fin du Data Challenge, un jury composé d'entrepreneurs, de chercheurs et d'ingénieurs sélectionnera l'équipe gagnante pour chaque projet. </p>
            <p> Rejoignez-nous lors de ces événements excitants et mettez vos compétences en pratique en résolvant des problèmes réels avec des données réelles. Le Data Challenge offre une opportunité unique de travailler en équipe, d'apprendre des experts de l'industrie et de faire valoir votre talent. Préparez-vous à vivre une expérience enrichissante et compétitive en rejoignant le Data Challenge de l'IAPAU !</p>
        </div>
             
    </div>
        

    <div class="wave">
        
    </div>  

    <?php
    include ('php/footer.php');
    ?>  
</body>
</html>
