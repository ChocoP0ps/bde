<?php 
$picture = $_FILES['imgInp'];
$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
$extension_upload = strtolower(  substr(  strrchr($picture['name'], '.')  ,1)  );
$title = $_POST['title'];
$comment = $_POST['comment'];
$idact = $_POST['idact'];

if($picture['error'] > 0 || $picture['size'] > 10000000 || !in_array($extension_upload,$extensions_valides))
{
	die(header("location:../oldactivity.php?idact=" . $idact ."&error" . $output));
}

$counter = 0;

$path="C:\wamp64\www\bde\image\\" . $idact . $counter . "." . $extension_upload;

while(file_exists($path)) {
	$counter++;
	$path="C:\wamp64\www\bde\image\\" . $idact . $counter . "." . $extension_upload;
};

move_uploaded_file($picture['tmp_name'], $path);

$sqlPath = "image/" . $idact . $counter . "." . $extension_upload;

$bdd = new PDO('mysql:host=127.0.0.1;dbname=bdebdd;charset=utf8', 'root', '');
$stmt = $bdd->prepare("INSERT INTO `image`(`PATH_IMAGE`, `Title`) VALUES (:pathimg,:title)");
$stmt->bindValue(':pathimg', $sqlPath);
$stmt->bindValue(':title', $title);
$stmt->execute();
$idImage = $bdd->lastInsertId();
$stmt->closeCursor();

$stmt = $bdd->prepare("INSERT INTO `photo`(`ID_ACTIVITY`, `ID_IMAGE`, `IS_COUVERTURE`, `LIKES`) VALUES (:idact,:idimg,:iscouv, 0)");
$stmt->bindValue(':idact', $idact);
$stmt->bindValue(':idimg', $idImage);
$stmt->bindValue(':iscouv', 0);
$stmt->execute();
$stmt->closeCursor();

if($comment != "")
{
	$stmt = $bdd->prepare("INSERT INTO `comment`(`ID_IMAGE`, `ID_USER`, `COMMENTAIRE`) VALUES (:idimg,:iduser,:comment)");
	$stmt->bindValue(':idimg', $idImage);
	$stmt->bindValue(':iduser', $_COOKIE['user']);
	echo $comment;
	$stmt->bindValue(':comment', $comment);
	$stmt->execute();
	$stmt->closeCursor();
}
header("location:../oldactivity.php?idact=" . $idact);
?>