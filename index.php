<?php
require_once 'inc/init.php';
$title = 'Accueil';
$agence = '';

?></main><?php


if (!empty($_POST) && isset($_POST['action']) && $_POST['action'] == 'reserver' ) {
    $nb_de_jour_timestamp = strtotime($_POST['datef']) - strtotime($_POST['dated']);
    $nb_de_jour = $nb_de_jour_timestamp / 86400;
    $prix_total = $nb_de_jour * $_POST['prix_journalier'];
    $_SESSION['panier'] = $_POST;
    $_SESSION['panier']['prix_total'] = $prix_total;
    header('location:' . URL . 'panier.php');
    exit();
}

require_once 'inc/header.php';


?>
<!--== Slider Area Start ==-->
<section id="slider-area" style="margin-top:56px">
    <!--== slide Item One ==-->
    <div class="single-slide-item overlay">
        <div class="container" style="min-height:1000px;">
            <div class="slider-right-text">
                <?php if (!empty($_SESSION['message'])) {
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                } ?>
                <h1>VEVILLE</h1>
                <p>LA LOCATION A PETIT PRIX !</p>
                <div class="about-btn">
                    <a href="#louez">Louer un véhicule dès maintenant !</a>
                </div>
            </div>
        </div>
    </div>
    <!--== slide Item One ==-->
</section>
<!--== Slider Area End ==-->

<section class="section-padding" id="louez">
<div class="col-lg-12">
    <div class="section-title  text-center">
        <h2>LOUER UN VEHICULE</h2>
        <span class="title-line"><i class="fa fa-car"></i></span>
    </div>
</div>

<div class="container">
<form method="POST" action="#louez">
    <input type="hidden" name="action" value="affichage">
    <div class="row">
        <label style="margin-top:5px;" for="agence">Sélectionner votre agence</label>
        <select name="agence" id="agence" class="form-control col-2 mx-2">
            <?php
            $resultat = execReq("SELECT * FROM agences");
            while ($agence = $resultat->fetch()) {
                ?><option value="<?=$agence['idagences']?>" <?= (isset($_POST['agence']) && $_POST['agence'] == $agence['idagences']) ? 'selected' : '' ?>><?=$agence['titre']?></option><?php
            }
            ?>
        </select>
        <label style="margin-top:5px;" for="date_heure_debut">Début de la loc</label>
        <input class="form-control col-2 mx-2" type="text" name="date_heure_debut" autocomplete="off" id="date_heure_debut" value="<?=(isset($_POST['date_heure_debut']) ? $_POST['date_heure_debut'] : '')?>">
        <label style="margin-top:5px;" for="date_heure_fin">Fin de la loc</label>
        <input class="form-control col-2 mx-2" type="text" name="date_heure_fin" autocomplete="off" id="date_heure_fin" value="<?=(isset($_POST['date_heure_fin']) ? $_POST['date_heure_fin'] : '')?>">
        <script>
        $(function() {
            $( "#date_heure_debut" ).datepicker({
                minDate: 0,
                onClose: function( selectedDate ) {$( "#date_heure_fin" ).datepicker( "option", "minDate", selectedDate );}
            });
            $( "#date_heure_fin" ).datepicker({
                onClose: function( selectedDate ) {$( "#date_heure_debut" ).datepicker( "option", "maxDate", selectedDate );}
            });
        });
        </script>
        <input type="submit" value="Choisir" class="btn btn-primary ml-2">
    </div>
</form>
<?php
    
