<?php 
$bdd = new PDO('mysql:host=127.0.0.1;dbname=bdebdd;port=3306; charset=utf8', 'root', '');

if($_POST['etat'] == -1)
{
	$stmt = $bdd->query('SELECT activities.*, image.PATH_IMAGE FROM activities INNER JOIN photo ON activities.ID_ACTIVITY = photo.ID_ACTIVITY INNER JOIN image ON photo.ID_IMAGE = image.ID_IMAGE WHERE photo.IS_COUVERTURE=1');
}
else
{
	$stmt = $bdd->query('SELECT activities.*, image.PATH_IMAGE FROM activities INNER JOIN photo ON activities.ID_ACTIVITY = photo.ID_ACTIVITY INNER JOIN image ON photo.ID_IMAGE = image.ID_IMAGE WHERE photo.IS_COUVERTURE=1 && activities.ID_ETAT=' . $_POST['etat']);
}

$activity = $stmt->fetchAll(PDO::FETCH_OBJ);

$json = json_encode($activity);

echo $json;
?>