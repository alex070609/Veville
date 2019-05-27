<?php
require_once('inc/init.php');
$title = 'Panier';

if( isset($_GET['action']) && $_GET['action'] == 'valider' && isConnected() ){

    execReq( "INSERT INTO commande VALUES(NULL, :membre, :vehicule, :agence, :date_heure_depart, :date_heure_fin, :prix_journalier, now())", array(
        'membre' => $_SESSION['membre']['idmembre'],
        'vehicule' => $_SESSION['panier']['vehicule'],
        'agence' => $_SESSION['panier']['agence'],
        'date_heure_depart' => date("Y-m-d", $_SESSION['panier']['dated']),
        'date_heure_fin' => date("Y-m-d", $_SESSION['panier']['datef']),
        'prix_journalier' => $_SESSION['prix_journalier']
    ));
    unset($_SESSION['panier']);
    unset($_SESSION['prix_journalier']);
    header('location:'.URL.'reservations.php');
    exit();

}

if( isset($_GET['action']) && $_GET['action'] == 'vider' ){
    unset($_SESSION['panier']);
    unset($_SESSION['prix_journalier']);
}








require_once('inc/header.php');
?>

<h2>Voici votre réservation</h2>
<?php
if( empty($_SESSION['panier']) ){
    echo '<div class="alert alert-info">Votre panier est vide :(</div>';
} else {
    ?>
    <table class="table table-stripped table-bordered">
        <tr>
            <th>Agence</th>
            <th>Vehicule</th>
            <th>Titre</th>
            <th>Date de début</th>
            <th>Date de fin</th>
            <th>Prix journalier</th>
        </tr>
        <tr>
            <td><?= $_SESSION['panier']['agence'] ?></td>
            <td><?= $_SESSION['panier']['vehicule'] ?></td>
            <td><?= $_SESSION['panier']['titre'] ?></td>
            <td><?= date('Y-m-d', $_SESSION['panier']['dated']) ?></td>
            <td><?= date('Y-m-d', $_SESSION['panier']['datef']) ?></td>
            <td><?=$_SESSION['panier']['prix_journalier'] ?></td>
        </tr>
        <tr class="bg-info text-light">
            <th colspan="4" class="text-right">Total</th>
            <th colspan="2" class="text-right"><?= $_SESSION['prix_journalier'] ?> €</th>
        </tr>
        <?php 
        if( isConnected() ){
            ?>
            <tr>
                <td colspan="6" class="text-center">
                    <a href="?action=valider" class="btn btn-primary">Reserver</a>
                </td>
            </tr>
            <?php
        } else {
            ?>
            <tr>
                <td colspan="6" class="text-center">
                    Veuillez vous <a href="<?= URL . 'inscription.php' ?>">inscrire</a> ou vous <a href="<?= URL . 'connexion.php' ?>">connecter</a> afin de valider votre panier.
                </td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td colspan="6" class="text-center">
                <a href="?action=vider" class="btn btn-danger">Annuler la reservation</a>
            </td>
        </tr>
    </table>
    <?php
}
?>




<?php
require_once('inc/footer.php');