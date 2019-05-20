<?php
require_once('../inc/init.php');
$title = 'backoffice';
$acc = false;

if ( !isAdmin() ){
    header('location:'.URL);
    exit();
}
require_once('../inc/header.php');
?>
<div role="main">
    <h2>Commandes</h2>
    <div class="table-responsive overflow-auto">
    <table class="table table-striped table-sm">
        <?php $commande = execReq( "SELECT * FROM commande"); ?>
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
                ?><td>Supp/edit</td><?php
            } ?>
            </tr>
        </tbody>
    </table>
</div>


<?php
require_once('../inc/footer.php');