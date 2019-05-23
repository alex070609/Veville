<?php
require_once('inc/init.php');
$title = 'Réservations';

if( !isConnected() ){
    $_SESSION['message'] = '<div class="alert alert-danger">Vous devez être connecté pour consulter cette page ! <a href="" data-toggle="modal" data-target="#inscription">cliquez ici</a> pour vous en créer un</div>';
    header('location:'.URL);
    exit();
}

$commande = execReq( "SELECT * FROM commande WHERE membre_idmembre=:id", array(
    'id' => $_SESSION['membre']['idmembre']
));

require_once('inc/header.php');

?>

<h1>Vos réservations</h1>

<div class="table-responsive overflow-auto">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Véhicule</th>
                <th>Agence</th>
                <th>Date de départ</th>
                <th>Date de fin</th>
                <th>Prix total</th>
                <th>Date de réservation</th>
            </tr>
        </thead>
        <tbody>
            <?php while( $ligne = $commande->fetch()){
                ?><tr><?php
                foreach($ligne as $key => $value){
                    if( $key == 'membre_idmembre' ){
                        continue;
                    }
                    if( $key == 'vehicule_idvehicule' ){
                        $whereclause = $value;
                        $vehicule = execReq( "SELECT * FROM vehicule WHERE idvehicule=$whereclause");
                        $vehicules = $vehicule->fetch();
                        $value = $vehicules['titre'];
                    }
                    if( $key == 'vehicule_idagences' ) {
                        $whereclause = $value;
                        $agence = execReq( "SELECT * FROM agences WHERE idagences=$whereclause");
                        $agences = $agence->fetch();
                        $value = $agences['titre'];
                    }
                    ?>
                    <td><?= $value ?></td><?php
                }
            } ?>
            </tr>
        </tbody>
    </table>
</div>

<?php

require_once('inc/footer.php');
