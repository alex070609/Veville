<?php
require_once('../inc/init.php');
$title = 'backoffice';
$acc = false;

if ( !isAdmin() ){
    header('location:'.URL.'connexion.php');
    exit();
}


if( isset($_GET['action']) && isset($_GET['id']) && $_GET['action'] == 'uppriv' ){
    $membre = execReq( "UPDATE membre SET statut=:statut WHERE idmembre=:id", array(
        'statut' => 1,
        'id' => $_GET['id']
    ));
    $_SESSION['message'] = '<div class="alert alert-info">Le membre <span class="text-success">'.$_GET['nom'].'</span> est devenu admin</div>';
    header('location:'.URL.'admin/membre.php');
    exit();
}


if( isset($_GET['action']) && $_GET['action'] == 'downpriv' ){
    $membre = execReq( "UPDATE membre SET statut=:statut WHERE idmembre=:id", array(
        'statut' => 0,
        'id' => $_GET['id']
    ));
    $_SESSION['message'] = '<div class="alert alert-info">Le membre <span class="text-success">'.$_GET['nom'].'</span> est devenu membre</div>';
    header('location:'.URL.'admin/membre.php');
    exit();
}


require_once('../inc/header.php');
?>

<?= $_SESSION['message'] ?? '' ?>
<?php unset($_SESSION['message']) ?>
<div role="main">
    <h2>Membres</h2>
    <div class="table-responsive overflow-auto">
    <table class="table table-striped table-sm">
        <?php $commande = execReq( "SELECT * FROM membre"); ?>
        <thead>
            <tr>
            <?php
            for($i=0;$i<$commande->columnCount();$i++){
                $colonne = $commande->getColumnMeta($i);
                    if( $colonne['name'] == 'mdp' ){
                        continue;
                    }
                ?>
                    <th><?= ucfirst($colonne['name']) ?></th>
                <?php
            } ?>
            <th>Privilèges</th>
            </tr>
        </thead>
        <?php $modif = ''; ?>
        <tbody>
            <?php while( $ligne = $commande->fetch()){
                ?><tr><?php
                foreach($ligne as $key => $value){
                    if( $key == 'mdp' ){
                        continue;
                    }
                    if( $key == 'civilite' ){
                        if($value == 'm'){
                            $value = 'Homme';
                        } else {
                            $value = 'Femme';
                        }
                    }
                    if( $key == "statut"){
                        if( $value == 0 ){
                            $value = "membre";
                            $modif = '<a href="?action=uppriv&id='.$ligne['idmembre'].'&nom='.$ligne['pseudo'].'">Promote</a>';
                        } else if( $value == 1 ) {
                            $value = "admin";
                            if( $_SESSION['membre']['idmembre'] != $ligne['idmembre'] ){
                                $modif = '<a href="?action=downpriv&id='.$ligne['idmembre'].'&nom='.$ligne['pseudo'].'">Demote</a>';
                            }
                        }
                    }
                    ?>
                    <td><?= $value ?></td><?php
                }
                ?><td><?= $modif ?></td><?php
            } ?>
            </tr>
        </tbody>
    </table>
</div>


<?php
require_once('../inc/footer.php');