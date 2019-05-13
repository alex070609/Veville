<?php
// l'éxistence du tableau membre dans la session indique que l'utilisateur s'est correctement connecté
function isConnected(){
    if( isset($_SESSION['membre']) ){
        return true;
    } else {
        return false;
    }
};

// un admin ets un mmebre connecté dont le statut vaut 1
function isAdmin(){
    if( isConnected() && $_SESSION['membre']['statut'] == 1 ){
        return true;
    } else{
        return false;
    }
};

// sanitize + bind + verif erreurs BDD avec message d'erreur
function execReq( $req, $params=array() ){
    global $pdo;
    $r = $pdo->prepare($req);
    if( !empty($params) ){
        //sanitize
        // bind
        foreach($params as $key => $value){
            $params[$key] = htmlspecialchars($value,ENT_QUOTES);
            $r->bindValue($key, $params[$key],PDO::PARAM_STR);
        }
    }
    $r->execute();
    if( !empty( $r->errorInfo()[2] ) ){
        die('Erreur rencontrée - merci de contacter l\'administrateur du site');
    }
    return $r;
};