if (isset($_POST['action']) && $_POST['action'] == 'affichage') {

        $agencereq = execReq("SELECT * FROM agences WHERE idagences=:id", array(
            'id' => $_POST['agence'],
        ));
        $agenceinfo = $agencereq->fetch();
        $agence = '
        <div class="col-12">
            <h4>Agence sélectionnée</h4>
            <div class="card mb-3" style="max-width: 540px;">
                <div class="row no-gutters">
                    <div class="col-md-4">
                        <img src="' . URL . 'photo/agences/' . $agenceinfo['photo'] . '" class="card-img" alt="' . $agenceinfo['titre'] . '">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">' . $agenceinfo['titre'] . '</h5>
                            <p class="card-text">' . $agenceinfo['description'] . '</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ';

        $exp_debut = explode('/', $_POST['date_heure_debut']);
        $date_debut = $exp_debut[2] . '-' . $exp_debut[0] . '-' . $exp_debut[1];
        
        $exp_fin = explode('/', $_POST['date_heure_fin']);
        $date_fin = $exp_fin[2] . '-' . $exp_fin[0] . '-' . $exp_fin[1];
        ?>
        <div class="container mt-5 mb-5">
        <div class="row">
        <?php
        // ici ma jointure pour comparer les dates et n'afficher que les véhicules dsiponibles
        $vehicule = execReq("SELECT * FROM vehicule WHERE agences_idagences=:id AND NOT EXISTS (SELECT * FROM commande WHERE vehicule.idvehicule=commande.vehicule_idvehicule AND date_heure_depart < '$date_fin' AND date_heure_fin > '$date_debut' )", array(
            'id' => $_POST['agence'],
        ));

        if ($vehicule->rowCount() != 0) {
            while ($infoVehicule = $vehicule->fetch()) { ?>
                <div class="card col-4" style="width: 18rem;">
                    <img src="<?=URL . 'photo/vehicule/' . $infoVehicule['photo']?>" class="card-img-top" alt="voiture">
                    <div class="card-body">
                        <h5 class="card-title"><?=$infoVehicule['titre']?></h5>
                        <p class="card-text">description : <?=$infoVehicule['description']?><br>prix journalier : <?=$infoVehicule['prix_journalier']?> €<br> </p>
                        <form action="" method="post">
                            <input type="hidden" name="action" value="reserver">
                            <input type="hidden" name="vehicule" value="<?= $infoVehicule['idvehicule'] ?>">
                            <input type="hidden" name="agence" value="<?= $_POST['agence'] ?>">
                            <input type="hidden" name="dated" value="<?= $date_debut ?>">
                            <input type="hidden" name="datef" value="<?= $date_fin ?>">
                            <input type="hidden" name="titre" value="<?= $infoVehicule['titre'] ?>">
                            <input type="hidden" name="prix_journalier" value="<?= $infoVehicule['prix_journalier'] ?>">
                            <input type="hidden" name="photo" value="<?= $infoVehicule['photo'] ?>">
                            <input type="hidden" name="desc" value="<?= $infoVehicule['description'] ?>">
                            
                            <input type="submit" class="btn btn-success" value="Réserver et payer">
                        </form>
                    </div>
                </div>
            <?php }
        } else { ?>
            <div class="alert alert-danger">il n'y a aucun véhicules disponibles</div>
        <?php } ?>
    </div></div>
<?php } ?>
</div>
</section>

