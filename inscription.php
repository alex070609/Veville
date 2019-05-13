<?php
if( !empty($_POST) ){
    //le formulaire est posté
    $nb_champs_vide = 0;
    foreach( $_POST as $value ){
        if( empty($value) ) $nb_champs_vide++;
    }
    if( $nb_champs_vide > 0 ){
        $content .= '<div class="alert alert-danger">Il manque '.$nb_champs_vide.' information(s)</div>';
    }

    //verif du pseudo
    $verif_pseudo = preg_match('#^[\w.-]{3,20}$#', $_POST['pseudo']);
    if( !$verif_pseudo ){
        $content .= '<div class="alert alert-danger">Le pseudo doit comporter entre 3 et 20 caractères (a à z, A à Z, 0 à 9, -, _, .)</div>';
    }
    // verif CP
    $verif_cp = preg_match('#^[0-9]{5}$#', $_POST['code_postal']);
    if( !$verif_cp ){
        $content .= '<div class="alert alert-danger">Le code postal n\'est pas valide</div>';
    }
    
    //verif email
    $verif_email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if( !$verif_email ){
        $content .= '<div class="alert alert-danger">L\'email n\'est pas valide</div>';
    }

    if( !$content ){
        // je n'ai pas d'erreurs
        // contrôler l'unicité du pseudo
        $verif_membre = execReq('SELECT * FROM membre WHERE pseudo=:pseudo', array('pseudo' => $_POST['pseudo']));
        if( $verif_membre->rowCount() > 0 ){
            $content .= '<div class="alert alert-danger">Ce pseudo est indisponible, merci d\'en choisir un autre</div>';
        } else {
            extract($_POST);
            // génère des variables avec le noms des index
            execReq('INSERT INTO membre VALUES (NULL, :pseudo, :mdp, :nom, :prenom, :email, :civilite, :ville, :code_postal, :adresse, 0)', array(
                'pseudo'=> $pseudo,
                'mdp' => md5($mdp . SALT),
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'civilite' => $civilite,
                'ville' => $ville,
                'code_postal' => $code_postal,
                'adresse' => $adresse
            ));
            $inscription = true;
            $content .= '<div class="alert alert-success">Vous êtes inscrit ! <a href="' . URL . 'connexion.php">Cliquez ici pour vous connecter</a></div>';
        }
    }
}