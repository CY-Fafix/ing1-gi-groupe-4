<?php
    include('./header.php');
    require_once("../../src/controllers/etudiant_controller.php");
    
    if (isset($_GET['id'])) {
        $id_dc = $_GET['id'];
    } else {
        echo  
            header('Location: ../index.php');
            exit;
    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link href="../css/stylle.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />
    <link href="../css/statsGlobales.css" rel="stylesheet" />
</head>
<body>
    <div class="Main">
    
        <?php
            //On récupère les analyses de code resneignées dans la base de données
            $controller = new EtudiantController();
            $analyses = $controller->recuperData($id_dc);
            echo "<br>";

            //Données sur le nombre de lignes écrites
            $nbLignes = [];
            foreach($analyses as $analyse) {
                array_push($nbLignes, array("y" => $analyse[0], "indexLabel" => $analyse[5]));
            }
            
            //Données sur le nombre de fonctions écrites
            $nbFonc = [];
            foreach($analyses as $analyse) {
                array_push($nbFonc, array("y" => $analyse[1], "indexLabel" => $analyse[5]));
            }

            //Données sur le nombre min de lignes
            $nbMin = [];
            foreach($analyses as $analyse) {
                array_push($nbMin, array("y" => $analyse[2], "label" => $analyse[5]));
            }

            //Données sur le nombre max de lignes
            $nbMax = [];
            foreach($analyses as $analyse) {
                array_push($nbMax, array("y" => $analyse[3], "label" => $analyse[5]));
            }

            //Données sur le nombre moy de lignes
            $nbMoy = [];
            foreach($analyses as $analyse) {
                array_push($nbMoy, array("y" => $analyse[4], "label" => $analyse[5]));
            }
        ?>

        <!--Tous les script de création de diagramme sont là-->
        <script type="text/javascript">
        window.onload = function () {
            //Script pour le diagramme sur le nombre de lignes écrites
            var chartLignes = new CanvasJS.Chart("chartLignes",
            {
                title:{
                    text: "Répartition du nombre de lignes écrites par équipe" 
                },
                legend: {
                    maxWidth: 350,
                    itemWidth: 120
                },
                data: [
                {
                    type: "doughnut",
                    showInLegend: true,
                    legendText: "{indexLabel}",
                    dataPoints: <?php echo json_encode($nbLignes); ?>
                }
                ]
            });
            chartLignes.render();
            
            //Script pour le diagramme sur le nombre de fonctions écrites
            var chartFonc = new CanvasJS.Chart("chartFonc",
            {
                title:{
                    text: "Répartition du nombre de fonctions écrites par équipe" 
                },
                legend: {
                    maxWidth: 350,
                    itemWidth: 120
                },
                data: [
                {
                    type: "doughnut",
                    showInLegend: true,
                    legendText: "{indexLabel}",
                    dataPoints: <?php echo json_encode($nbFonc); ?>
                }
                ]
            });
            chartFonc.render();

            var chart = new CanvasJS.Chart("chartLongueur",
            {
                title:{
                    text: "Longueur des fonctions écrites par les équipes"
                },
                axisY: {
                    title: "Nombre de lignes"
                },
                data: [
                    {
                        type: "bar",
                        showInLegend: true,
                        legendText: "Plus petite fonction",
                        color: "yellow",
                        dataPoints: <?php echo json_encode($nbMin); ?>
                    },
                    {
                        type: "bar",
                        showInLegend: true,
                        legendText: "Fonction moyenne",
                        color: "orange",
                        dataPoints: <?php echo json_encode($nbMoy); ?>
                    },
                    {
                        type: "bar",
                        showInLegend: true,
                        legendText: "Plus grande fonction",
                        color: "red",
                        dataPoints: <?php echo json_encode($nbMax); ?>
                    }
                ]
            });
            chart.render();
        }
        </script>
        <script type="text/javascript" src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

        <!--Affichage des diagrammes-->
        <div class="cadre"><div id="chartLignes" style="height: 300px; width: 90%; margin: auto;"></div></div>
        <br><br><br>
        <div class="cadre"><div id="chartFonc" style="height: 300px; width: 90%; margin: auto;"></div></div>
        <br><br><br>
        <div class="cadrefinal"><div id="chartLongueur" style="height: 300px; width: 90%; margin: auto;"></div></div>
    </div>
</body>
</html>
<?php include('./footer.php'); ?>