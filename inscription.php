<?php
require_once('inc/init.php');
$message  = '';
$_SESSION['message'] = '';
if( !empty($_POST) ){
    //le formulaire est posté
    $nb_champs_vide = 0;
    foreach( $_POST as $value ){
        if( empty($value) ) $nb_champs_vide++;
    }
    if( $nb_champs_vide > 0 ){
        $message .= '<div class="alert alert-danger">Il manque '.$nb_champs_vide.' information(s)</div>';
    }

    //verif du pseudo
    $verif_pseudo = preg_match('#^[\w.-]{3,20}$#', $_POST['pseudo']);
    if( !$verif_pseudo ){
        $message .= '<div class="alert alert-danger">Le pseudo doit comporter entre 3 et 20 caractères (a à z, A à Z, 0 à 9, -, _, .)</div>';
    }

    if( $nb_champs_vide > 0 && $verif_pseudo == false ){
        $_SESSION['message'] = $message;
        header('location:'.URL);
        exit();
    }

    if( $nb_champs_vide == 0 && $verif_pseudo == true ){
        // je n'ai pas d'erreurs
        // contrôler l'unicité du pseudo
        $verif_membre = execReq('SELECT * FROM membre WHERE pseudo=:pseudo', array('pseudo' => $_POST['pseudo']));
        if( $verif_membre->rowCount() > 0 ){
            $_SESSION['message'] = '<div class="alert alert-danger">Ce pseudo est indisponible, merci d\'en choisir un autre</div>';
            header('location:'.URL);
            exit();
        } else {
            extract($_POST);
            // génère des variables avec le noms des index
            execReq('INSERT INTO membre VALUES (NULL, :pseudo, :mdp, :nom, :prenom, :email, :civilite, 0 , now())', array(
                'pseudo'=> $pseudo,
                'mdp' => md5($mdp . SALT),
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'civilite' => $civilite
            ));
            $inscription = true;
            $_SESSION['message'] = '<div class="alert alert-success">Vous êtes inscrit ! <a href="' . URL . 'connexion.php">Cliquez ici pour vous connecter</a></div>';
            header('location:'.URL);
            exit();
        }
    }
}