<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>BDE EXIA AIX</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="css/ActivityStyle.css">
	<!-- Latest compiled and minified JavaScript -->
	<script
	src="https://code.jquery.com/jquery-3.2.1.min.js"
	integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
	crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<script type="text/javascript" src="js/datepicker.js"></script>
	<script type="text/javascript" src="js/date.format.js"></script>
	<link rel="stylesheet" type="text/css" href="css/base.css" />
	<link rel="stylesheet" type="text/css" href="css/clean.css" />
</head>
<body>
	<header>
		<div class="container-fluid">	</div>
		<nav class="navbar navbar-default" role="navigation">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<a class="navbar-brand" href="#">Accueil</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li class="test"><a href="#">Boutique</a></li>
					<li><a href="#">Activité</a></li>
				</ul>
				<div class="col-sm-3 col-md-3"> 
					<form class="navbar-form" role="search">
						<div class="input-group">
							<input type="text" class="form-control" placeholder="Recherchez une activité" name="q">
							<div class="input-group-btn">
								<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
							</div>
						</div>
					</form>
				</div>
				<ul class="nav navbar-nav navbar-right">
					<li><a class="menu" href="#">Contacts</a></li>
					<li><a href="#">S'enregistrer / Se connecter</a></li>
				</ul>
			</div><!-- /.navbar-collapse -->
		</nav>
	</header>
	<?php
	if(isset($_GET['idact']))
	{
		$bdd = new PDO('mysql:host=127.0.0.1;dbname=bdebdd;port=3306, charset=utf8', 'root', '');
		$stmt = $bdd->prepare("SELECT * FROM activities WHERE ID_ACTIVITY = :idact");
		$stmt->bindValue(':idact', $_GET['idact']);
		if( $stmt->execute() )
		{
			if($activity = $stmt->fetch(PDO::FETCH_OBJ))
			{
				$title = $activity->TITRE;
				$desc = $activity->DESCRIPTION;
				$age = $activity->AGE_MIN;
				$price = $activity->PRIX_ACTIVITY;
				$etat = $activity->ID_ETAT;
				$required = $activity->REQUIS;
				$min = $activity->PERSONNES_MIN;
				$stmt = $bdd->prepare("SELECT photo.`IS_COUVERTURE`, image.PATH_IMAGE FROM `photo` INNER JOIN image ON photo.ID_IMAGE = image.ID_IMAGE WHERE `ID_ACTIVITY` = :idact && photo.`IS_COUVERTURE`=1");
				$stmt->bindValue(':idact', $_GET['idact']);
				if( $stmt->execute() )
				{
					$photo = $stmt->fetch(PDO::FETCH_OBJ);
					$path = $photo->PATH_IMAGE;
				}
				$stmt = $bdd->prepare("SELECT users.NOM_USERS, users.PRENOM_USERS FROM `participation` INNER JOIN users ON users.ID_USERS = participation.ID_USERS WHERE participation.ID_ACTIVITY= :idact");
				$stmt->bindValue(':idact', $_GET['idact']);
				if( $stmt->execute() )
				{
					$participants = $stmt->fetchAll(PDO::FETCH_OBJ);
				}
			}
			else
			{
				$title = "Pas d'activité";
				$desc = "Pas de description";
			}
		}
	}
	?>

	<div class="container" style="margin-top: 50px;">
		<div class="row panel">
			<div class="col-md-8  col-xs-12">
				<div class="header">
					<img src="<?= $path ?>" class="img-thumbnail picture hidden-xs" />
					<img src="<?= $path ?>" class="img-thumbnail visible-xs picture_mob" />
					<h1><?= $title ?></h1>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row panel descr-panel">
			<div class="col-md-6 descr">
				<p><?= $desc ?></p>
			</div>
			<div class="col-md-6 list-descr">
				<dl>
					<dt>Age :</dt>
					<dd><?= $age ?> ans</dd>
					<dt>Prix :</dt>
					<dd><?= $price ?>€</dd>
					<dt>Requis :</dt>
					<dd><?= $required ?></dd>
				</dl>
			</div>
		</div>
	</div>
	<div class="container" style="margin-top: -20px;">
		<div class="row panel">
			<div class="col-md-6 col-md-offset-3 col-xs-12 text-center ">
				<p class="col-md-12" id="simple-calendar"></p>
			</div>
		</div>
	</div>
	<div class="container participants">
		<div class="row panel">
			<div class="col-md-12  col-xs-12 ">
				<h1 class="text-center">Participants :</h1>
				<ul class="list-participants col-md-6 col-md-offset-3">
					<?php
					for($i = 0; $i < count($participants); $i++)
					{
						if($i % 2 == 0)
						{
							echo "<li class='left'>" . $participants[$i]->NOM_USERS . " " . $participants[$i]->PRENOM_USERS . "</li>";
						}
						else
						{
							echo "<li class='right'>" . $participants[$i]->NOM_USERS . " " . $participants[$i]->PRENOM_USERS . "</li><br>";
						}
					}
					?>
				</ul>
			</div>
		</div>
	</div>


	<div class="container footer navbar-fixed-bottom">
		<div class="row">
			<button class="btn btn-danger btn-lg btn-block col-md-12 participate" id="send"><?php if($etat == 1){ echo "Voter"; }else{ echo "Participer"; } ?></button>
		</div>
	</div>
	<script>
		
		$(document).ready(function () {

			$('#simple-calendar').DatePicker({
				mode: 'multiple',
				inline: true,
				date: new Date()
				<?php 
				if($etat==2)
				{
					echo ", onChange: function() { $('#simple-calendar').DatePickerSetDate(new Date(), true); }";
				}
				?>
			});
		});

		$('#send').click(function(){
			console.log("vote");
			var arraydate = new Array();
			arraydate = $('#simple-calendar').DatePickerGetDate()[0];
			for (var i = 0; i < arraydate.length; i++) {
				arraydate[i] = dateFormat(arraydate[i], "yyyy-mm-dd");
			}
			$.post( "php/vote.php", { idact : <?= $_GET['idact'] ?>, dates : arraydate, state : <?= $etat ?> }, function( data ) {
				location.href = "index.html";
			});
		})

	</script>
</body>
</html>