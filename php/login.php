<?php 
$userName = $_POST['username'];
$password = $_POST['password'];
$remember = $_POST['remember'];

$nameExist = false;
$goodPass = false;
$bdd = new PDO('mysql:host=127.0.0.1;dbname=daltehyondb;port=3306', 'visiteur', 'mdpvisiteur');

$stmt = $bdd->prepare("SELECT * FROM user WHERE username = :username");
$stmt->bindValue(':username', $userName);
$id = 0;

if( $stmt->execute() )
{
	$user = $stmt->fetch(PDO::FETCH_OBJ);
	if($user != null)
	{
		$nameExist = true;
		if( $user->userpassword === md5($password) )
		{
			$goodPass = true;
			$id = $user->iduser;
		}
	}
}

if($nameExist)
{
	if($goodPass)
	{
		session_start();
		ob_start();
		$_SESSION['user'] = $id;
		header("location:../menu.php");
	}
	else
	{
		die(header("location:../index.php?loginFailed=true&md=" . md5($password)));
	}
}
else
{
	die(header("location:../index.php?loginFailed=true"));
}

?>