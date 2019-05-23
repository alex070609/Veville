<?php
require_once('../inc/init.php');
$title = 'backoffice';
$acc = false;

if ( !isAdmin() ){
    header('location:'.URL);
    exit();
}

// enregistrement d'un produit en BDD
if( !empty($_POST) ){
    $nb_champs_vides = 0;
    foreach($_POST as $value){
        if($value == ''){
            $nb_champs_vides++;
        }
    }
    if( $nb_champs_vides > 0 ){
        $content .= '<div class="alert alert-danger">Merci de remplir les '.$nb_champs_vides.' champ(s) manquant(s)</div>';
    }

    //gérer la photo
    $photo_bdd = $_POST['photo_courante'] ?? '';
    
    if( !empty($_FILES['photo']['name']) ){
        $photo_bdd = $_POST['marque'] . $_POST['modele'] . '_' . $_POST['idvehicule'] . '_' . $_FILES['photo']['name'];
        $dossier_photo = $_SERVER['DOCUMENT_ROOT'] . URL . 'photo/vehicule/';
        $ext_auto = ['image/jpeg', 'image/png', 'image/gif'];

        if( in_array($_FILES['photo']['type'], $ext_auto) ){
            move_uploaded_file($_FILES['photo']['tmp_name'], $dossier_photo . $photo_bdd);
        } else {
            $content = '<div class="alert alert-danger">La photo n\'a pas été enregistrer, formats accepté : .jpeg, .jpg, .png, .gif</div>';
        }
    } else {
        echo 'non';
    }

    if( empty($content) ){
        extract($_POST);
        if( $idvehicule != 0 ){
            execReq("UPDATE vehicule SET agences_idagences=:id, titre=:titre, marque=:marque, modele=:modele, description=:description, photo=:photo, prix_journalier=:prix_journalier WHERE idvehicule=:idvehicule", array(
                'id' => $agences_idagences,
                'titre' => $titre,
                'marque' => $marque,
                'modele' => $modele,
                'description' => $description,
                'photo' => $photo_bdd,
                'prix_journalier' => $prix_journalier,
                'idvehicule' => $idvehicule
            ));
        } else {
            execReq('INSERT INTO vehicule VALUES (NULL, :agences_idagences, :titre, :marque, :modele, :description, :photo, :prix_journalier)', array(
                'agences_idagences' => $agences_idagences,
                'titre' => $titre,
                'marque' => $marque,
                'modele' => $modele,
                'description' => $description,
                'photo' => $photo_bdd,
                'prix_journalier' => $prix_journalier
            ));
            $content .= '<div class="alert alert-success">Le produit à été ajouté !</div>';
        }
        header('location:'.URL.'admin/vehicules.php');
        exit();
    }
}


require_once('../inc/header.php');


