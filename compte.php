<?php
require_once('inc/init.php');
$title = 'Compte';
$content = '';
if( !isConnected() ){ header('location:' . URL); exit(); }

function verif_pseudo(){
    if( empty($_POST['pseudo']) ){
        return $_SESSION['membre']['pseudo'];
    } else {
        return $_POST['pseudo'];
    }
}
function verif_prenom(){
    if( empty($_POST['prenom']) ){
        return $_SESSION['membre']['prenom'];
    } else {
        return $_POST['prenom'];
    }
}
function verif_nom(){
    if( empty($_POST['nom']) ){
        return $_SESSION['membre']['nom'];
    } else {
        return $_POST['nom'];
    }
}
function verif_email(){
    if( empty($_POST['email']) ){
        return $_SESSION['membre']['email'];
    } else {
        return $_POST['email'];
    }
}


if( !empty($_POST) ){
    if( isset($_POST['info']) ){
        $req = execReq( "SELECT * FROM membre WHERE idmembre=:id", array('id' => $_SESSION['membre']['idmembre']));
        if( $req->rowCount() != 0 ){
            $res = $req->fetch();
            execReq( "UPDATE membre SET idmembre=:idmembre, pseudo=:nvpseudo, mdp=:mdp, prenom=:prenom, nom=:nom, email=:email, civilite=:civilite, statut=:statut, date_enregistrement=:date_enregistrement, photo=:photo WHERE idmembre=:idmembre", array(
                'idmembre' =>$_SESSION['membre']['idmembre'],
                'mdp' => $res['mdp'],
                'nvpseudo' => verif_pseudo(),
                'prenom' => verif_prenom(),
                'nom' => verif_nom(),
                'email' => verif_email(),
                'civilite' => $_SESSION['membre']['civilite'],
                'statut' => $_SESSION['membre']['statut'],
                'date_enregistrement' => $_SESSION['membre']['date_enregistrement'],
                'photo' => $_SESSION['membre']['photo']
            ));
            $_SESSION['message'] = '<div class="alert alert-success">Vos informations on été mises à jours !</div>';
            $req = execReq( "SELECT * FROM membre WHERE idmembre=:id", array('id' => $_SESSION['membre']['idmembre']));
            $res = $req->fetch();
            $_SESSION['membre'] = $res;
            header('location:'.URL.'compte.php');
            exit();
        } else {
            $content = '<div class="alert alert-danger">Vos informations ne sont pas correcte !</div>';
        }
    } else if( isset($_POST['mdp']) ){
        $req = execReq( "SELECT * FROM membre WHERE idmembre=:id", array('id' => $_SESSION['membre']['idmembre']));
        if( $req->rowCount() != 0 ){
            $res = $req->fetch();
            if( $_POST['email'] == $res['email'] && md5($_POST['mdp'] . SALT) == $res['mdp'] ){
                if( $_POST['mdp1'] != $_POST['mdp2'] ){
                    $content .= '<div class="alert alert-danger">Vos mots de passe de correspondent pas !</div>';
                } else {
                    execReq( "UPDATE membre SET idmembre=:idmembre, pseudo=:pseudo, mdp=:mdp, prenom=:prenom, nom=:nom, email=:email, civilite=:civilite, statut=:statut, date_enregistrement=:date_enregistrement, photo=:photo WHERE idmembre=:idmembre", array(
                        'idmembre' =>$_SESSION['membre']['idmembre'],
                        'pseudo' => $_SESSION['membre']['pseudo'],
                        'mdp' => md5($_POST['mdp1'] . SALT),
                        'prenom' => $_SESSION['membre']['prenom'],
                        'nom' => $_SESSION['membre']['nom'],
                        'email' => $_SESSION['membre']['email'],
                        'civilite' => $_SESSION['membre']['civilite'],
                        'statut' => $_SESSION['membre']['statut'],
                        'date_enregistrement' => $_SESSION['membre']['date_enregistrement'],
                        'photo' => $_SESSION['membre']['photo']
                    ));
                    $_SESSION['message'] = '<div class="alert alert-success">Mot de passe changé !</div>';
                    header('location:'.URL.'compte.php');
                    exit();
                }
            } else {
                $content .= '<div class="alert alert-danger">Il se peux que votre adresse mail ne corresponde pas à celle sur la base de données ou que votre ancien mot de passe soit incorrect !</div>';
            }
        }
    } else if ( isset($_POST['photo']) ){
        $photo_bdd = '';
    
        if( !empty($_FILES['photo']['name']) ){
            $photo_bdd = $_SESSION['membre']['pseudo'] . '_' . $_SESSION['membre']['prenom'] . '_' . $_SESSION['membre']['nom'] . '_' . $_FILES['photo']['name'];
            $dossier_photo = $_SERVER['DOCUMENT_ROOT'] . URL . 'photo/users/';
            $ext_auto = ['image/jpeg', 'image/png', 'image/gif'];

            if( in_array($_FILES['photo']['type'], $ext_auto) ){
                move_uploaded_file($_FILES['photo']['tmp_name'], $dossier_photo . $photo_bdd);

                $req = execReq( "UPDATE membre SET photo=:photo WHERE idmembre=:idmembre", array(
                    'idmembre' =>$_SESSION['membre']['idmembre'],
                    'photo' => $photo_bdd
                ));

                $_SESSION['message'] = '<div class="alert alert-success">Mise à jour de la photo réussite !</div>';

                $req = execReq( "SELECT * FROM membre WHERE idmembre=:id", array('id' => $_SESSION['membre']['idmembre']));
                $res = $req->fetch();
                unset($_SESSION['membre']);
                $_SESSION['membre'] = $res;

                header('location:'.URL.'compte.php');
                exit();
            } else {
                $content = '<div class="alert alert-danger">La photo n\'a pas été enregistrer, formats accepté : .jpeg, .jpg, .png, .gif</div>';
            }
        }
    }
}

