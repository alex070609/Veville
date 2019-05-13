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
                        <a href="" class="nav-link">Inscription</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link">Connexion</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="" class="nav-link">Mes réservations</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link">Mon compte</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link">Deconnexion</a>
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
<main class="container">