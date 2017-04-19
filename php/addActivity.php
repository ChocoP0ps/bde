<?php 
$title = $_POST['title'];
$des = $_POST['description'];
$price = $_POST['price'];
$min = $_POST['min'];
$req = $_POST['required'];
$age = $_POST['age'];
$date = $_POST['date'];
$couv = $_FILES['image'];
$iduser = $_COOKIE['user'];
$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
$extension_upload = strtolower(  substr(  strrchr($couv['name'], '.')  ,1)  );

$dates = explode(",", $date);

if($couv['error'] > 0 || $couv['size'] > 10000000 || !in_array($extension_upload,$extensions_valides))
{
	die(header("location:../form.html?error=picture"));
}
else if($title == "")
{
	die(header("location:../form.html?error=title"));
}
else if($price == "")
{
	$price = 0;
}
else if($min == "")
{
	$min = 10;
}
else if($age == "")
{
	$age = 18;
}

$bdd = new PDO('mysql:host=127.0.0.1;dbname=bdebdd;charset=utf8', 'root', '');
if (!($stmt = $bdd->prepare("INSERT INTO `activities`(`ID_ETAT`, `TITRE`, `DESCRIPTION`, `PRIX_ACTIVITY`, `PERSONNES_MIN`, `AGE_MIN`, `REQUIS`) VALUES (:etat, :title, :descr, :price, :min, :age, :req)"))) {
	echo "Echec de la préparation : (" . $mysqli->errno . ") " . $mysqli->error;
}
$stmt->bindValue(':etat', 1);
$stmt->bindValue(':title', $title);
$stmt->bindValue(':descr', $des);
$stmt->bindValue(':price', $price);
$stmt->bindValue(':min', $min);
$stmt->bindValue(':age', $age);
$stmt->bindValue(':req', $req);
if (!$stmt->execute()) 
{
	print_r($stmt->debugDumpParams());
	echo "<br>Echec lors de l'exécution : (" . $stmt->errorCode() . ") ";
}
$idact = $bdd->lastInsertId();
$stmt->closeCursor();

$counter = 0;

$path="C:\wamp64\www\bde\image\\" . $idact . $counter . "." . $extension_upload;

while(file_exists($path)) {
	$counter++;
	$path="C:\wamp64\www\bde\image\\" . $idact . $counter . "." . $extension_upload;
};

move_uploaded_file($couv['tmp_name'], $path);

$sqlPath = "image/" . $idact . $counter . "." . $extension_upload;

$stmt = $bdd->prepare("INSERT INTO `image`(`PATH_IMAGE`, `Title`) VALUES (:pathimg,:title)");
$stmt->bindValue(':pathimg', $sqlPath);
$stmt->bindValue(':title', $title);
$stmt->execute();
$idImage = $bdd->lastInsertId();
$stmt->closeCursor();

$stmt = $bdd->prepare("INSERT INTO `photo`(`ID_ACTIVITY`, `ID_IMAGE`, `IS_COUVERTURE`, `LIKES`) VALUES (:idact,:idimg,:iscouv, 0)");
$stmt->bindValue(':idact', $idact);
$stmt->bindValue(':idimg', $idImage);
$stmt->bindValue(':iscouv', 1);
$stmt->execute();
$stmt->closeCursor();

for($i = 0; $i < count($dates); $i++)
{
	$stmt = $bdd->prepare("INSERT INTO `dates`(`DATE`) VALUES (:dat)");
	$stmt->bindValue(':dat', $dates[$i]);
	$stmt->execute();
	$stmt->closeCursor();

	$stmt = $bdd->prepare("INSERT INTO `dates_activity`(`DATE`, `ID_ACTIVITY`) VALUES (:dat, :idact)");
	$stmt->bindValue(':dat', $dates[$i]);
	$stmt->bindValue(':idact', $idact);
	$stmt->execute();
	$stmt->closeCursor();
}

header("location:../list_activities.html");

?>