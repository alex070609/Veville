<?php
require_once('inc/init.php');
$title = 'Accueil';
require_once('inc/header.php');

if( !empty($_SESSION['message'])){
    echo $_SESSION['message'];
    unset($_SESSION['message']);
}
?>






<?php
require_once('inc/footer.php');
?>