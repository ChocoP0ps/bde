<?php 
$email = $_POST['email'];
$password = $_POST['password'];
if(isset($_POST['remember']))
{
	$remember = $_POST['remember'];
}
else
{
	$remember = "off";
}

$nameExist = false;
$goodPass = false;
$bdd = new PDO('mysql:host=127.0.0.1;dbname=bdebdd;port=3306', 'root', '');

$stmt = $bdd->prepare("SELECT * FROM users WHERE EMAIL = :email");
$stmt->bindValue(':email', $email);
$id = 0;

if( $stmt->execute() )
{
	$user = $stmt->fetch(PDO::FETCH_OBJ);
	if($user != null)
	{
		$nameExist = true;
		if( $user->PASSWORD === md5($password) )
		{
			$goodPass = true;
			$id = $user->ID_USERS;
		}
	}
}

if($nameExist)
{
	if($goodPass)
	{
		setcookie("user", $id,time()+86400, null, null, false, true);
		header("location:../list_activities.html");
	}
	else
	{
		die(header("location:../logreg.php?loginFailed=mdp"));
	}
}
else
{
	die(header("location:../logreg.php?loginFailed=email"));
}

?>