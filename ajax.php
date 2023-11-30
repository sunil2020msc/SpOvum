<?php
$conn = new PDO("mysql:host=localhost;dbname=interview", "root", "");
$graph = $_POST['graph'];

switch ($graph) {
    case 'graph1': {
            $qry = $conn->prepare("SELECT * FROM files");
            $qry->execute();
            $xAxis = [];
            $yAxis = [];
            $i = 0;
            while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
                $i++;
                $xAxis[] = $i;
                $yAxis[] = $row['colA'];
            }
            break;
        }
    case 'graph2': {
            $qry = $conn->prepare("SELECT * FROM files");
            $qry->execute();
            $xAxis = [];
            $yAxis = [];
            $i = 0;
            while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
                $i++;
                $xAxis[] = $i;
                $yAxis[] = $row['colB'];
            }
            break;
        }
    case 'graph3': {
            $qry = $conn->prepare("SELECT AVG(colA) AS average_colA, date FROM files GROUP BY date");
            $qry->execute();
            $xAxis = [];
            $yAxis = [];
            $i = 0;
            while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
                $i++;
                $xAxis[] = date('d-m-Y', strtotime($row['date']));
                $yAxis[] = $row['average_colA'];
            }
            break;
        }
    case 'graph4': {
            $qry = $conn->prepare("SELECT AVG(colB) AS average_colB, date FROM files GROUP BY date");
            $qry->execute();
            $xAxis = [];
            $yAxis = [];
            $i = 0;
            while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
                $i++;
                $xAxis[] = date('d-m-Y', strtotime($row['date']));
                $yAxis[] = $row['average_colB'];
            }
            break;
        }
}

$data = [
    'xAxis' => $xAxis,
    'yAxis' => $yAxis,
];

echo json_encode($data, true);
