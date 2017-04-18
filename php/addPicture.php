<?php 
$picture = $_FILES['imgInp'];
$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
$extension_upload = strtolower(  substr(  strrchr($picture['name'], '.')  ,1)  );
$title = $_POST['title'];
$comment = $_POST['comment'];
$idact = $_POST['idact'];


if($picture['error'] > 0 || $picture['size'] > 10000000 || !in_array($extension_upload,$extensions_valides))
{
	var_dump($_FILES);
	var_dump($_POST);
	//die(header("location:../oldactivity.php?idact=" . $idact ."&error" . $output));
}

$path="C:\wamp64\www\bde\image\\" . $picture['name'];

move_uploaded_file($picture['tmp_name'], $path);

$sqlPath = "image/" . $picture['name'];

$bdd = new PDO('mysql:host=127.0.0.1;dbname=bdebdd;charset=utf8', 'root', '');
$stmt = $bdd->prepare("INSERT INTO `image`(`ID_USERS`, `PATH_IMAGE`, `Title`) VALUES (:iduser,:pathimg,:title)");
$stmt->bindValue(':iduser', $_COOKIE['user']);
$stmt->bindValue(':pathimg', $sqlPath);
$stmt->bindValue(':title', $title);
$stmt->execute();
$idImage = $bdd->lastInsertId();

$stmt = $bdd->prepare("INSERT INTO `photo`(`ID_ACTIVITY`, `ID_IMAGE`, `IS_COUVERTURE`, `LIKES`) VALUES (:idact,:idimg,:iscouv, 0)");
$stmt->bindValue(':idact', $idact);
$stmt->bindValue(':idimg', $idImage);
$stmt->bindValue(':iscouv', false);
$stmt->execute();

if($comment =! "")
{
	$stmt = $bdd->prepare("INSERT INTO `comment`(`ID_IMAGE`, `ID_USER`, `COMMENTAIRE`) VALUES (:idimg,:iduser,:comment)");
	$stmt->bindValue(':idimg', $idImage);
	$stmt->bindValue(':iduser', $_COOKIE['user']);
	$stmt->bindValue(':comment', $comment);
	$stmt->execute();
}

?>