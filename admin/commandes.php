<?php
require_once('../inc/init.php');
$title = 'backoffice';
$acc = false;

if ( !isAdmin() ){
    header('location:'.URL);
    exit();
}


if( isset($_POST) && !empty($_POST) ){
    extract($_POST);
    execReq( "UPDATE commande SET idcommande=:idcommande, membre_idmembre=:membre_idmembre, vehicule_idvehicule=:vehicule_idvehicule, vehicule_idagences=:vehicule_idagences, date_heure_depart=:date_heure_depart, date_heure_fin=:date_heure_fin, prix_total=:prix_total, date_enregistrement=:date WHERE idcommande=:idcommande", array(
        'idcommande' => $idcommande,
        'membre_idmembre' => $membre_idmembre,
        'vehicule_idvehicule' => $vehicule_idvehicule,
        'vehicule_idagences' => $vehicule_idvehicule,
        'date_heure_depart' => $dated,
        'date_heure_fin' => $datef,
        'prix_total' => $prixt,
        'date' => $date
    ));
    header('location:'.URL.'admin/commandes.php');
    exit();
}

if( (isset($_GET['action']) && $_GET['action'] == 'supp') ){
    // supréssion en BDD
    execReq("DELETE FROM commande WHERE idcommande=:id", array('id' => $_GET['id']));
    $content .= '<div class="alert alert-success">Le produit à été supprimé</div>';
}


require_once('../inc/header.php');


if( (isset($_GET['action']) && $_GET['action'] == 'edit') ){
    // Ajout d'un véhicule
    ?><form method="POST">
        <div class="col-12 alert alert-danger">
            <div class="col-12">
                <small>Ne modifier que si vous connaissez les données présentes</small>
            </div>
            <div class="d-flex col-12">
                <div class="col-6">
                    <label for="">Id commande</label>
                    <input type="text" class="form-control" name="idcommande" value="<?= $_GET['id'] ?>">
                    <label for="">Id membre</label>
                    <input type="text" class="form-control" name="membre_idmembre" value="<?= $_GET['membre'] ?>">
                </div>
                <div class="col-6">
                    <label for="">Id vehicule</label>
                    <input type="text" class="form-control" name="vehicule_idvehicule" value="<?= $_GET['vehicule'] ?>">
                    <label for="">Id agence</label>
                    <input type="text" class="form-control" name="vehicule_idagences" value="<?= $_GET['agence'] ?>">
                </div>
            </div>
        </div>
        <?php
            $req = execReq( "SELECT *FROM commande WHERE idcommande=:id AND membre_idmembre=:idm", array(
                'id' => $_GET['id'],
                'idm' => $_GET['membre']
            ));
            $res = $req->fetch()
        ?>
        <div class="col-12 d-flex">
            <div class="col-4">
                <label for="dated">Date départ</label>
                <input type="text" class="form-control" name="dated" id="dated" value="<?= $res['date_heure_depart'] ?>">
            </div>
            <div class="col-4">
                <label for="datef">Date fin</label>
                <input type="text" class="form-control" name="datef" id="datef" value="<?= $res['date_heure_fin'] ?>">
            </div>
            <div class="col-4">
                <label for="prixt">Prix total</label>
                <input type="text" class="form-control" name="prixt" id="prixt" value="<?= $res['prix_total'] ?>">
            </div>
            <input type="hidden" name="date" value="<?= $res['date_enregistrement'] ?>">
        </div>
        <input type="submit" class="btn btn-success mb-4 mt-3" value="Mettre à jour">
    </form><?php
}



?>
<div role="main">
    <h2>Commandes</h2>
    <div class="table-responsive overflow-auto">
    <table class="table table-striped table-sm">
        <?php 
        $whereclause = '';
        if( isset($_GET['where']) ){
            $whereclause = 'WHERE idcommande='.$_GET['where'];
        }
        $commande = execReq( "SELECT * FROM commande $whereclause"); 
        ?>
        <thead>
            <tr>
            <?php
            for($i=0;$i<$commande->columnCount();$i++){
                $colonne = $commande->getColumnMeta($i);
                if( $colonne['name'] == 'idcommande' ){
                    $colonne['name'] = 'id';
                }
                if( $colonne['name'] == 'membre_idmembre' ){
                    $colonne['name'] = 'membre';
                }
                if( $colonne['name'] == 'vehicule_idvehicule' ){
                    $colonne['name'] = 'vehicule';
                }
                if( $colonne['name'] == 'vehicule_idagences' ){
                    $colonne['name'] = 'agence';
                }
                ?>
                    <th><?= ucfirst($colonne['name']) ?></th>
                <?php
            } ?>
            <th>Edition</th>
            </tr>
        </thead>
        <tbody>
            <?php while( $ligne = $commande->fetch()){
                ?><tr><?php
                foreach($ligne as $key => $value){
                    if($key == 'membre_idmembre'){
                        $membre = execReq( "SELECT * FROM membre WHERE idmembre=:idmembre", array(
                            'idmembre' => $value
                        ));
                        $leMembre = $membre->fetch();
                        $value = $leMembre['pseudo'];
                    }?>
                    <td><?= $value ?></td><?php
                }
                ?><td><a href="?action=edit&id=<?= $ligne['idcommande'] ?>&membre=<?= $ligne['membre_idmembre'] ?>&vehicule=<?= $ligne['vehicule_idvehicule'] ?>&agence=<?= $ligne['vehicule_idagences'] ?>">edit</a>/<a href="?action=supp&id=<?= $ligne['idcommande'] ?>">supp</a></td><?php
            } ?>
            </tr>
        </tbody>
    </table>
    <?php 
    if( isset($_GET['where']) ) {
        ?><a href="<?= URL . 'admin/commandes.php' ?>" class="btn btn-primary">Tout afficher</a><?php
    }
    ?>
</div>


<?php



require_once('../inc/footer.php');

