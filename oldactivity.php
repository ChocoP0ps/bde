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
				$photos = array();
				$stmt = $bdd->prepare("SELECT photo.`IS_COUVERTURE`, image.PATH_IMAGE FROM `photo` INNER JOIN image ON photo.ID_IMAGE = image.ID_IMAGE WHERE `ID_ACTIVITY` = :idact");
				$stmt->bindValue(':idact', $_GET['idact']);
				if( $stmt->execute() )
				{
					$photos = $stmt->fetchAll(PDO::FETCH_OBJ);
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
					<?php
					for($i=0; $i<count($photos); $i++)
					{
						if($photos[$i]->IS_COUVERTURE)
						{
							echo '<img src="' . $photos[$i]->PATH_IMAGE . '" class="img-thumbnail picture hidden-xs" />
							<img src="' . $photos[$i]->PATH_IMAGE . '" class="img-thumbnail visible-xs picture_mob" />';
						}
					}
					?>
					<h1><?= $title ?></h1>
					<p><?= $desc ?></p>
				</div>
			</div>
		</div>
	</div>
	<div class="container" style="margin-bottom: 20px;">
		<div class="row">
			<div class="panel panel-activity">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-6">
							<a href="#" class="active" id="see-link">Voir</a>
						</div>
						<div class="col-xs-6">
							<a href="#" class = "" id="add-form-link">Ajouter</a>
						</div>
					</div>
					<hr>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<div id="see">
								<div class='list-group gallery'>
									<?php
									for($i=0; $i<count($photos); $i++)
									{
										echo '<div class="col-sm-4 col-xs-6 col-md-3 col-lg-3">
										<a class="fancybox thumbnail" rel="ligthbox" href="' . $photos[$i]->PATH_IMAGE . '">
											<img class="img-responsive" alt="" src="' . $photos[$i]->PATH_IMAGE . '" width="320" height="320"  />
										</a>
									</div>';
								}
								?>
							</div> <!-- list-group / end -->
						</div>
						<form action="php/addPicture.php" id="add-form" name="add-form" method="post" role="form" style="display: none;" class="col-md-8 col-md-offset-2">
							<div class="form-group">
								<label for="comment">Titre:</label>
								<input type="text" name="title" id="title" tabindex="1" class="form-control">
							</div>
							<div class="form-group">
								<label for="comment">Commentaire:</label>
								<textarea name="comment" class="form-control" rows="5" id="comment" form="add-form"></textarea>
							</div>
							<input type="hidden" name="idact" id="idact" value="<?= $_GET['idact'] ?>">
							<div class="input-group image-preview">
								<input type="text" class="form-control image-preview-filename" disabled="disabled"> <!-- don't give a name === doesn't send on POST/GET -->
								<span class="input-group-btn">
									<!-- image-preview-clear button -->
									<button type="button" class="btn btn-default image-preview-clear" style="display:none;">
										<span class="glyphicon glyphicon-remove"></span> Clear
									</button>
									<!-- image-preview-input -->
									<div class="btn btn-default image-preview-input">
										<span class="glyphicon glyphicon-folder-open"></span>
										<span class="image-preview-input-title">Browse</span>
										<input type="file" accept="image/png, image/jpeg, image/gif" name="input-file-preview"/> <!-- rename it -->
									</div>
								</span>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-6 col-sm-offset-3">
										<input type="submit" name="add-submit" id="add-submit" tabindex="4" class="form-control btn btn-login" value="Ajouter">
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).on('click', '#close-preview', function(){ 
		$('.image-preview').popover('hide');
		$('.image-preview').hover(
			function () {
				$('.image-preview').popover('show');
			}, 
			function () {
				$('.image-preview').popover('hide');
			}
			);    
	});
	$(document).ready( function() {
		$(document).on('change', '.btn-file :file', function() {
			var input = $(this),
			label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
			input.trigger('fileselect', [label]);
		});

		$('.btn-file :file').on('fileselect', function(event, label) {

			var input = $(this).parents('.input-group').find(':text'),
			log = label;

			if( input.length ) {
				input.val(log);
			} else {
				if( log ) alert(log);
			}

		});
		function readURL(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();

				reader.onload = function (e) {
					$('#img-upload').attr('src', e.target.result);
				}

				reader.readAsDataURL(input.files[0]);
			}
		}

		$("#imgInp").change(function(){
			readURL(this);
		}); 	
	});
	$(function() {
		$('#see-link').click(function(e) {
			$("#see").delay(100).fadeIn(100);
			$("#add-form").fadeOut(100);
			$('#add-form-link').removeClass('active');
			$(this).addClass('active');
			e.preventDefault();
		});
		$('#add-form-link').click(function(e) {
			$("#add-form").delay(100).fadeIn(100);
			$("#see").fadeOut(100);
			$('#see-link').removeClass('active');
			$(this).addClass('active');
			e.preventDefault();
		});
		var closebtn = $('<button/>', {
			type:"button",
			text: 'x',
			id: 'close-preview',
			style: 'font-size: initial;',
		});
		closebtn.attr("class","close pull-right");
    // Set the popover default content
    $('.image-preview').popover({
    	trigger:'manual',
    	html:true,
    	title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
    	content: "There's no image",
    	placement:'bottom'
    });
    // Clear event
    $('.image-preview-clear').click(function(){
    	$('.image-preview').attr("data-content","").popover('hide');
    	$('.image-preview-filename').val("");
    	$('.image-preview-clear').hide();
    	$('.image-preview-input input:file').val("");
    	$(".image-preview-input-title").text("Browse"); 
    }); 
    // Create the preview image
    $(".image-preview-input input:file").change(function (){     
    	var img = $('<img/>', {
    		id: 'dynamic',
    		width:250,
    		height:200
    	});      
    	var file = this.files[0];
    	var reader = new FileReader();
        // Set preview image into the popover data-content
        reader.onload = function (e) {
        	$(".image-preview-input-title").text("Change");
        	$(".image-preview-clear").show();
        	$(".image-preview-filename").val(file.name);            
        	img.attr('src', e.target.result);
        	$(".image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
        }        
        reader.readAsDataURL(file);
    });  
});
</script>
</body>
</html>