<?php
require_once('inc/init.php');
if( !isConnected() ){
    $_SESSION['message'] = '<div class="alert alert-danger">Vous devez être connecté pour consulter cette page ! <a href="" data-toggle="modal" data-target="#inscription">cliquez ici</a> pour vous en créer un</div>';
    header('location:'.URL.'index.php');
    exit();
}
if( isset($_POST['titre']) ){
    $title = $_POST['titre'];
} else {
    $title = $_GET['titre'];
}


if( !empty($_GET['action']) && $_GET['action'] == "reserver" ){
    $nb_de_jour_timestamp = $_GET['datef'] - $_GET['dated'];
    $nb_de_jour = $nb_de_jour_timestamp/86400;
    $prix_journalier = $nb_de_jour * $_GET['prix_journalier'];
    execReq( "INSERT INTO commande VALUES(NULL, :membre, :vehicule, :agence, :date_heure_depart, :date_heure_fin, :prix_journalier, now())", array(
        'membre' => $_SESSION['membre']['idmembre'],
        'vehicule' => $_GET['vehicule'],
        'agence' => $_GET['agence'],
        'date_heure_depart' => date("Y-m-d", $_GET['dated']),
        'date_heure_fin' => date("Y-m-d", $_GET['datef']),
        'prix_journalier' => $prix_journalier
    ));
    $content = '<div class="alert alert-success">Votre reservation a été effectué !</div>';
}


require_once('inc/header.php');
var_dump($_POST);
if( !empty($_POST) ):
?>
<div class="row">
    <div class="col-4">
        <h2>Véhicule sélectionné</h2>
        <div class="card" style="width: 100%">
            <img src="<?= URL . 'photo/vehicule/' . $_POST['photo'] ?>" class="card-img-top" alt="voiture">
            <div class="card-body">
                <h5 class="card-title"><?= $_POST['titre'] ?></h5>
                <p class="card-text">description : <?= $_POST['desc'] ?><br>prix journalier : <?= $_POST['prix_journalier'] ?> €<br> </p>
            </div>
        </div>
    </div>
    <div class="col-8">
        <h3>Réservation :</h3>
        <?php
        $timestamp1 = strtotime($_POST['dated']);
        $timestamp2 = strtotime($_POST['datef']);
        $date_deja_prise = execReq( "SELECT * FROM commande WHERE vehicule_idvehicule=:idvehicule", array(
            'idvehicule' => $_POST['vehicule']
        ));
        $texte = '';
        $color = '';
        while ($date = $date_deja_prise->fetch() ){
            $date_debut = strtotime($date['date_heure_depart']);
            $date_fin = strtotime($date['date_heure_fin']);
            if( ($date_debut <= $timestamp2 && $timestamp1 <= $date_fin) ){
                $texte = 'Véhicule indisponible';
                $color = 'danger';
                ?><p class="alert alert-info">Ce véhicule est déjà réserver du <?= $date['date_heure_depart'] ?> au <?= $date['date_heure_fin'] ?></p><?php
            } 
        } ?>
        <div class="row">
            <form action="" method="get" class="ml-4">
                <input type="hidden" name="dated" value="<?= $timestamp1 ?>">
                <input type="hidden" name="titre" value="<?= $_POST['titre'] ?>">
                <input type="hidden" name="datef" value="<?= $timestamp2 ?>">
                <input type="hidden" name="agence" value="<?= $_POST['agence'] ?>">
                <input type="hidden" name="vehicule" value="<?= $_POST['vehicule'] ?>">
                <input type="hidden" name="prix_journalier" value="<?= $_POST['prix_journalier'] ?>">
                <?php 
                    if( empty($texte) ){
                        ?><input type="hidden" name="action" value="reserver"><?php
                    }
                ?>
                <button type="submit" class="btn btn-<?= !empty($color) ? $color : 'success' ?>" <?= ($texte == 'Véhicule indisponible') ? 'disabled' : '' ?>><?= !empty($texte) ? $texte : 'Réserver' ?></button>
            </form>
            <form action="index.php" method="get" class="ml-4">
                <input type="hidden" name="action" value="ok">
                <input type="hidden" name="action2" value="ok">
                <input type="hidden" name="agence" value="<?= $_POST['agence'] ?>">
                <input type="hidden" name="idmembre" value="<?= $_SESSION['membre']['idmembre'] ?>">
                <input type="hidden" name="dated" value="<?= $_POST['dated'] ?>">
                <input type="hidden" name="datef" value="<?= $_POST['datef'] ?>">
                <input type="submit" class="btn btn-info" value="Retour">
            </form>
        </div>
    </div>
</div>


<?php
endif;


require_once('inc/footer.php');