-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 29 mars 2023 à 07:17
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projetr401`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `id_article` int(11) NOT NULL AUTO_INCREMENT,
  `contenu` varchar(400) NOT NULL,
  `date_d` date NOT NULL,
  `fk_id_auteur` varchar(30) NOT NULL,
  PRIMARY KEY (`id_article`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id_article`, `contenu`, `date_d`, `fk_id_auteur`) VALUES
(5, 'ici du contenu', '2023-08-01', 'cl'),
(8, 'nouveau contenu', '2023-03-23', 'clement2');

-- --------------------------------------------------------

--
-- Structure de la table `likedislike`
--

DROP TABLE IF EXISTS `likedislike`;
CREATE TABLE IF NOT EXISTS `likedislike` (
  `fk_id_utilisateur` varchar(30) NOT NULL,
  `fk_id_article` int(10) NOT NULL,
  `action_a` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `likedislike`
--

INSERT INTO `likedislike` (`fk_id_utilisateur`, `fk_id_article`, `action_a`) VALUES
('clement', 5, 'dislike'),
('clement2', 5, 'like'),
('clement2', 8, 'like');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  `role_r` varchar(30) NOT NULL,
  `mdp` varchar(500) NOT NULL,
  `id_utilisateur` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`nom`, `prenom`, `role_r`, `mdp`, `id_utilisateur`) VALUES
('clem', 'faux', 'publisher', '', ''),
('f', 'c', 'admin', '$2y$10$5nRi0R3Pa2doHA7.7G3z/u69NsuIhAXUEAmVAOUe6QE3K5wTZByVC', 'c'),
('f', 'c', 'admin', '$2y$10$G1sK6UVKhyaIrz1e5pac9.oQbzluReGcmEqiHyGyPak/rfW056tqa', 'cl'),
('f', 'c', 'publisher', '$2y$10$5nNvDUXWmiUcCOo2FisLmOJlV9zdZVBCvKvZcZ8YJsBcTdnZ0mXPa', 'clement'),
('f', 'c', 'publisher', '$2y$10$6JypdhNc9rsl8EbzDvSYnOwVQioV2MCuwbC7h17cp9So.gpNCfqPG', 'clement2');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