require_once('inc/header.php');
?>

<h1>Bienvenue <?= $_SESSION['membre']['prenom'] ?> !</h1>

<?php
echo $content;

if( isset($_SESSION['message']) ){
    echo $_SESSION['message'];
    unset($_SESSION['message']);
}

if( isset($_GET['action']) && $_GET['action'] == 'modif-info' ){
    ?>
    <h2>Modifications de mes informations personnelles</h2>
    <form method="POST" action="" class="mb-4">
        <input type="hidden" name="info">
        <div class="form-group">
            <small>Pseudo actuel</small>
            <input type="text" class="form-control" name="ancpseudo">
        </div>
        <div class="form-group">
            <small>Pseudo</small>
            <input type="text" class="form-control" name="pseudo">
        </div>
        <div class="form-group">
            <small>Prenom</small>
            <input type="text" class="form-control" name="prenom">
        </div>
        <div class="form-group">
            <small>Nom</small>
            <input type="text" class="form-control" name="nom">
        </div>
        <div class="form-group">
            <small>Email</small>
            <input type="text" class="form-control" name="email">
        </div>
        <div class="from-group">
            <input type="submit" class="btn btn-primary" value="Modifier">
        </div>
    </form>
    <?php
}
?>

<?php
if( isset($_GET['action']) && $_GET['action'] == 'modif-mdp' ){
    ?>
    <h2>Modifications de mon mot de passe</h2>
    <form method="POST" action="" class="mb-4">
        <input type="hidden" name="mdp">
        <div class="form-group">
            <small>Veuillez rensigné votre email</small>
            <input type="email" class="form-control" name="email">
        </div>
        <div class="form-group">
            <small>Ancien mot de passe</small>
            <input type="password" class="form-control" name="mdp">
        </div>
        <div class="form-group">
            <small>Nouveau mot de passe</small>
            <input type="password" class="form-control" name="mdp1">
        </div>
        <div class="form-group">
            <small>Retapez nouveau mot de passe</small>
            <input type="password" class="form-control" name="mdp2">
        </div>
        <div class="from-group">
            <input type="submit" class="btn btn-primary" value="Modifier">
        </div>
    </form>
    <?php
}
?>


<?php
if( isset($_GET['action']) && $_GET['action'] == 'photo' ){
    ?>
    <h2>Modifications de ma photo</h2>
    <form method="POST" class="mb-4" enctype="multipart/form-data">
        <input type="hidden" name="photo">
        <div class="form-group">
            <small>Nouvelle photo :</small>
            <input type="file" class="form-control" name="photo">
        </div>
        <div class="from-group">
            <input type="submit" class="btn btn-primary" value="Modifier">
        </div>
    </form>
    <?php
}
?>



<div class="row">
    <div class="col-6">
        <a href="?action=photo" class="photo"><img src="<?= URL . 'photo/users/' . $_SESSION['membre']['photo'] ?>" alt="photo" class="userpic"></a>
    </div>
    <div class="col-6">
        <h3>Vos informations :</h3>
        <p><span class="av">Pseudo :</span><span class="ap"><?= $_SESSION['membre']['pseudo'] ?></span></p>
        <p><span class="av">Nom :</span><span class="ap"><?= $_SESSION['membre']['nom'] ?></span></p>
        <p><span class="av">Prenom :</span><span class="ap"><?= $_SESSION['membre']['prenom'] ?></span></p>
        <p><span class="av">Email :</span><span class="ap"><?= $_SESSION['membre']['email'] ?></span></p>
        <p><span class="av">Date d'enregistrement :</span><span class="ap"><?= $_SESSION['membre']['date_enregistrement'] ?></span></p>
        <a href="?action=modif-info" class="btn btn-info">Modifier mes informations</a>
        <a href="?action=modif-mdp" class="btn btn-info">Modifier mon mot de passe</a>
    </div>
</div>






<?php

// $photo_bdd = '';
    
// function photo(){
//     if( !empty($_FILES['photo']['name']) ){
//         return $_POST['pseudo'] . '_' . $_POST['prenom'] . '_' . $_POST['nom'] . '_' . $_FILES['photo']['name'];
//     } else {
//         return 'generic.png';
//     }
// }
// $photo_bdd = photo();
// $dossier_photo = $_SERVER['DOCUMENT_ROOT'] . URL . 'photo/users/';
// $ext_auto = ['image/jpeg', 'image/png', 'image/gif'];

// if( in_array($_FILES['photo']['type'], $ext_auto) ){
//     move_uploaded_file($_FILES['photo']['tmp_name'], $dossier_photo . $photo_bdd);
// } else {
//     $content = '<div class="alert alert-danger">La photo n\'a pas été enregistrer, formats accepté : .jpeg, .jpg, .png, .gif</div>';
// }

?>

<?php
require_once('inc/footer.php');