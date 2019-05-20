<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Veville | <?= $title ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="<?= URL . 'inc/css/style.css'?>">
</head>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="<?= URL ?>"></a>
        <button class="navbar-toggler" type="button" date-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse maNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item <?= ($title == 'Accueil') ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= URL ?>">Accueil</a>
                </li>
                <?php if( !isConnected() ): ?>
                    <li class="nav-item">
                        <a href="" class="nav-link" data-toggle="modal" data-target="#inscription">Inscription</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link" data-toggle="modal" data-target="#connexion">Connexion</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="" class="nav-link">Mes réservations</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link">Mon compte</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= URL.'connexion.php?action="deco"' ?>" class="nav-link">Deconnexion</a>
                    </li>
                <?php endif; ?>
                <?php if( isAdmin() ):?>
                    <li class="nav-item dropdown">
                        <a href="" class="nav-link dropdown-toggle" href="#" id="menuadmin" role="button" data-toggle="dropdown">Admin</a>
                        <div class="dropdown-menu" aria-labelledby="menuadmin">
                            <a href="" class="dropdown-item">Gestion Véhicules</a>
                            <a href="" class="dropdown-item">Gestion Membres</a>
                            <a href="" class="dropdown-item">Gestion Réservations</a>
                            <a href="" class="dropdown-item">BackOffice</a>
                        </div>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="" class="nav-link">Panier</a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<!-- modal d'incription -->
<div class="modal fade" id="inscription" tabindex="-1" role="dialog" aria-labelledby="modalinscription" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalinscription">Inscription</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="inscription.php" method="POST">
          <div class="form-row">
            <label for="pseudo">Pseudo</label>
            <input class="form-control" type="text" name="pseudo" id="pseudo">
          </div>
          <div class="form-row">
            <label for="mdp">Mot de passe</label>
            <input class="form-control" type="password" name="mdp" id="mdp">
          </div>
          <div class="form-row">
            <label for="nom">Nom</label>
            <input class="form-control" type="text" name="nom" id="nom">
            <label for="prenom">Prenom</label>
            <input class="form-control" type="text" name="prenom" id="prenom">
          </div>
          <div class="form-row">
            <label for="email">Email</label>
            <input class="form-control" type="email" name="email" id="email">
          </div>
          <div class="form-row">
            <label for="civilite">Civilitée</label>
            <select class="form-control" name="civilite" id="civilite">
              <option value="m">Homme</option>
              <option value="f">Femme</option>
            </select>
          </div>
          <button type="button" class="btn btn-secondary form-control mt-4" data-dismiss="modal">Retour</button>
          <input type="submit" value="S'inscrire" class="btn btn-primary form-control mt-4">
        </form>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<!-- modal de connexion -->
<div class="modal fade" id="connexion" tabindex="-1" role="dialog" aria-labelledby="modalconnexion" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalconnexion">Connexion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="connexion.php" method="POST">
          <label for="pseudo">pseudo</label>
          <input type="text" name="pseudo" id="pseudo" class="form-control">
          <label for="mdp">Mot de passe</label>
          <input type="password" name="mdp" id="mdp" class="form-control">
          <button type="button" class="btn btn-secondary mt-4 form-control" data-dismiss="modal">Retour</button>
          <input type="submit" class="btn btn-primary mt-4 form-control" value="Se connecter">
        </form>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>


<main class="container" style="margin-top:60px;">