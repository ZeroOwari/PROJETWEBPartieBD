-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : dim. 30 mars 2025 à 17:51
-- Version du serveur : 11.5.2-MariaDB
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `web4all`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `ID-admin` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Id des admins',
  `Prenom-admin` varchar(60) NOT NULL COMMENT 'Prenom des admins',
  `Nom-admin` varchar(60) NOT NULL COMMENT 'Nom des admins',
  `Email-admin` varchar(255) NOT NULL COMMENT 'Email de log',
  `MDP-admin` varchar(60) NOT NULL COMMENT 'Mot de passe',
  PRIMARY KEY (`ID-admin`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Table des admins qui gèrent le site';

-- --------------------------------------------------------

--
-- Structure de la table `ajout`
--

DROP TABLE IF EXISTS `ajout`;
CREATE TABLE IF NOT EXISTS `ajout` (
  `ID-entreprise` int(11) NOT NULL COMMENT 'clé étrangère de l''entreprise ajoutée',
  `ID-auteur` int(11) NOT NULL COMMENT 'identifiant de l''auteur du changement'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cv`
--

DROP TABLE IF EXISTS `cv`;
CREATE TABLE IF NOT EXISTS `cv` (
  `ID-CV` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifiant du cv d''un étudiant',
  `ID-etudiant-CV` int(11) NOT NULL COMMENT 'identifiant de l''etudiant a qui appartient le cv (clé étrangère table Etudiant)',
  `CheminAcces-CV` varchar(255) NOT NULL COMMENT 'chemin d''acces vers le cv (local ou url)',
  PRIMARY KEY (`ID-CV`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `entreprise`
--

DROP TABLE IF EXISTS `entreprise`;
CREATE TABLE IF NOT EXISTS `entreprise` (
  `ID-entreprise` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifiant de l''entreprise',
  `Nom-entreprise` varchar(60) NOT NULL COMMENT 'Nom de l''entreprise',
  `Description-entreprise` varchar(2000) DEFAULT NULL COMMENT 'description de l''entreprise',
  `Email-entreprise` varchar(255) NOT NULL COMMENT 'email de l''entreprise',
  `Telephone-entreprise` varchar(15) DEFAULT NULL COMMENT 'numéro de téléphone de l''entreprise',
  `Note-entreprise` int(11) DEFAULT NULL COMMENT 'moyenne des evaluations de l''entreprise',
  PRIMARY KEY (`ID-entreprise`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

DROP TABLE IF EXISTS `etudiant`;
CREATE TABLE IF NOT EXISTS `etudiant` (
  `ID-etudiant` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Identifiant étudiant',
  `Prenom-etudiant` int(60) NOT NULL COMMENT 'Prenom de l''etudiant',
  `Nom-etudiant` int(60) NOT NULL COMMENT 'Nom de l''etudiant',
  `Email-etudiant` int(255) NOT NULL COMMENT 'Mail de log compte etudiant',
  `MDP-etudiant` int(60) NOT NULL COMMENT 'Mot de passe acces au compte etudiant',
  `Telephone-etudiant` varchar(15) DEFAULT NULL COMMENT 'Num de téléphone étudiant',
  `DateNaissance-etudiant` date DEFAULT NULL COMMENT 'date de naissance de l''etudiant',
  `ID-CV` int(11) NOT NULL COMMENT 'Identifiant du cv de l''etudiant (clé etrangère table CV)',
  `ID-promotion-etudiant` int(11) NOT NULL COMMENT 'identifiant de la promotion (clé étrangère table Promotion)',
  PRIMARY KEY (`ID-etudiant`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `favoris`
--

DROP TABLE IF EXISTS `favoris`;
CREATE TABLE IF NOT EXISTS `favoris` (
  `ID-etudiant` int(11) NOT NULL COMMENT 'identifiant de l''etudiant ayant ajouté une offre a sa wishlist',
  `ID-offre` int(11) NOT NULL COMMENT 'Identifiant de l''offre ajoutée'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `offrestage`
--

DROP TABLE IF EXISTS `offrestage`;
CREATE TABLE IF NOT EXISTS `offrestage` (
  `ID-offre` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifiant de l''offre',
  `Nom-offre` int(60) NOT NULL COMMENT 'Nom de l''offre',
  `Description-offre` varchar(2000) NOT NULL COMMENT 'Description de l''offre',
  `Competences-offre` varchar(2000) NOT NULL COMMENT 'Compétences requises ou demandées pour l''offre',
  `Debut-offre` date NOT NULL COMMENT 'Date début de l''offre',
  `Fin-offre` date NOT NULL COMMENT 'Date fin de l''offre',
  `ID-entreprise` int(11) NOT NULL COMMENT 'Identifiant de l''entreprise où se deroule l''offre',
  PRIMARY KEY (`ID-offre`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pilotepromo`
--

DROP TABLE IF EXISTS `pilotepromo`;
CREATE TABLE IF NOT EXISTS `pilotepromo` (
  `ID-pilote` int(20) NOT NULL AUTO_INCREMENT COMMENT 'Identifiant des piotes',
  `Prenom-pilote` varchar(60) NOT NULL COMMENT 'Prenom des pilotes',
  `Nom-pilote` varchar(60) NOT NULL COMMENT 'Nom des pilotes',
  `Email-pilote` varchar(255) NOT NULL COMMENT 'Email de log pilote',
  `MDP-pilote` varchar(60) NOT NULL COMMENT 'Mot de passe compte pilote',
  PRIMARY KEY (`ID-pilote`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `postule`
--

DROP TABLE IF EXISTS `postule`;
CREATE TABLE IF NOT EXISTS `postule` (
  `ID-etudiant` int(11) NOT NULL COMMENT 'Identifiant de l''etudiant qui postule',
  `ID-offre` int(11) NOT NULL COMMENT 'Identifiant de l''offre postulée',
  `Date-postule` date NOT NULL COMMENT 'Date ou l''etudiant postule'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `promotion`
--

DROP TABLE IF EXISTS `promotion`;
CREATE TABLE IF NOT EXISTS `promotion` (
  `ID-promo` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Identifiant des promos',
  `Nom-promo` varchar(60) NOT NULL COMMENT 'Nom des promos',
  `Debut-promo` date NOT NULL COMMENT 'année de début de la promo',
  `Fin-promo` date NOT NULL COMMENT 'année de fin de la promo',
  PRIMARY KEY (`ID-promo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `publication`
--

DROP TABLE IF EXISTS `publication`;
CREATE TABLE IF NOT EXISTS `publication` (
  `ID-offre` int(11) NOT NULL COMMENT 'Identifiant de l''offre publiée',
  `ID-auteur` int(11) NOT NULL COMMENT 'Identifiant de l''auteur de la publication'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
