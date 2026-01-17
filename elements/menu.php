<div class="container-fluid p-0">
	<nav class="navbar navbar-expand-lg navbar-dark">
		<a class="navbar-brand" href="home.php">
			<h1 class="fatal-logo">FATAL PICARD</h1>
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav ml-auto">
				<li class="nav-item"><a class="nav-link" href="home.php">Acceuil</a></li>
				<?php 
                        if (isset($_SESSION['loggedin']) && isset($_SESSION['adminx']) > 0) {
                            echo '<li class="nav-item"><a class="nav-link" href="admin.php">Administration</a></li>';
                        } 
                        ?>
				<li class="nav-item"><a class="nav-link" href="planet-index.php">Index Planètes</a></li>
				<li class="nav-item"><a class="nav-link" href="outil-rentabilite.php">Outil Rentabilité</a></li>
				<li class="nav-item"><a class="nav-link" href="profil.php">Profil</a></li>
				<li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
			</ul>
		</div>
	</nav>
</div>