<!--== About Us Area Start ==-->
<section id="about-area" class="section-padding">
        <div class="container">
            <div class="row">
                <!-- Section Title Start -->
                <div class="col-lg-12">
                    <div class="section-title  text-center">
                        <h2>A PROPOS</h2>
                        <span class="title-line"><i class="fa fa-car"></i></span>
                        <p>Veville la location de véhicules à petit prix</p>
                    </div>
                </div>
                <!-- Section Title End -->
            </div>

            <div class="row">
                <!-- About Content Start -->
                <div class="col-lg-6">
                    <div class="display-table">
                        <div class="display-table-cell">
                            <div class="about-content">
                                <p>Veville est une entreprise de location de véhicules implanté depuis plusieurs années au centre de paris, notre entreprise vous propose des prix intéréssant pour la location de véhicules d'exeption ou non, avec un service irréprochable et des avantages pour les clietns fidèles</p>
                                <div class="about-btn">
                                    <a href="#">Nous contacter</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- About Content End -->

                <!-- About Video Start -->
                <div class="col-lg-6">
                    <div class="about-video">
                        <iframe src="https://player.vimeo.com/video/121982328?title=0&byline=0&portrait=0"></iframe>
                    </div>
                </div>
                <!-- About Video End -->
            </div>
        </div>
    </section>
    <!--== About Us Area End ==-->

    <!--== Services Area Start ==-->
    <section id="service-area" class="section-padding">
        <div class="container">
            <div class="row">
                <!-- Section Title Start -->
                <div class="col-lg-12">
                    <div class="section-title  text-center">
                        <h2>Nos services :</h2>
                        <span class="title-line"><i class="fa fa-car"></i></span>
                        <p>Vous trouverez ci-dessous tout les services que nous proposons</p>
                    </div>
                </div>
                <!-- Section Title End -->
            </div>


			<!-- Service Content Start -->
			<div class="row">
				<!-- Single Service Start -->
				<div class="col-lg-4 text-center">
					<div class="service-item">
						<i class="fa fa-taxi"></i>
						<h3>Livraison</h3>
						<p>Livraison du véhicule à domicile (dans la ville de Paris et Lyon) ou à l'aéroport</p>
					</div>
				</div>
				<!-- Single Service End -->

				<!-- Single Service Start -->
				<div class="col-lg-4 text-center">
					<div class="service-item">
						<i class="fa fa-cog"></i>
						<h3>Dépannage</h3>
						<p>Si vous tomber en panne avec l'un de nos véhicules un de nos employés ce déplacera sur place avec un nouveau véhicule</p>
					</div>
				</div>
				<!-- Single Service End -->

				<!-- Single Service Start -->
				<div class="col-lg-4 text-center">
					<div class="service-item">
						<i class="fa fa-map-marker"></i>
						<h3>Application</h3>
						<p>Avec notre application mobile reperez les agences à proximitée de vous et faites voius livré !</p>
					</div>
				</div>
				<!-- Single Service End -->

				<!-- Single Service Start -->
				<div class="col-lg-4 text-center">
					<div class="service-item">
						<i class="fa fa-life-ring"></i>
						<h3>Assurance</h3>
						<p>Une assurance est disponible pour rouler en toute sérénitée</p>
					</div>
				</div>
				<!-- Single Service End -->

				<!-- Single Service Start -->
				<div class="col-lg-4 text-center">
					<div class="service-item">
						<i class="fa fa-bath"></i>
						<h3>Nettoyage des véhicules</h3>
						<p>Tout nos véhicules son nétoyer et astiqué pour votre confort et pour une propreté digne d'un véhicule neuf</p>
					</div>
				</div>
				<!-- Single Service End -->

				<!-- Single Service Start -->
				<div class="col-lg-4 text-center">
					<div class="service-item">
						<i class="fa fa-phone"></i>
						<h3>Service d'appels</h3>
						<p>Si vous n'avez plus besoin de votre véhicule mais qu'aucune agence n'est près de vous vous pouvez contact un de nos employés qui viendra sur place récupérer le véhicule</p>
					</div>
				</div>
				<!-- Single Service End -->
			</div>
			<!-- Service Content End -->
        </div>
    </section>
    <!--== Services Area End ==-->

    <!--== Fun Fact Area Start ==-->
    <section id="funfact-area" class="overlay section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-11 col-md-12 m-auto">
                    <div class="funfact-content-wrap">
                        <div class="row">
                            <!-- Single FunFact Start -->
                            <div class="col-lg-4 col-md-6">
                                <div class="single-funfact">
                                    <div class="funfact-icon">
                                        <i class="fa fa-smile-o"></i>
                                    </div>
                                    <div class="funfact-content">
                                        <p><span class="counter">550</span>+</p>
                                        <h4>Clients satisfaits</h4>
                                    </div>
                                </div>
                            </div>
                            <!-- Single FunFact End -->

                            <!-- Single FunFact Start -->
                            <div class="col-lg-4 col-md-6">
                                <div class="single-funfact">
                                    <div class="funfact-icon">
                                        <i class="fa fa-car"></i>
                                    </div>
                                    <div class="funfact-content">
                                        <p><span class="counter">250</span>+</p>
                                        <h4>Véhicules en stock</h4>
                                    </div>
                                </div>
                            </div>
                            <!-- Single FunFact End -->

                            <!-- Single FunFact Start -->
                            <div class="col-lg-4 col-md-6">
                                <div class="single-funfact">
                                    <div class="funfact-icon">
                                        <i class="fa fa-bank"></i>
                                    </div>
                                    <div class="funfact-content">
                                        <p><span class="counter">50</span>+</p>
                                        <h4>Agences</h4>
                                    </div>
                                </div>
                            </div>
                            <!-- Single FunFact End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== Fun Fact Area End ==-->

    <!--== Pricing Area Start ==-->
    <section id="pricing-area" class="section-padding overlay">
        <div class="container">
            <div class="row">
                <!-- Section Title Start -->
                <div class="col-lg-12">
                    <div class="section-title  text-center">
                        <h2>La qualité à petits prix</h2>
                        <span class="title-line"><i class="fa fa-car"></i></span>
                        <p>Voici nos tarifs pour nos services</p>
                    </div>
                </div>
                <!-- Section Title End -->
            </div>

            <!-- Pricing Table Conatent Start -->
            <div class="row">
                <!-- Single Pricing Table -->
                <div class="col-lg-4 col-md-6 text-center">
                    <div class="single-pricing-table">
                        <h3>GOLD</h3>
                        <h2>49.99 €</h2>
                        <h5>PAR MOIS</h5>

                        <ul class="package-list">
                            <li>LIVRAISON GRATUITE DU VEHICULE</li>
                            <li>LOCATIONS POUR MARIAGES ET AUTRE</li>
                            <li>ASSURANCE INCLUSE</li>
                            <li>DEPANNAGE INCLUS</li>
                        </ul>
                    </div>
                </div>
                <!-- Single Pricing Table -->

                <!-- Single Pricing Table -->
                <div class="col-lg-4 col-md-6 text-center">
                    <div class="single-pricing-table">
                        <h3>BRONZE</h3>
                        <h2>Gratuit</h2>
                        <h5>A L'INCRIPTION AU SERVICE</h5>

                        <ul class="package-list">
                            <li>LIVRAISON PAYANTE</li>
                            <li>LOCATION POUR DIVERS EVENEMENT</li>
                            <li>ASSURANCE EN SUPPLEMENT</li>
                            <li>DEPANAGE EN SUPPLEMENT</li>
                        </ul>
                    </div>
                </div>
                <!-- Single Pricing Table -->

                <!-- Single Pricing Table -->
                <div class="col-lg-4 col-md-6 text-center">
                    <div class="single-pricing-table">
                        <h3>SILVER</h3>
                        <h2>24.99 €</h2>
                        <h5>PAR MOIS</h5>

                        <ul class="package-list">
                            <li>LIVRAISON GRATUITE A L'AEROPORT</li>
                            <li>LOCATION POUR MARIAGES ET AUTRE</li>
                            <li>ASSURANCE EN SUPPLEMENT</li>
                            <li>DEPANNAGE INCLUS</li>
                        </ul>
                    </div>
                </div>
                <!-- Single Pricing Table -->
            </div>
            <!-- Pricing Table Conatent End -->
        </div>
    </section>
    <!--== Pricing Area End ==-->

    <!--== Mobile App Area Start ==-->
    <div id="mobileapp-video-bg"></div>
    <section id="mobile-app-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="mobile-app-content">
                        <h2>REDUCTION DE 20% AVEC L'APP VEVILLE</h2>
                        <p>Facile &amp; Rapide - Réserver votre véhicule en 60 secondes !</p>
                        <p>télécharger sur :</p>
                        <div class="app-btns">
                            <a href="#"><i class="fa fa-android"></i> Android Store</a>
                            <a href="#"><i class="fa fa-apple"></i> Apple Store</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== Mobile App Area End ==-->






<?php
require_once 'inc/footer.php';
?>