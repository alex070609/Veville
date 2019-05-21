<?php
require_once('inc/init.php');
$title = 'Accueil';
require_once('inc/header.php');

if( !empty($_SESSION['message'])){
    echo $_SESSION['message'];
    unset($_SESSION['message']);
}

$content = '';
$nb_champ_vide = 0;
if( !empty($_POST) ){
    foreach( $_POST as $value ){
        if( $value == '' ){
            $nb_champ_vide++;
        }
    }
    if( $nb_champ_vide > 0 ){
        $content .= '<div class="alert alert-danger">Merci de remplir les '.$nb_champ_vide.' champ(s) manquant(s)</div>';
    }

    if( empty($content) ){
        extract($_POST);
        if( !empty($idmembre) ){
            $vehicule = execReq( "SELECT * FROM vehicule WHERE idvehicule=:idvehicule", array(
                'idvehicule' => $idvehicule
            ));
            $infoVehicule = $vehicule->fetch();
            $agence = $infoVehicule['agences_idagences'];
            $timestamp1 = strtotime($date_heure_depart);
            $timestamp2 = strtotime($date_heure_fin);
            $date_deja_prise = execReq( "SELECT * FROM commande WHERE vehicule_idvehicule=:idvehicule", array(
                'idvehicule' => $idvehicule
            ));
            $date = $date_deja_prise->fetch();
            if( !empty($date) ){
                if( ($timestamp1 > strtotime($date['date_heure_depart'])) && (strtotime($date['date_heure_fin']) < $timestamp2) ){
                    $content .= '<div class="alert alert-danger">Le véhicule '.$infoVehicule['titre'].' est déjà louer du '.$date['date_heure_depart'].' au '.$date['date_heure_fin'].' inclu</div>';
                }
            }
            if( empty($content) ){
                $nb_de_jour_timestamp = $timestamp2 - $timestamp1;
                $nb_de_jour = $nb_de_jour_timestamp/86400;
                $prix_journalier = $nb_de_jour * $infoVehicule['prix_journalier'];
                execReq( "INSERT INTO commande VALUES(NULL, $idmembre, $idvehicule, $agence, :date_heure_depart, :date_heure_fin, $prix_journalier, now())", array(
                    'date_heure_depart' => date("Y-m-d", $timestamp1),
                    'date_heure_fin' => date("Y-m-d", $timestamp2)
                ));
                $content = '<div class="alert alert-success">Votre reservation a été effectué !</div>';
            }
        } else {
            $content .= '<div class="alert alert-danger">Pour passer une commande vous devez créer un compte <a href="" data-toggle="modal" data-target="#inscription">cliquez ici</a> pour vous en créer un</div>';
        }
    }
}

echo $content;
?>
<div class="text-center">
    <h1>VEVILLE</h1>
    <h2>Location de véhicules à petit prix !</h2>
</div>
<img src="photo/voitures.png" alt="Voitures" style="width:100%;height:auto">
<form method="post">
    <input type="hidden" name="idmembre" value="<?= isset($_SESSION['membre']) ? $_SESSION['membre']['idmembre'] : '' ?>">
    <label for="idvehicule">Vehicule</label>
    <select name="idvehicule" id="idvehicule">
        <?php 
        $vehicules = execReq( "SELECT v.idvehicule, v.marque, v.modele, a.titre FROM vehicule v, agences a WHERE v.agences_idagences = a.idagences ORDER BY marque");
        while( $vehicule = $vehicules->fetch() ){
            ?><option value="<?= $vehicule['idvehicule'] ?>"><?= $vehicule['marque'] . ' - ' . $vehicule['modele'] . ' - ' . $vehicule['titre'] ?></option><?php
        } ?>
    </select>
    <label for="date_heure_depart">Début de la loc</label>
    <input type="text" name="date_heure_depart" id="date_heure_depart">
    <label for="date_heure_fin">Fin de la loc</label>
    <input type="text" name="date_heure_fin" id="date_heure_fin">
    <input type="submit" value="Envoyer" class="btn btn-primary">
</form>
<script>
$(function() {
    $( "#date_heure_depart" ).datepicker({
        minDate: 0,
        buttonImageOnly: true
    });
    $( "#date_heure_fin" ).datepicker({
        minDate: '+3D',
        maxDate: '+2W',
        buttonImageOnly: true
    });
});
</script>
<?php
require_once('inc/footer.php');
?>