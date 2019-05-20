<?php
require_once('inc/init.php');

if( isset($_GET['action']) && $_GET['action'] == 'deconnexion' ){
    //session_destroy();
    unset($_SESSION['membre']);
    $message .= '<div class="alert alert-success">Vous vous êtes déconnecté</div>';
    $_SESSION['message'] = $message;
    header('location:'.URL);
    exit();
}

$message = '';
if( !empty($_POST) ){
    // le formulaire est posté
    if( !empty($_POST['pseudo']) && !empty($_POST['mdp']) ){
        // je check si je trouve l'utilisateur avec son mdp dans la BDD 
        $resultat = execReq('SELECT * FROM membre WHERE pseudo=:pseudo AND mdp=:mdp', array(
            'pseudo' => $_POST['pseudo'],
            'mdp' => md5($_POST['mdp'] . SALT)
        ));
        if( $resultat->rowCount() != 0 ){
            // j'ai trouver l'utilisateur
            $membre = $resultat->fetch();
            unset($membre['mdp']); // je retire le champ MDP crypté
            $_SESSION['membre'] = $membre;
            unset($_SESSION['post_compte']);
            header('location:'.URL.'compte.php');
            exit();
        } else {
            // je n'ai pas trouver l'utilisateur
            $message .= '<div class="alert alert-danger">Erreur sur les identifiants, ou utilisateur introuvable</div>';
            $_SESSION['message'] = $message;
            $_SESSION['post_compte'] = $_POST;
            header('location:'.URL);
            exit();
        }
    } else {
        $message .= '<div class="alert alert-danger">Merci de compléter tout les champs</div>';
        $_SESSION['message'] = $message;
        header('location:'.URL);
        exit();
    }
}
