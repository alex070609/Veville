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
        $photo_bdd = $_POST['titre'] . $_POST['idagences'] . '_' . $_FILES['photo']['name'];
        $dossier_photo = $_SERVER['DOCUMENT_ROOT'] . URL . 'photo/agences/';
        $ext_auto = ['image/jpeg', 'image/png', 'image/gif'];

        if( in_array($_FILES['photo']['type'], $ext_auto) ){
            move_uploaded_file($_FILES['photo']['tmp_name'], $dossier_photo . $photo_bdd);
        } else {
            $content = '<div class="alert alert-danger">La photo n\'a pas été enregistrer, formats accepté : .jpeg, .jpg, .png, .gif</div>';
        }
    }
    if( empty($content) ){
        extract($_POST);
        if( $idagences != 0 ){
            execReq("UPDATE agences SET titre=:titre, adresse=:adresse, ville=:ville, cp=:cp, description=:description, photo=:photo WHERE idagences=:idagences", array(
                'idagences' => $idagences,
                'titre' => $titre,
                'adresse' => $adresse,
                'ville' => $ville,
                'cp' => $cp,
                'description' => $description,
                'photo' => $photo_bdd,
                'idagences' => $idagences
            ));
            $content .= '<div class="alert alert-success">Le produit à été mis à jour !</div>';
        } else {
            execReq('INSERT INTO agences VALUES (NULL, :titre, :adresse, :ville, :cp, :description, :photo)', array(
                'titre' => $titre,
                'adresse' => $adresse,
                'ville' => $ville,
                'cp' => $cp,
                'description' => $description,
                'photo' => $photo_bdd
            ));
            $content .= '<div class="alert alert-success">Le produit à été ajouté !</div>';
        }
        header('location:'.URL.'admin/agence.php');
        exit();
    }
}


require_once('../inc/header.php');
echo $content;
?>
<div role="main">
    <h2>Agences</h2>
    


    <a href="?action=ajout" class="btn btn-primary mb-4">Ajouter une nouvelle agence</a>
    <?php
    if( (isset($_GET['action']) && $_GET['action'] == 'edit') || (isset($_GET['action']) && $_GET['action'] == 'ajout') ){
        // Ajout d'un véhicule
        ?><form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="idagences" value="<?= $_GET['id'] ?? 0 ?>">
            <div class="row">
                <?php
                if( !empty($_GET['id']) ){
                    $resultat = execReq("SELECT * FROM agences WHERE idagences=:id", array('id' => $_GET['id']));
                    $agence_courante = $resultat->fetch();
                } ?>
                <div class="col">
                    <label for="titre">Titre</label>
                    <input class="form-control" type="text" name="titre" id="titre" value="<?= $_POST['titre'] ?? $agence_courante['titre'] ?? '' ?>">
                </div>
                <div class="col">
                    <label for="adresse">Adresse</label>
                    <input class="form-control" type="text" name="adresse" id="adresse" value="<?= $_POST['adresse'] ?? $agence_courante['adresse'] ?? '' ?>">
                </div>
                <div class="col">
                    <label for="ville">Ville</label>
                    <input class="form-control" type="text" name="ville" id="ville" value="<?= $_POST['ville'] ?? $agence_courante['ville'] ?? '' ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <label for="cp">code postal</label>
                    <input class="form-control" type="text" name="cp" id="cp" value="<?= $_POST['cp'] ?? $agence_courante['cp'] ?? '' ?>">
                </div>
                <div class="col-6">
                    <label for="description">Description</label>
                    <textarea class="form-control" name="description" id="description"><?= $_POST['description'] ?? $agence_courante['description'] ?? '' ?></textarea>
                </div>
            </div>
            <div class="row mb-1">
                <label for="photo">Photo</label>
                <input class="form-control" type="file" name="photo" id="photo">
                <?php 
                    if( !empty($agence_courante['photo']) ){
                        ?>
                        <em>Vous pouvez téléverser une nouvelle photo</em>
                        <img class="img-fluid" src="<?= URL . "photo/agences/" . $agence_courante['photo'] ?>" alt="<?= $agence_courante['titre'] ?>">
                        <input type="hidden" name="photo_courante" value="<?= $agence_courante['photo'] ?>">
                        <?php
                    }
                ?>
            </div>
            <input type="submit" class="btn btn-success mb-4 mt-3" value="Metre à jour / Ajouter">
        </form><?php
    }

    if( isset($_GET['action']) && $_GET['action'] == 'supp' && !empty($_GET['id']) ){
        // retrait d'un vehicule
        // je vais chercher la photo du produit
        $resultat = execReq("SELECT photo FROM agences WHERE idagences=:id", array('id' => $_GET['id']));
        // si je trouve le produit
        if( $resultat->rowCount() == 1 ){
            $agence = $resultat->fetch();
            // si le champs photo est renseigné
            if( !empty($agence['photo']) ){
                $fichier = $_SERVER['DOCUMENT_ROOT'].URL.'photo/pdt/'.$agence['photo'];
                if( file_exists($fichier) ){
                    // suppression de la photo
                    unlink($fichier);
                }
            }
        }
        // supréssion en BDD
        execReq("DELETE FROM agences WHERE idagence=:id", array('id'=>$_GET['id']));
        $content .= '<div class="alert alert-success">Le produit à été supprimé</div>';
    }?>


    <div class="table-responsive overflow-auto">
    <table class="table table-striped table-sm">
        <?php $commande = execReq( "SELECT * FROM agences"); ?>
        <thead>
            <tr>
            <?php
            for($i=0;$i<$commande->columnCount();$i++){
                $colonne = $commande->getColumnMeta($i);
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
                foreach($ligne as $key => $value){?>
                    <td><?= $value ?></td><?php
                }
                ?><td><a href="?action=edit&id=<?= $ligne['idagences'] ?>">Edit</a>/<a href="?action=supp&id=<?= $ligne['idagences'] ?>">supp</a></td><?php
            } ?>
            </tr>
        </tbody>
    </table>
</div>


<?php
require_once('../inc/footer.php');