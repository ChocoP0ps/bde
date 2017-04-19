<?php 
$bdd = new PDO('mysql:host=127.0.0.1;dbname=bdebdd;port=3306, charset=utf8', 'root', '');

if($_GET['etat'] == -1)
{
	$stmt = $bdd->query('SELECT * FROM activities');
}
else
{
	$stmt = $bdd->query('SELECT * FROM activities WHERE ID_ETAT = ' . $_GET['etat']);
}

$activity = $stmt->fetchAll(PDO::FETCH_OBJ);

$json = json_encode($activity);

echo $json;
?>