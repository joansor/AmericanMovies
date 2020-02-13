-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 12 fév. 2020 à 21:18
-- Version du serveur :  5.7.26
-- Version de PHP :  7.2.18

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
  `biographie_a` text,
  `date_de_naissance_a` date NOT NULL,
  PRIMARY KEY (`id_a`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `artistes`
--

INSERT INTO `artistes` (`id_a`, `nom_a`, `prenom_a`, `photo_a`, `biographie_a`, `date_de_naissance_a`) VALUES
(15, 'De Niro', 'Robert', 'robert-de_niro.jpg', '', '1970-01-01'),
(16, 'Pacino', 'Al', 'al-pacino.jpg', '', '1970-01-01'),
(17, 'Scorsese', 'Martin', 'martin-scorsese.jpg', '', '1970-01-01'),
(18, 'Liotta', 'Ray', 'ray-liotta.jpg', '', '1970-01-01'),
(19, 'Pesci', 'Joe', 'joe-pesci.jpg', '', '1970-01-01'),
(20, 'Leone', 'Sergio', 'sergio-leone.jpg', '', '1970-01-01'),
(21, 'Fincher', 'David', 'david-fincher.jpg', '', '1970-01-01'),
(22, 'Fonda', 'Henry', 'henry-fonda.jpg', '', '1970-01-01'),
(23, 'Bronson', 'Charles', 'charles-bronson.jpg', '', '1970-01-01'),
(24, 'Wolff', 'Frank', 'frank-wolff.jpg', '', '1970-01-01');

-- --------------------------------------------------------

--
-- Structure de la table `artistes_categories`
--

DROP TABLE IF EXISTS `artistes_categories`;
CREATE TABLE IF NOT EXISTS `artistes_categories` (
  `id_c` int(11) NOT NULL AUTO_INCREMENT,
  `nom_c` varchar(255) NOT NULL,
  PRIMARY KEY (`id_c`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `artistes_categories`
--

INSERT INTO `artistes_categories` (`id_c`, `nom_c`) VALUES
(1, 'Acteurs'),
(2, 'Réalisateurs');

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

DROP TABLE IF EXISTS `commentaires`;
CREATE TABLE IF NOT EXISTS `commentaires` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Films_id_f` int(11) NOT NULL,
  `commentaire_c` longtext,
  `Utilisateurs_id_u` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_commentaires_Films1_idx` (`Films_id_f`),
  KEY `fk_Commentaires_Utilisateurs1_idx` (`Utilisateurs_id_u`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `films`
--

INSERT INTO `films` (`id_f`, `titre_f`, `poster_f`, `annee_f`, `resume_f`, `video_f`) VALUES
(18, 'Les Affranchis', 'les_affranchis.jpg', 2020, '', ''),
(19, 'Seven', 'seven.jpg', 1970, '', ''),
(20, 'Il etait une fois dans l\'Ouest', 'il_etait_une_fois_dans_l.ouest.jpg', 1970, '', ''),
(21, 'Le Bon, La Brute Et Le Truand', 'le_bon._la_brute_et_le_truand.jpg', 1970, '', ''),
(22, 'Il Etait Une Fois En Amerique', 'il_etait_une_fois_en_amerique.jpg', 1970, '', ''),
(23, 'Gladiator', 'gladiator.jpg', 1970, '', ''),
(24, 'Tu ne tueras point', 'tu_ne_tueras_point.jpg', 1970, '', ''),
(25, 'Lion', 'lion.jpg', 1970, '', ''),
(26, 'Fight Club', 'fight_club.jpg', 1970, '', ''),
(27, 'Vol au-dessus d\'un nid de coucou', 'vol_au-dessus_d.un_nid_de_coucou.jpg', 1970, '', ''),
(28, 'Pulp Fiction', 'pulp_fiction.jpg', 1970, '', ''),
(29, 'Django Unchained', 'django_unchained.jpg', 1970, '', ''),
(30, 'Les Evades', 'les_evades.jpg', 1970, '', ''),
(31, 'Bohemian Rhapsody', 'bohemian_rhapsody.jpg', 1970, '', ''),
(32, 'Coco', 'coco.jpg', 1970, '', ''),
(33, 'Le Parrain', 'le_parrain.jpg', 1970, '', ''),
(34, '12 hommes en colere', '12_hommes_en_colere.jpg', 1970, '', ''),
(35, 'La Liste de Schindler', 'la_liste_de_schindler.jpg', 1970, '', ''),
(36, 'La Ligne verte', 'la_ligne_verte.jpg', 1970, '', '');

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
(18, 15),
(33, 16),
(18, 18),
(18, 19);

-- --------------------------------------------------------

--
-- Structure de la table `metier`
--

DROP TABLE IF EXISTS `metier`;
CREATE TABLE IF NOT EXISTS `metier` (
  `artistes_id_a` int(11) NOT NULL,
  `categories_id_c` int(11) NOT NULL,
  PRIMARY KEY (`artistes_id_a`,`categories_id_c`),
  KEY `films_id_f` (`artistes_id_a`),
  KEY `categories_id_c` (`categories_id_c`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `metier`
--

INSERT INTO `metier` (`artistes_id_a`, `categories_id_c`) VALUES
(15, 1),
(15, 2),
(16, 1),
(17, 2),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1);

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
(18, 17);

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
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_u`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_u`, `type_user`, `username`, `password`, `email`, `create_time`) VALUES
(1, 'admin', 'admin', '$2y$10$IYI.S0ns159RpnhlGAD2FObR7m7TGzkutwL0Ytbz2nRTpDMSg8A4u', 'admin@admin.fr', '2020-02-07 07:27:18'),
(8, 'user', 'ludo', '$2y$10$juQ0YGpYvd20PsiGrSuBKOFr9Sr4XZHy.CadWMOUdpMZju5tsiEvO', 'rap2fr@hotmail.fr', '2020-02-10 15:02:01');

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
