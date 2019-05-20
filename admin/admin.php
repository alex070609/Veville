<?php
require_once('../inc/init.php');
$title = 'backoffice';
$acc  = false;
require_once('../inc/header.php');
?>



    <div role="main" class="px-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Récapitulatif des ventes</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">Partager</button>
            <button type="button" class="btn btn-sm btn-outline-secondary">Exporter</button>
          </div>
        </div>
      </div>

      <canvas class="my-4 w-100 chartjs-render-monitor" id="myChart" width="769" height="324" style="display: block; width: 769px; height: 324px;"></canvas>

      <h2>Dernières locations</h2>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
            <?php $commande = execReq( "SELECT * FROM commande"); ?>
          <thead>
            <tr>
            <?php
            for($i=0;$i<$commande->columnCount();$i++){
                $colonne = $commande->getColumnMeta($i);
                if( $colonne['name'] != "vehicule_idvehicule" && $colonne['name'] != "vehicule_idagences" ){
                    if( $colonne['name'] == "membre_idmembre" ){
                        ?> <th>Membre</th> <?php
                        continue;
                    }
                ?>
                    <th><?= ucfirst($colonne['name']) ?></th>
                <?php
                }
                ?> <th>Voir</th> <?php
            } ?>
            </tr>
          </thead>
          <tbody>
              <?php while( $ligne = $commande->fetch()){
                  ?><tr><?php
                    foreach($ligne as $key => $value){
                        if($key == "vehicule_idvehicule"){
                            continue;
                        }
                        if($key == "vehicule_idagence"){
                            continue;
                        }
                        if($key == 'membre_idmembre'){
                            $membre = execReq( "SELECT * FROM membre WHERE idmembre=:idmembre", array(
                                'idmembre' => $value
                            ));
                            $leMembre = $membre->fetch();
                            $value = $leMembre['pseudo'];
                        }?>
                        <td><?= $value ?></td><?php
                    }
                    ?> <td>Voir</td> <?php
                  ?></tr><?php
                } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
  <script>
  (function () {
    'use strict'
  
    feather.replace()
  
    // Graphs
    var ctx = document.getElementById('myChart')
    // eslint-disable-next-line no-unused-vars
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: [
          '',
          '',
          '',
          '',
          '',
          '',
          ''
        ],
        datasets: [{
          data: [
            0,
            0,
            0,
            0,
            0,
            0,
            0
          ],
          lineTension: 0,
          backgroundColor: 'transparent',
          borderColor: '#007bff',
          borderWidth: 4,
          pointBackgroundColor: '#007bff'
        }]
      },
      options: {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: false
            }
          }]
        },
        legend: {
          display: false
        }
      }
    })
  }())  
  </script>
<?php
require_once('../inc/footer.php');
