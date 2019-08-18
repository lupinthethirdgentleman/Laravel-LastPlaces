<?php
$mysqli = new MySQLi('localhost','lst_user','last@123321','last_pls') or die($mysqli->connect_error);

	?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Galería de tribus - West Africa DMC</title>
 <meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta name="keywords" content="Last Places DMC" />
<meta name="description" content="Last Places DMC Template for personal use only." />
<meta name="author" content="Manish Bajpai - Codexious Digital Agency" />
	
<!----Stylesheets start---->	
<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/fontawesome/fontawesome.min.css">
<!-- Google Fonts -->
		<link href="https://fonts.googleapis.com/css?family=Muli:200,300,400,600,700,800,900" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Dosis:300,600,700" rel="stylesheet">

		<!-- Plugins -->
		<link rel="stylesheet" href="css/plugins.css" />

    
	
</head>
<body>
	<!---Header Section start--->
	<header>
		<div class="container">
			<div class="row">
				<div class="col-sm-4">
					<div class="logo">
					<img src="img/lastplaces.jpg" alt="Last Places Logo">
					</div>			
				</div>
				<div class="col-sm-8 text-center my-auto">
					<div class="row">
						<div class="col-md-11 text-center">
							<div class="contact-info">
						<p><a href="tel:+34 935 439 601"><i class="fas fa-phone-alt"></i> +34 935 439 601</a></p>
						<p><a href="mailto:Info@lastplacesdmc.com"><i class="fas fa-envelope-open-text"></i> Info@lastplacesdmc.com</a></p>
					</div>
						</div>
						<div class="col-md-1">
							<select class="selectpicker" data-width="fit" onchange="location = this.value;">
								<option value="/lastplacesdmc/westafrica/" data-content='<span class="flag-icon flag-icon-us"></span> English'>English</option>
								<option value="/lastplacesdmc/westafrica/es/"  data-content='<span class="flag-icon flag-icon-mx"></span> Español' selected>Español</option>
							</select>	  
						</div>
					</div>
					
					<nav class="navbar navbar-expand-lg">
					  <div class="container">

						<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						  <span class="icon-bar"><i class="fas fa-bars"></i></span>
						</button>

						<!-- navbar links -->
						<div class="collapse navbar-collapse" id="navbarSupportedContent">
						  <ul class="navbar-nav ml-center">
							<li class="nav-item">
							  <a class="nav-link" href="/lastplacesdmc/westafrica/es/index.php">West Africa</a>
							</li>

							<li class="nav-item">
							  <a class="nav-link" href="/lastplacesdmc/westafrica/es/gallery-tribes.php">Tribus</a>
							</li>

							<li class="nav-item">
							  <a class="nav-link" href="/lastplacesdmc/westafrica/es/gallery-nature.php">Naturaleza</a>
							</li>

						   <li class="nav-item">
							  <a class="nav-link" href="/lastplacesdmc/westafrica/es/contact.php">Contacto</a>
							</li>
						 </ul>
						</div>
					  </div>
				  </nav>
					
					
				</div>
			</div>
		</div>
	</header>
	<!---Header Section Ends--->
	
	<!---Page Content Section start--->
	<section id="sites">
		<div class="container">
			<div class="row text-center">
				<div class="col-sm-12">
					<h1>Galería de tribus</h1>
				</div>
			</div>
		<!----Images gallery--->
			<?php
			$table='photos_country';
			$result = $mysqli->query("SELECT * FROM $table WHERE country_id=14") or die($mysqli->error);
			    echo '<div class="row spr-1 text-center">';
				while ($data = $result->fetch_assoc()){
					echo '<div class="col-sm-4"><div class="top-gallery">';
					echo "<img src='http://lastplaces.net/uploads/photogallery/{$data['image']}' alt='{$data['name']}' />";
					echo "</div></div>";
			    		
				}    
			    
			        echo "</div>";
				
			?>
		</div>
	</section>

	<!----Footer Start--->
	
	<footer>
		<div class="widget-area text-center">
			<h3>Tenemos una manera única de satisfacer tus expectativas aventureras.</h3>
			<h4><a href="tel:+34 935 439 601">Llamada +34 935 439 601</a></h4>
			
			<!--Social Icons Comment start
			<ul>
				<li><a href="#" class="social-icons"> <i class="fab fa-facebook-f"></i> </a></li>
				<li><a href="#" class="social-icons"> <i class="fab fa-youtube"></i> </a></li>
				<li><a href="#" class="social-icons"> <i class="fab fa-instagram"></i> </a></li>
			</ul> 
			Social Icons Comment end-->

		</div>
		<div class="copyright text-center">Copyright &copy; 2019 Last Places DMC - Todos los derechos reservados</div>
	</footer>
	<!----Footer end--->


<!-----Bootstrap & FontAwesome JS Library start----->
<script src="https://kit.fontawesome.com/727bc2ed09.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

<!-----Bootstrap JS Library end----->

</body>
</html>
