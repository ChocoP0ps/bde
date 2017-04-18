<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title>Bde</title>
	<link rel="stylesheet" type="text/css" href="css/loginstyle.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script
	src="https://code.jquery.com/jquery-3.2.1.min.js"
	integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
	crossorigin="anonymous">
</script>
</head>
<body>
	<?php
	if( isset( $_GET['registerFailed']) && $_GET['registerFailed'])
	{
		switch($_GET['reason'])
		{
			case "passwordDifferent":
				$regisError="Les mots de passe sont différents";
				break;
			case "emailInvalid":
				$regisError="L'adresse Email est invalide";
				break;
			case "emailExist":
				$regisError="L'adresse Email est déjà utilisé";
				break;
			case "imageInvalid":
				$regisError="Image invalide";
				break;
		}
	}
	else if(isset( $_GET['loginFailed']) && $_GET['loginFailed'])
	{
		$logError=true;
	}
	?>
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-login">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-6">
								<a href="#" class="<?php if( !isset($regisError)){ echo "active"; } ?>" id="login-form-link">Login</a>
							</div>
							<div class="col-xs-6">
								<a href="#" class = "<?php if(isset($regisError)){echo "active"; } ?>" id="register-form-link">Register</a>
							</div>
						</div>
						<hr>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<form id="login-form" action="php/login.php" method="post" role="form" style="display: <?php if(isset($regisError)){echo "none"; }else{echo "block";} ?>;">
									<?php if(isset($logError)){echo '<div class="alert alert-danger">Mauvais pseudo ou mauvais mot de passe</div>'; } ?>
									<div class="form-group">
										<input type="text" name="email" id="email" tabindex="1" class="form-control" placeholder="Adresse Email">
									</div>
									<div class="form-group">
										<input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password">
									</div>
									<div class="form-group text-center">
										<input type="checkbox" tabindex="3" class="" name="remember" id="remember">
										<label for="remember"> Remember Me</label>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Log In">
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-lg-12">
												<div class="text-center">
													<a href="http://phpoll.com/recover" tabindex="5" class="forgot-password">Mot de passe oublié ?</a>
												</div>
											</div>
										</div>
									</div>
								</form>
								<form id="register-form" action="php/register.php" method="post" role="form" style="display: <?php if(isset($regisError)){echo "block"; }else{echo "none";} ?>;" enctype="multipart/form-data">
									<?php if(isset($regisError)){echo '<div class="alert alert-danger">' . $regisError . "</div>"; } ?>
									<div class="form-group">
										<input type="text" name="nom" id="nom" tabindex="1" class="form-control" placeholder="Nom" value="">
									</div>
									<div class="form-group">
										<input type="text" name="prenom" id="prenom" tabindex="1" class="form-control" placeholder="Prenom" value="">
									</div>
									<div class="form-group">
										<input type="email" name="email" id="email" tabindex="1" class="form-control <?php if(isset($regisError) && ( $regisError === "L'adresse Email est invalide" || $regisError === "L'adresse Email est déjà utilisé")){echo "error";} ?>" placeholder="Addresse Email" value="">
									</div>
									<div class="form-group">
										<input type="password" name="password" id="password" tabindex="2" class="form-control <?php if(isset($regisError) && $regisError === "Les mots de passe sont différents"){echo "error";} ?>" placeholder="Mot de passe">
									</div>
									<div class="form-group">
										<input type="password" name="confirm-password" id="confirm-password" tabindex="2" class="form-control <?php if(isset($regisError) && $regisError === "Les mots de passe sont différents"){echo "error";} ?>" placeholder="Confirmer le mot de passe">
									</div>
									<div class="form-group">
										<input type="file" name="avatar" id="avatar" tabindex="2" class="form-control <?php if(isset($regisError) && $regisError === "Image invalide"){echo "error";} ?>" placeholder="Avatar">
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-register" value="Register Now">
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
	</div>
	<script>
		$(function() {

			$('#login-form-link').click(function(e) {
				$("#login-form").delay(100).fadeIn(100);
				$("#register-form").fadeOut(100);
				$('#register-form-link').removeClass('active');
				$(this).addClass('active');
				e.preventDefault();
			});
			$('#register-form-link').click(function(e) {
				$("#register-form").delay(100).fadeIn(100);
				$("#login-form").fadeOut(100);
				$('#login-form-link').removeClass('active');
				$(this).addClass('active');
				e.preventDefault();
			});

		});
	</script>
</body>
</html>

