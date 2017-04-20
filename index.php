<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>BDE EXIA AIX</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="css/ListStyle.css">
	<!-- Latest compiled and minified JavaScript -->
	<script
	src="https://code.jquery.com/jquery-3.2.1.min.js"
	integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
	crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
		<header>
		<div class="container-fluid">
			<h1>BDE Cesi.Exia Aix</h1>
		</div>
		<nav class="navbar navbar-default" role="navigation">
			<div class="navbar-header">
				<a class="navbar-brand" href="index">Accueil</a>
			</div>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li class="test"><a href="shop">Boutique</a></li>
					<li><a href="form"><i class="fa fa-plus-circle" aria-hidden="true"></i>
						Activité</a></li>
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
						<li><a class="menu" href="contact.html">Contacts</a></li>
						<li><a href="logreg.php">S'enregistrer / Se connecter</a></li>
					</ul>
				</div>
			</nav>
		</header>
	<div class="container" style="margin-top: 70px;">
		<div class="row form-group">
			<div class="col-xs-12 col-md-offset-2 col-lg-offset-2 col-md-8 col-lg-8" id="cont-act">
				<div class="panel-heading">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab1default" data-toggle="tab" id="all">Toutes</a></li>
						<li><a href="#tab2default" data-toggle="tab" id="passed">Passées</a></li>
						<li><a href="#tab3default" data-toggle="tab" id="confirmed">Confirmées</a></li>
						<li><a href="#tab3default" data-toggle="tab" id="proposed">Proposées</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready( function() {
		getActivities(-1);
	});

	$('#all').click(function(){
		getActivities(-1);
	});

	$('#passed').click(function(){
		getActivities(3);
	});

	$('#confirmed').click(function(){
		getActivities(2);
	});

	$('#proposed').click(function(){
		getActivities(1);
	});

	function getActivities(state) {
		console.log("etat : " + state);
		$.post( "php/getActivities.php", { etat : state }, function( data ) {
			var activities = JSON.parse(data);
			$('.panel-act').fadeOut(600, function() { 
				$(this).remove(); 
			});
			for(var i = 0; i < activities.length; i++)
			{
				$("#cont-act").append($('<div class="panel panel-default panel-act"> <div class="panel-image"> <img src="' + activities[i].PATH_IMAGE + '" class="panel-image-preview" /> <h4>' + activities[i].TITRE + '</h4> <p>' + activities[i].DESCRIPTION + '</p> </div> <div class="panel-footer clearfix"> <p>Participants: <span>15</span></p> </div> </div>').hide().fadeIn(1200));
			}
		});
	}


	$('.toggler').click(function() {
		var tog = $(this);
		var secondDiv = tog.parent().prev();
		var firstDiv = secondDiv.prev();
		firstDiv.children('p').toggleClass('hide');
		secondDiv.toggleClass('hide');
		tog.toggleClass('fa fa-chevron-up fa fa-chevron-down');
		return false;
	});

	$('.comsys').click(function() {
		var togCmt = $(this);
		togCmt.toggleClass('active');
		var panelFooterDiv = togCmt.parent();
		var panelDefaultDiv = panelFooterDiv.parent();
		var panelCmtsDiv = panelDefaultDiv.next();
		panelCmtsDiv.slideToggle('hide');
		return false;
	});
</script>
</body>
</html>