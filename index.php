<?php
require_once('inc/init.php');
$title = 'Accueil';
$agence = '';
require_once('inc/header.php');




if( !empty($_SESSION['message'])){
    echo $_SESSION['message'];
    unset($_SESSION['message']);
}

echo $content;





?>
<div class="text-center">
    <h1>VEVILLE</h1>
    <h2>Location de véhicules à petit prix !</h2>
</div>
<img src="photo/voitures.png" alt="Voitures" style="width:100%;height:auto">
<hr>

<form method="get">
<label for="agence">Sélectionner votre agence</label>
<input type="hidden" name="action" value="ok">
<div class="row">
    <select name="agence" id="agence" class="form-control col-5">
        <?php
        $resultat = execReq( "SELECT * FROM agences");
        while($agence = $resultat->fetch()){
            ?><option value="<?= $agence['idagences'] ?>"><?= $agence['titre'] ?></option><?php
        }
        ?>
    </select>
    <input type="submit" value="Choisir" class="btn btn-primary ml-2">
</div>
</form>
<hr>
<?php
if( isset($_GET['action']) && $_GET['action'] == 'ok' ){
    $agenceFinale = $_GET['agence'];
    $agence = '';
    var_dump($_GET);
    if( empty($agence) ){
        $agencereq = execReq( "SELECT * FROM agences WHERE idagences=:id", array(
            'id' => $agenceFinale
        ));
        $agenceinfo = $agencereq->fetch();
        $agence .= '
        <div class="col-12">
            <h4>Agence sélectionnée</h4>
            <div class="card mb-3" style="max-width: 540px;">
                <div class="row no-gutters">
                    <div class="col-md-4">
                        <img src="'. URL . 'photo/agences/' . $agenceinfo['photo'] .'" class="card-img" alt="'. $agenceinfo['titre'] .'">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">'. $agenceinfo['titre'] .'</h5>
                            <p class="card-text">'. $agenceinfo['description'] .'</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ';
        echo $agence;
    } 
    ?>
    <form method="get" class="row">
        <input type="hidden" name="action" value="ok">
        <input type="hidden" name='action2' value="ok">
        <input type="hidden" name='agence' value="<?= isset($_GET['agence']) ? $_GET['agence'] : '' ?>">
        <input type="hidden" name="idmembre" value="<?= isset($_SESSION['membre']) ? $_SESSION['membre']['idmembre'] : '' ?>">
        <label style="margin-top:5px;" for="date_heure_debut">Début de la loc</label>
        <input class="form-control col mx-2" type="text" name="date_heure_debut" autocomplete="off" id="date_heure_debut" value="<?= (isset($_GET['date_heure_debut']) ? $_GET['date_heure_debut'] : '') ?>">
        <label style="margin-top:5px;" for="date_heure_fin">Fin de la loc</label>
        <input class="form-control col mx-2" type="text" name="date_heure_fin" autocomplete="off" id="date_heure_fin" value="<?= (isset($_GET['date_heure_fin']) ? $_GET['date_heure_fin'] : '') ?>">
        <input type="submit" value="Selectionner ses dates" class="btn btn-success">
    </form>
    <hr>
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
<?php
    if( isset($_GET['action2']) && $_GET['action2'] == 'ok' ){
        $idmembre = $_GET['idmembre'];
        $date_debut = $_GET['date_heure_debut'];
        $date_fin = $_GET['date_heure_fin'];
        ?>
        <div class="container mt-5 mb-5">
        <div class="row">
        <?php
        $mot = '';
        $vehicule = execReq( "SELECT * FROM vehicule WHERE agences_idagences=:id", array(
            'id' => $_GET['agence']
        ));
        while( $infoVehicule = $vehicule->fetch() ){
            ?>
            <div class="card col-4" style="width: 18rem;">
                <img src="<?= URL . 'photo/vehicule/' . $infoVehicule['photo'] ?>" class="card-img-top" alt="voiture">
                <div class="card-body">
                    <h5 class="card-title"><?= $infoVehicule['titre'] ?></h5>
                    <p class="card-text">description : <?= $infoVehicule['description'] ?><br>prix journalier : <?= $infoVehicule['prix_journalier'] ?> €<br> </p>
                    <form action="choix_vehicule.php" method="post">
                        <input type="hidden" name="vehicule" value="<?= $infoVehicule['idvehicule'] ?>">
                        <input type="hidden" name="agence" value="<?= $agenceFinale ?>">
                        <input type="hidden" name="dated" value="<?= $date_debut ?>">
                        <input type="hidden" name="datef" value="<?= $date_fin ?>">
                        <input type="hidden" name="titre" value="<?= $infoVehicule['titre'] ?>">
                        <input type="hidden" name="prix_journalier" value="<?= $infoVehicule['prix_journalier'] ?>">
                        <input type="hidden" name="photo" value="<?= $infoVehicule['photo'] ?>">
                        <input type="hidden" name="desc" value="<?= $infoVehicule['description'] ?>">
                        
                        <input type="submit" class="btn btn-info" value="Sélectionner ce véhiucle">
                    </form>
                </div>
            </div>
            <?php
        }
        ?></div></div><?php
    }
}






$content = '';
$nb_champ_vide = 0;
if( !empty($_GET) ){
    if( !empty($_get['membre']) ){
        $vehicule = execReq( "SELECT * FROM vehicule WHERE idvehicule=:idvehicule", array(
            'idvehicule' => $_GET['vehicule']
        ));
        $infoVehicule = $vehicule->fetch();
        $agence = $infoVehicule['agences_idagences'];
        $timestamp1 = strtotime($_GET['dated']);
        $timestamp2 = strtotime($_GET['datef']);
        $date_deja_prise = execReq( "SELECT * FROM commande WHERE vehicule_idvehicule=:idvehicule", array(
            'idvehicule' => $_GET['vehicule']
        ));
        while( $date = $date_deja_prise->fetch() ){
            $date_debut = strtotime($date['date_heure_depart']);
            $date_fin = strtotime($date['date_heure_fin']);
            if( ($date_debut <= $timestamp2 && $timestamp1 <= $date_fin) ){
                $content .= '<div class="alert alert-danger">Le véhicule '.$infoVehicule['titre'].' est déjà louer du '.$date['date_heure_depart'].' au '.$date['date_heure_fin'].' inclu</div>';
            }
        }
        if( empty($content) ){
            $nb_de_jour_timestamp = $timestamp2 - $timestamp1;
            $nb_de_jour = $nb_de_jour_timestamp/86400;
            $prix_journalier = $nb_de_jour * $infoVehicule['prix_journalier'];
            execReq( "INSERT INTO commande VALUES(NULL, :membre, :vehicule, :agence, :date_heure_depart, :date_heure_fin, $prix_journalier, now())", array(
                'membre' => $_GET['membre'],
                'vehicule' => $_GET['vehicule'],
                'agence' => $_GET['agence'],
                'date_heure_depart' => date("Y-m-d", $timestamp1),
                'date_heure_fin' => date("Y-m-d", $timestamp2)
            ));
            $content = '<div class="alert alert-success">Votre reservation a été effectué !</div>';
        }
    } else {
        $content .= '<div class="alert alert-danger">Pour passer une commande vous devez créer un compte <a href="" data-toggle="modal" data-target="#inscription">cliquez ici</a> pour vous en créer un</div>';
    }
}










require_once('inc/footer.php');
?>