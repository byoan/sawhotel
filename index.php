<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Analyse Multicritères - RankHotel</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<?php
if (!empty($_POST)) {
    $coefs = array();
    $values = array();
    foreach ($_POST as $key => $value) {
        $coefs[] = (int)$value[0];
        $values[$key] = (int)$value[1];
    }
    $calculatedCoefs = array();
    foreach ($coefs as $key => $value) {
        $calculatedCoefs[$key] = $value/(array_sum($coefs));
    }
    if (array_sum($calculatedCoefs) > 1) {
        die('erreur dans la somme des coefs');
    }

    $db = new PDO('mysql:dbname=sawhotel;host=127.0.0.1', 'root', 'root');
    $query = "DROP VIEW IF EXISTS Hotel_Pond;";
    $statement = $db->prepare($query);
    $statement->execute();

    $query = "CREATE VIEW Hotel_Pond
              as
              SELECT IdH, TRUNCATE($calculatedCoefs[0] * Prix_Norm, 3) Prix_Pond, TRUNCATE($calculatedCoefs[1] * Distance_Norm, 2) Distance_Pond, TRUNCATE($calculatedCoefs[2] * NbEt_Norm, 3) NbEt_Pond
              FROM Hotel_Norm;";
    $statement = $db->prepare($query);
    $statement->execute();

    $query = "SELECT H.IdH, H.Prix, H.Distance, H.NbEt, S.Score
    FROM Hotel H, Hotel_Score S
    WHERE H.IdH = S.IdH
    ORDER BY S.Score Desc;";

    $statement = $db->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo '<div class="container"><h2>Résultats</h2><table class="table">';
    echo '<tr>';
    echo '<th>IdH</th><th>Prix</th><th>Distance</th><th>NbEt</th><th>Score</th>';
    echo '</tr>';
    foreach ($result as $key => $value) {
        echo '<tr>';
        foreach ($value as $id => $val) {
            echo '<td>' . $val . '</td>';
        }
        echo '</tr>';
    }
    echo '</table></div>';
} else {
    include('includes/form.php');
}
