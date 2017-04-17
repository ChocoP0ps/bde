<?php 
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$email = $_POST['email'];
$password = $_POST['password'];
$verifPass = $_POST['confirm-password'];
$avatar = $_FILES['avatar'];
$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
$extension_upload = strtolower(  substr(  strrchr($avatar['name'], '.')  ,1)  );
$image_sizes = getimagesize($avatar['tmp_name']);

if(strcmp($password, $verifPass))
{
	die(header("location:../logreg.php?registerFailed=true&reason=passwordDifferent"));
}
else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
{
	die(header("location:../logreg.php?registerFailed=true&reason=emailInvalid"));
}
else if($avatar['error'] > 0 || $avatar['size'] > 10000000 || !in_array($extension_upload,$extensions_valides) || $image_sizes[0] != 180 || $image_sizes[1] != 180)
{
	die(header("location:../logreg.php?registerFailed=true&reason=imageInvalid" . $info));
}
else
{
	$emailExist = false;
	$bdd = new PDO('mysql:host=127.0.0.1;dbname=bdebdd;charset=utf8', 'root', '');

	$stmt = $bdd->prepare("SELECT * FROM users WHERE email = ?");
	if( $stmt->execute( array( $email ) ) )
	{
		while ( $stmt->fetch() ) 
		{
			$emailExist = true;
		}
	}

	if( $emailExist )
	{
		die(header("location:../logreg.php?registerFailed=true&reason=emailExist"));
	}
	else
	{


		$id=0;
		$stmt = $bdd->prepare("SELECT MAX(`ID_USERS`) as max FROM users");
		if( $stmt->execute() )
		{
			while ( $rep = $stmt->fetch(PDO::FETCH_OBJ) ) 
			{
				$id=$rep->max + 1;
			}
		}
		$stmt->closeCursor();
		$path="C:\wamp64\www\bde\image\avatar\\" . $id . "." . $extension_upload;

		move_uploaded_file($avatar['tmp_name'], $path);

		$sqlPath = "avatar/" . $id . "." . $extension_upload;

		$bdd->exec("INSERT INTO `image`(`ID_USERS`, `PATH_IMAGE`) VALUES (" . $id . ", '" . $sqlPath . "')");

		$idImage = $bdd->lastInsertId();




		$md = md5($password);
		if (!($stmt = $bdd->prepare("INSERT INTO `users`(`ID_USERS`, `ID_GRADE`, `ID_IMAGE`, `NOM_USERS`, `PRENOM_USERS`, `EMAIL`, `PASSWORD`) VALUES (:id, 1, :idImage , :nom, :prenom, :email, :password)"))) {
			echo "Echec de la préparation : (" . $mysqli->errno . ") " . $mysqli->error;
		}
		else if (!$stmt->bindValue(':id', $id))
		{
			echo "Echec lors du liage des paramètres : (" . $stmt->errno . ") " . $stmt->error;
		}
		else if (!$stmt->bindValue(':idImage', $idImage))
		{
			echo "Echec lors du liage des paramètres : (" . $stmt->errno . ") " . $stmt->error;
		}
		else if (!$stmt->bindValue(':nom', $nom)) 
		{
			echo "Echec lors du liage des paramètres : (" . $stmt->errno . ") " . $stmt->error;
		}
		else if (!$stmt->bindValue(':prenom', $prenom)) 
		{
			echo "Echec lors du liage des paramètres : (" . $stmt->errno . ") " . $stmt->error;
		}
		else if (!$stmt->bindValue(':email', $email)) 
		{
			echo "Echec lors du liage des paramètres : (" . $stmt->errno . ") " . $stmt->error;
		}
		else if (!$stmt->bindValue(':password', $md)) 
		{
			echo "Echec lors du liage des paramètres : (" . $stmt->errno . ") " . $stmt->error;
		}
		else if (!$stmt->execute()) 
		{
			print_r($stmt->debugDumpParams());
			echo "<br>Echec lors de l'exécution : (" . $stmt->errorCode() . ") ";
		}
		else
		{
			header("location: ../logreg.php");
		}
	}
}
?>