<?php 
$userName = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$verifPass = $_POST['confirm-password'];

if(strcmp($password, $verifPass))
{
	die(header("location:../index.php?registerFailed=true&reason=passwordDifferent"));
}
else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
{
	die(header("location:../index.php?registerFailed=true&reason=emailInvalid&email=$email"));
}
else
{
	$nameExist = false;
	$emailExist = false;
	$bdd = new PDO('mysql:host=127.0.0.1;dbname=daltehyondb;charset=utf8', 'root', '');

	$stmt = $bdd->prepare("SELECT * FROM user WHERE username = ?");
	if( $stmt->execute( array( $userName ) ) )
	{
		while ( $stmt->fetch() ) 
		{
			$nameExist = true;
		}
	}

	$stmt = $bdd->prepare("SELECT * FROM user WHERE useremail = ?");
	if( $stmt->execute( array( $email ) ) )
	{
		while ( $stmt->fetch() ) 
		{
			$emailExist = true;
		}
	}

	if( $nameExist )
	{
		die(header("location:../index.php?registerFailed=true&reason=usernameExist"));
	}
	else if( $emailExist )
	{
		die(header("location:../index.php?registerFailed=true&reason=emailExist"));
	}
	else
	{
		$md = md5($password);
		if (!($stmt = $bdd->prepare("INSERT INTO `user`(`username`, `useremail`, `userpassword`) VALUES (:username, :email, :password)"))) {
			echo "Echec de la préparation : (" . $mysqli->errno . ") " . $mysqli->error;
		}
		else if (!$stmt->bindValue(':username', $userName)) 
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
			header("location: ../index.php");
		}
	}
}
?>