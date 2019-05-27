-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  ven. 24 mai 2019 à 11:50
-- Version du serveur :  5.7.24
-- Version de PHP :  7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `veville`
--

-- --------------------------------------------------------

--
-- Structure de la table `agences`
--

DROP TABLE IF EXISTS `agences`;
CREATE TABLE IF NOT EXISTS `agences` (
  `idagences` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `titre` varchar(200) NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `ville` varchar(50) NOT NULL,
  `cp` int(3) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(200) NOT NULL,
  PRIMARY KEY (`idagences`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `agences`
--

INSERT INTO `agences` (`idagences`, `titre`, `adresse`, `ville`, `cp`, `description`, `photo`) VALUES
(1, 'Paris', '9 rue rivoli', 'Paris', 75000, 'agence de paris nord', 'Paris1_veville-roissy.jpg'),
(2, 'Nanterre', '24 rue de paris', 'Nanterre', 92014, 'agence de nanterre', ''),
(3, 'Marines', '9 rue du général leclerc', 'Marines', 95640, 'agence de la ville de marines', '');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `idcommande` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `membre_idmembre` int(10) UNSIGNED NOT NULL,
  `vehicule_idvehicule` int(10) UNSIGNED NOT NULL,
  `vehicule_idagences` int(10) UNSIGNED NOT NULL,
  `date_heure_depart` date NOT NULL,
  `date_heure_fin` date NOT NULL,
  `prix_total` int(3) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`idcommande`),
  KEY `fk_commande_membre1_idx` (`membre_idmembre`),
  KEY `fk_vehicule_idvehicule_idx` (`vehicule_idvehicule`),
  KEY `fk_vehicule_idagence_idx` (`vehicule_idagences`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`idcommande`, `membre_idmembre`, `vehicule_idvehicule`, `vehicule_idagences`, `date_heure_depart`, `date_heure_fin`, `prix_total`, `date_enregistrement`) VALUES
(1, 2, 1, 1, '2019-05-21', '2019-05-31', 550, '2019-05-21 16:04:42'),
(2, 2, 1, 1, '2019-06-01', '2019-06-08', 392, '2019-05-22 09:08:03'),
(3, 2, 1, 1, '2019-06-09', '2019-06-15', 336, '2019-05-22 09:18:03'),
(8, 2, 2, 1, '2019-05-24', '2019-06-08', 480, '2019-05-24 09:23:49');

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

DROP TABLE IF EXISTS `membre`;
CREATE TABLE IF NOT EXISTS `membre` (
  `idmembre` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(20) NOT NULL,
  `mdp` varchar(100) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `civilite` enum('m','f') NOT NULL,
  `statut` int(3) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idmembre`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `membre`
--

INSERT INTO `membre` (`idmembre`, `pseudo`, `mdp`, `nom`, `prenom`, `email`, `civilite`, `statut`, `date_enregistrement`, `photo`) VALUES
(2, 'Alex070609', '3f347804b873f9741686cffa33c8dcee', 'BEE', 'Axel', 'alex070609033@gmail.com', 'm', 1, '2019-05-20 10:54:00', 'Alex070609_Axel_BEE_girt.png'),
(3, 'toto', 'e1f65043fab46b6c5adc1f0d6aad8f47', 'toto', 'toto', 'toto@toto.fr', 'm', 0, '2019-05-20 16:01:50', NULL),
(4, 'tata', '26dca018fd566e2f50f0c682cc7d673d', 'tata', 'tata', 'tata@tata.fr', 'f', 0, '2019-05-20 16:13:00', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `vehicule`
--

DROP TABLE IF EXISTS `vehicule`;
CREATE TABLE IF NOT EXISTS `vehicule` (
  `idvehicule` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `agences_idagences` int(10) UNSIGNED DEFAULT NULL,
  `titre` varchar(200) NOT NULL,
  `marque` varchar(50) NOT NULL,
  `modele` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(200) NOT NULL,
  `prix_journalier` int(3) NOT NULL,
  PRIMARY KEY (`idvehicule`),
  KEY `fk_vehicule_agences1_idx` (`agences_idagences`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `vehicule`
--

INSERT INTO `vehicule` (`idvehicule`, `agences_idagences`, `titre`, `marque`, `modele`, `description`, `photo`, `prix_journalier`) VALUES
(1, 1, 'BMW i8 noire/blanche', 'BMW', 'I8', 'BMW i8 noire/blanche', 'BMWI8_1_bmw-i8.jpg', 56),
(2, 1, 'Citroën C3 blanche', 'Citroën', 'C3', 'Citroën C3 blanche', 'CitroënC3_citroen-c3.jpg', 32),
(3, 2, 'BMW i8 noire/blanche', 'BMW', 'I8', 'BMW i8 noire/blanche', 'BMWI8_0_bmw-i8.jpg', 56);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `fk_commande_membre1` FOREIGN KEY (`membre_idmembre`) REFERENCES `membre` (`idmembre`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vehicule_idagence` FOREIGN KEY (`vehicule_idagences`) REFERENCES `vehicule` (`agences_idagences`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vehicule_idvehicule` FOREIGN KEY (`vehicule_idvehicule`) REFERENCES `vehicule` (`idvehicule`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `vehicule`
--
ALTER TABLE `vehicule`
  ADD CONSTRAINT `fk_vehicule_agences1` FOREIGN KEY (`agences_idagences`) REFERENCES `agences` (`idagences`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