?>
<div role="main">
    <h2>Vehicules</h2>
    <a href="?action=ajout" class="btn btn-primary mb-4">Ajouter un nouveau véhicule</a>
    <?php
    if( (isset($_GET['action']) && $_GET['action'] == 'edit') || (isset($_GET['action']) && $_GET['action'] == 'ajout') ){
        // Ajout d'un véhicule
        ?><form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="idvehicule" value="<?= $_GET['id'] ?? 0 ?>">
            <div class="row">
                <div class="col">
                    <label for="agence">Agence</label>
                    <select class="form-control" name="agences_idagences" id="agence">
                        <?php $agence = execReq( "SELECT * FROM agences");
                        while( $option = $agence->fetch() ){
                            ?><option value="<?= $option['idagences'] ?>">
                                <?= $option['idagences'] . ' - ' . $option['titre'] ?>
                            </option><?php
                        }
                        ?>
                    </select>
                </div>
                <?php
                if( !empty($_GET['id']) ){
                    $resultat = execReq("SELECT * FROM vehicule WHERE idvehicule=:id", array('id' => $_GET['id']));
                    $vehicule_courant = $resultat->fetch();
                } ?>
                <div class="col">
                    <label for="titre">Nom</label>
                    <input class="form-control" type="text" name="titre" id="titre" value="<?= $_POST['titre'] ?? $vehicule_courant['titre'] ?? '' ?>">
                </div>
                <div class="col">
                    <label for="marque">Marque</label>
                    <input class="form-control" type="text" name="marque" id="marque" value="<?= $_POST['marque'] ?? $vehicule_courant['marque'] ?? '' ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <label for="modele">Modèle</label>
                    <input class="form-control" type="text" name="modele" id="modele" value="<?= $_POST['modele'] ?? $vehicule_courant['modele'] ?? '' ?>">
                </div>
                <div class="col-6">
                    <label for="description">Description</label>
                    <textarea class="form-control" name="description" id="description"><?= $_POST['description'] ?? $vehicule_courant['description'] ?? '' ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-6 mb-1">
                    <label for="photo">Photo</label>
                    <input class="form-control" type="file" name="photo" id="photo">
                    <?php 
                        if( !empty($vehicule_courant['photo']) ){
                            ?>
                            <em>Vous pouvez téléverser une nouvelle photo</em>
                            <img class="img-fluid" src="<?= URL . "photo/vehicule/" . $vehicule_courant['photo'] ?>" alt="<?= $vehicule_courant['titre'] ?>">
                            <input type="hidden" name="photo_courante" value="<?= $vehicule_courant['photo'] ?>">
                            <?php
                        }
                    ?>
                </div>
                <div class="col-6 mb-1">
                    <label for="prix_journalier">Prix journalier</label>
                    <input class="form-control" type="number" name="prix_journalier" id="prix_journalier" value="<?= $_POST['prix_journalier'] ?? $vehicule_courant['prix_journalier'] ?? '' ?>">
                </div>
            </div>
            <input type="submit" class="btn btn-success mb-4 mt-3" value="Metre à jour / Ajouter">
        </form><?php
    }

    if( isset($_GET['action']) && $_GET['action'] == 'supp' && !empty($_GET['id']) ){
        // retrait d'un vehicule
        // je vais chercher la photo du produit
        $resultat = execReq("SELECT photo FROM vehicule WHERE idvehicule=:id", array('id' => $_GET['id']));
        // si je trouve le produit
        if( $resultat->rowCount() == 1 ){
            $vehicule = $resultat->fetch();
            // si le champs photo est renseigné
            if( !empty($vehicule['photo']) ){
                $fichier = $_SERVER['DOCUMENT_ROOT'].URL.'photo/pdt/'.$vehicule['photo'];
                if( file_exists($fichier) ){
                    // suppression de la photo
                    unlink($fichier);
                }
            }
        }
        // supréssion en BDD
        execReq("DELETE FROM vehicule WHERE idvehicule=:id", array('id'=>$_GET['id']));
        $content .= '<div class="alert alert-success">Le produit à été supprimé</div>';
    }
    echo $content;
    ?>
    <div class="table-responsive overflow-auto">
    <table class="table table-striped table-bordered table-sm">
        <?php $commande = execReq( "SELECT * FROM vehicule"); ?>
        <thead>
            <tr>
            <?php
            for($i=0;$i<$commande->columnCount();$i++){
                $colonne = $commande->getColumnMeta($i);
                if( $colonne['name'] == 'agences_idagences' ){
                    $colonne['name'] = 'Agence';
                }
                if( $colonne['name'] == 'idvehicule'){
                    $colonne['name'] = 'Id';
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
                    if( $key == 'photo' ){
                        $value = '<img src="'.URL.'photo/vehicule/'.$value.'" alt="'.$value.'" style="width:auto;height:100px;">';
                    }
                    if( $key == 'agences_idagences' ){
                        $agence = execReq( "SELECT * FROM agences WHERE idagences=:idagences", array(
                            'idagences' => $value
                        ));
                        $resultat = $agence->fetch();
                        $value = $resultat['titre'];
                    }
                    ?>
                    <td><?= $value ?></td><?php
                }
                ?><td><a href="?action=edit&id=<?= $ligne['idvehicule'] ?>">Edit</a>/<a href="?action=supp&id=<?= $ligne['idvehicule'] ?>">supp</a></td><?php
            } ?>
            </tr>
        </tbody>
    </table>
</div>


<?php
require_once('../inc/footer.php');