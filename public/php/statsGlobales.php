<?php
    include('./header.php');
    require_once("../../src/controllers/etudiant_controller.php");
    //Normalement on a un id de data challenge en paremetre GET
    $id_dc = 1; 

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link href="../css/stylle.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />
</head>
<body>
    <div class="Main">
    
        <?php
            $controller = new EtudiantController();
            $analyses = $controller->recuperData($id_dc);
            echo "<br>";
            
            //Diagramme sur le nombre de lignes écrites
            $nbLignes = [];
            foreach($analyses as $analyse) {
                array_push($nbLignes, array("y" => $analyse[0], "indexLabel" => $analyse[5]));
            }
            $titre = "Répartition du nombre de lignes écrites par projet";

        ?>
        <script type="text/javascript">
        window.onload = function () {
            var chartData = <?php echo json_encode($nbLignes); ?>;
            var chart = new CanvasJS.Chart("chartContainer",
            {
                title:{
                    text: "<?php echo $titre; ?>" 
                },
                legend: {
                    maxWidth: 350,
                    itemWidth: 120
                },
                data: [
                {
                    type: "pie",
                    showInLegend: true,
                    legendText: "{indexLabel}",
                    dataPoints: chartData
                }
                ]
            });
            chart.render();
        }
        </script>
        <script type="text/javascript" src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
        <div id="chartContainer" style="height: 300px; width: 100%;"></div>
    </div>
</body>
</html>
<?php include('./footer.php'); ?>