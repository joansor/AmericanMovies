-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  jeu. 06 fév. 2020 à 21:05
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP :  7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `mydb`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_user` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `appartient`
--

DROP TABLE IF EXISTS `appartient`;
CREATE TABLE IF NOT EXISTS `appartient` (
  `Genre_id_g` int(11) NOT NULL,
  `Films_id_f` int(11) NOT NULL,
  PRIMARY KEY (`Genre_id_g`,`Films_id_f`),
  KEY `fk_Appartient_Films1_idx` (`Films_id_f`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `appartient`
--

INSERT INTO `appartient` (`Genre_id_g`, `Films_id_f`) VALUES
(1, 2),
(2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `artistes`
--

DROP TABLE IF EXISTS `artistes`;
CREATE TABLE IF NOT EXISTS `artistes` (
  `id_a` int(11) NOT NULL AUTO_INCREMENT,
  `nom_a` varchar(255) NOT NULL,
  `prenom_a` varchar(255) NOT NULL,
  `photo_a` varchar(255) DEFAULT NULL,
  `biographie_a` varchar(255) DEFAULT NULL,
  `date_de_naissance_a` date NOT NULL,
  PRIMARY KEY (`id_a`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `artistes`
--

INSERT INTO `artistes` (`id_a`, `nom_a`, `prenom_a`, `photo_a`, `biographie_a`, `date_de_naissance_a`) VALUES
(1, 'Clint ', 'Eastwood', 'assets/images/clint.jpg', 'Celui-ci l\'exhorte à aller au-delà des apparences et à trouver la réponse à la question qui hante constamment ses pensées : qu\'est-ce que la Matrice?omantiques dans le superbe film \"Sur la route de Madison\". En 2003, il joue et réalise \"Mystic River\", pui', '1930-05-31'),
(2, 'REEVES', 'Keanu Charles', 'assets/images/Keanu_Reeves.png', 'Keanu Reeves passe son enfance en Australie jusqu\'au divorce de ses parents. Il part alors à New York, puis à Toronto, au Canada, avec sa mère et ses deux soeurs. Abandonnant les études pour s\'orienter vers le métier de comédien, il fait ses débuts à la t', '1964-09-02'),
(3, 'Wachowski', 'lana', 'assets/images/lana.jpg', 'Lana Wachowski (née Laurence Wachowski le 21 juin 1965 à Chicago1) et Lilly Wachowski (née Andrew Wachowski le 29 décembre 1967 à Chicago2) sont deux sœurs réalisatrices américaines. Toutes deux femmes trans, elles ont d\'abord été connues sous les diminut', '1965-06-21');

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

DROP TABLE IF EXISTS `commentaires`;
CREATE TABLE IF NOT EXISTS `commentaires` (
  `Films_id_f` int(11) NOT NULL,
  `commentaire_c` longtext DEFAULT NULL,
  `Utilisateurs_id_u` int(11) NOT NULL,
  PRIMARY KEY (`Films_id_f`,`Utilisateurs_id_u`),
  KEY `fk_commentaires_Films1_idx` (`Films_id_f`),
  KEY `fk_Commentaires_Utilisateurs1_idx` (`Utilisateurs_id_u`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `films`
--

DROP TABLE IF EXISTS `films`;
CREATE TABLE IF NOT EXISTS `films` (
  `id_f` int(11) NOT NULL AUTO_INCREMENT,
  `titre_f` varchar(255) NOT NULL,
  `poster_f` varchar(255) NOT NULL,
  `annee_f` year(4) NOT NULL,
  `resume_f` varchar(255) NOT NULL,
  `video_f` varchar(255) NOT NULL,
  PRIMARY KEY (`id_f`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `films`
--

INSERT INTO `films` (`id_f`, `titre_f`, `poster_f`, `annee_f`, `resume_f`, `video_f`) VALUES
(1, 'GRAND TORINO', 'assets/images/grantorino.jpg', 2009, 'Vétéran de la guerre de Corée, Walt n\'aime pas ses voisins asiatiques. Les événements vont forcer Walt à défendre ses voisins face à un gang local.\r\n', 'https://www.dailymotion.com/embed/video/x7bbo4d'),
(2, 'THE MATRIX', 'assets/images/thematrix.jpg', 1999, 'Programmeur anonyme dans un service administratif le jour, Thomas Anderson devient Neo la nuit venue. Sous ce pseudonyme, il est l\'un des pirates les plus recherchés du cyber-espace. A cheval entre deux mondes, Neo est assailli par d\'étranges songes et de', 'https://www.dailymotion.com/embed/video/xk96a3');

-- --------------------------------------------------------

--
-- Structure de la table `genre`
--

DROP TABLE IF EXISTS `genre`;
CREATE TABLE IF NOT EXISTS `genre` (
  `id_g` int(11) NOT NULL AUTO_INCREMENT,
  `genre_du_film` varchar(255) NOT NULL,
  PRIMARY KEY (`id_g`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `genre`
--

INSERT INTO `genre` (`id_g`, `genre_du_film`) VALUES
(1, 'Action'),
(2, 'Drama');

-- --------------------------------------------------------

--
-- Structure de la table `jouer`
--

DROP TABLE IF EXISTS `jouer`;
CREATE TABLE IF NOT EXISTS `jouer` (
  `Films_id_f` int(11) NOT NULL,
  `Artistes_id_a` int(11) NOT NULL,
  PRIMARY KEY (`Films_id_f`,`Artistes_id_a`),
  KEY `fk_Jouer_Artistes1_idx` (`Artistes_id_a`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `jouer`
--

INSERT INTO `jouer` (`Films_id_f`, `Artistes_id_a`) VALUES
(1, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `realiser`
--

DROP TABLE IF EXISTS `realiser`;
CREATE TABLE IF NOT EXISTS `realiser` (
  `Films_id_f` int(11) NOT NULL,
  `Artistes_id_a` int(11) NOT NULL,
  PRIMARY KEY (`Films_id_f`,`Artistes_id_a`),
  KEY `fk_Realiser_Artistes1_idx` (`Artistes_id_a`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `realiser`
--

INSERT INTO `realiser` (`Films_id_f`, `Artistes_id_a`) VALUES
(1, 1),
(2, 3);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id_u` int(11) NOT NULL AUTO_INCREMENT,
  `type_user` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_u`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `appartient`
--
ALTER TABLE `appartient`
  ADD CONSTRAINT `fk_Appartient_Films1` FOREIGN KEY (`Films_id_f`) REFERENCES `films` (`id_f`),
  ADD CONSTRAINT `fk_Appartient_Genre1` FOREIGN KEY (`Genre_id_g`) REFERENCES `genre` (`id_g`);

--
-- Contraintes pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD CONSTRAINT `fk_Commentaires_Utilisateurs1` FOREIGN KEY (`Utilisateurs_id_u`) REFERENCES `utilisateurs` (`id_u`),
  ADD CONSTRAINT `fk_commentaires_Films1` FOREIGN KEY (`Films_id_f`) REFERENCES `films` (`id_f`);

--
-- Contraintes pour la table `jouer`
--
ALTER TABLE `jouer`
  ADD CONSTRAINT `fk_Jouer_Artistes1` FOREIGN KEY (`Artistes_id_a`) REFERENCES `artistes` (`id_a`),
  ADD CONSTRAINT `fk_Jouer_Films1` FOREIGN KEY (`Films_id_f`) REFERENCES `films` (`id_f`);

--
-- Contraintes pour la table `realiser`
--
ALTER TABLE `realiser`
  ADD CONSTRAINT `fk_Realiser_Artistes1` FOREIGN KEY (`Artistes_id_a`) REFERENCES `artistes` (`id_a`),
  ADD CONSTRAINT `fk_Realiser_Films1` FOREIGN KEY (`Films_id_f`) REFERENCES `films` (`id_f`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
