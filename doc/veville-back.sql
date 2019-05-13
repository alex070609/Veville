-- MySQL Workbench Synchronization
-- Generated: 2019-05-13 20:23
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: stagiaire

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE TABLE IF NOT EXISTS `veville`.`vehicule` (
  `idvehicule` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idagences` INT(10) UNSIGNED NOT NULL,
  `titre` VARCHAR(200) NOT NULL,
  `marque` VARCHAR(50) NOT NULL,
  `modele` VARCHAR(50) NOT NULL,
  `description` TEXT NOT NULL,
  `photo` VARCHAR(200) NOT NULL,
  `prix_journalier` INT(3) NOT NULL,
  PRIMARY KEY (`idvehicule`),
  INDEX `fk_vehicule_agences1_idx` (`idagences` ASC) VISIBLE,
  CONSTRAINT `fk_vehicule_agences1`
    FOREIGN KEY (`idagences`)
    REFERENCES `veville`.`agences` (`idagences`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `veville`.`agences` (
  `idagences` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `titre` VARCHAR(200) NOT NULL,
  `adresse` VARCHAR(50) NOT NULL,
  `ville` VARCHAR(50) NOT NULL,
  `cp` INT(3) NOT NULL,
  `description` TEXT NOT NULL,
  `photo` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`idagences`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `veville`.`membre` (
  `idmembre` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pseudo` VARCHAR(20) NOT NULL,
  `mdp` VARCHAR(100) NOT NULL,
  `nom` VARCHAR(20) NOT NULL,
  `prenom` VARCHAR(20) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `civilite` ENUM('m', 'f') NOT NULL,
  `statut` INT(3) NOT NULL,
  `date_enregistrement` DATETIME NOT NULL,
  PRIMARY KEY (`idmembre`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `veville`.`commande` (
  `idcommande` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idmembre` INT(10) UNSIGNED NULL DEFAULT NULL,
  `idvehicule` INT(10) UNSIGNED NOT NULL,
  `idagences` INT(10) UNSIGNED NULL DEFAULT NULL,
  `date_heure_depart` DATETIME NOT NULL,
  `date_heure_fin` DATETIME NOT NULL,
  `prix_total` INT(3) NOT NULL,
  `date_enregistrement` DATETIME NOT NULL,
  PRIMARY KEY (`idcommande`),
  INDEX `fk_membre_has_Vehicule_Vehicule1_idx` (`idvehicule` ASC) VISIBLE,
  INDEX `fk_membre_has_Vehicule_membre1_idx` (`idmembre` ASC) VISIBLE,
  INDEX `fk_commande_vehicule1_idx` (`idagences` ASC) VISIBLE,
  CONSTRAINT `fk_membre_has_Vehicule_membre1`
    FOREIGN KEY (`idmembre`)
    REFERENCES `veville`.`membre` (`idmembre`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_membre_has_Vehicule_Vehicule1`
    FOREIGN KEY (`idvehicule`)
    REFERENCES `veville`.`vehicule` (`idvehicule`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_commande_vehicule1`
    FOREIGN KEY (`idagences`)
    REFERENCES `veville`.`vehicule` (`idagences`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
