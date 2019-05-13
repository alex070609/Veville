<?php

// Fuseau horaire
date_default_timezone_set('Europe/Paris');

// Session
session_start();

// Connexion BDD
$pdo = new PDO(
    'mysql:host=localhost;dbname=veville',
    'root',
    '',
    array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    )
);

// Contantes de site
define('URL','/veville/');
define('SALT','Mo1d3pa223!!c0mpl1q33!!');

// initialisation de variables
$content = '';
$left_content = '';
$right_content = '';

// Inclusion du fichier de fonctions
require_once('function.php');