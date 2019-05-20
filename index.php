<?php
require_once('inc/init.php');
$title = 'Accueil';
require_once('inc/header.php');

if( !empty($_SESSION['message'])){
    echo $_SESSION['message'];
    unset($_SESSION['message']);
}
?>
<script src="inc/js/date.js"></script>
<div class="text-center">
    <h1>VEVILLE</h1>
    <h2>Location de véhicules à petit prix !</h2>
</div>
<img src="photo/voitures.png" alt="Voitures" style="width:100%;height:auto">
<form method="post">
    <label for="marque">Marque</label>
    <select name="marque" id="marque">
        <?php 
        $vehicules = execReq( "SELECT * FROM vehicule ORDER BY marque"); 
        while( $vehicule = $vehicules->fetch() ){
        ?>
        <option value="<?= $vehicule['idvehicule'] ?>"><?= $vehicule['marque'] ?></option>
        <?php
        } ?>
    </select>
    <label for="date_heure_depart">Début de la loc</label>
    <input type="text" name="date_heure_depart" id="date_depart">
    <input type="time" name="date_heure_depart" id="heure_depart">
    <label for="date_heure_fin">Fin de la loc</label>
    <input type="text" name="date_heure_fin" id="date_fin">
    <input type="time" name="date_heure_fin" id="heure_fin">
</form>

<?php
require_once('inc/footer.php');
?>