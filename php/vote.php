<?php 
if(isset($_COOKIE['user']))
{
	$iduser = $_COOKIE['user'];
}
else
{
	//die(header("location:../activity.php?idact=" . $idact . "&error=notconnected"));
}

$idact = $_POST['idact'];
$etat = $_POST['state'];
$dates = $_POST['dates'];

$bdd = new PDO('mysql:host=127.0.0.1;dbname=bdebdd;port=3306; charset=utf8', 'root', '');

$particip = false;
$stmt = $bdd->prepare("SELECT * FROM `participation` WHERE ID_USERS = :iduser && ID_ACTIVITY = :idact");
$stmt->bindValue(':iduser', $iduser);
$stmt->bindValue(':idact', $idact);
$stmt->execute();
while ( $stmt->fetch() ) 
{
	$particip = true;
}
$stmt->closeCursor();


if($particip)
{
	//die(header("location:../activity.php?idact=" . $idact . "&error=alreadyvoted"));
}
else
{
	$stmt = $bdd->prepare("INSERT INTO `participation`(`ID_USERS`, `ID_ACTIVITY`, `IS_CREATEUR`) VALUES (:iduser, :idact, :creat)");
	$stmt->bindValue(':iduser', $iduser);
	$stmt->bindValue(':idact', $idact);
	$stmt->bindValue(':creat', 0);
	$stmt->execute();
	$stmt->closeCursor();

	for($i = 0; $i < count($dates); $i++)
	{
		$stmt = $bdd->prepare("INSERT INTO `dates_activity`(`DATE`, `ID_ACTIVITY`, `ID_USER`) VALUES (:dat, :idact, :iduser)");
		$stmt->bindValue(':dat', $dates[$i]);
		$stmt->bindValue(':idact', $idact);
		$stmt->bindValue(':iduser', $iduser);
		$stmt->execute();
		$stmt->closeCursor();

		$exist=false;
		$stmt = $bdd->prepare("SELECT * FROM `dates` WHERE `DATE`=:dat");
		$stmt->bindValue(':dat', $dates[$i]);
		$stmt->execute();
		while ( $stmt->fetch() ) 
		{
			$exist = true;
		}
		$stmt->closeCursor();

		if(!$exist)
		{
			$stmt = $bdd->prepare("INSERT INTO `dates`(`DATE`) VALUES (:dat)");
			$stmt->bindValue(':dat', $dates[$i]);
			$stmt->execute();
		}
	}
	//header("location:../index.html");
}

?>
