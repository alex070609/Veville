<?php

if( isset($_GET['action']) && $_GET['action'] == 'deconnexion' ){
    //session_destroy();
    unset($_SESSION['membre']);
    header('location:'.URL);
    exit();
}

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
            header('location:'.URL.'compte.php');
            exit();
        }
    }
}
    
    
    /*
    
    } else {
            // je n'ai pas trouver l'utilisateur
            $content .= '<div class="alert alert-danger">Erreur sur les identifiants, ou utilisateur introuvable</div>';
        }
    } else {
        $content .= '<div class="alert alert-danger">Merci de compléter tout les champs</div>';
    }

    */