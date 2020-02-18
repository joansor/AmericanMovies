-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  ven. 14 fév. 2020 à 13:06
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

--
-- Déchargement des données de la table `appartient`
--

INSERT INTO `appartient` (`Genre_id_g`, `Films_id_f`) VALUES
(6, 18),
(7, 18),
(6, 19),
(7, 20);

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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

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
(21, 'Fincher', 'David', 'david-fincher.jpg', 'Dès l\'âge de huit ans, David Fincher réalise de nombreux films dans le cadre familial. Passionné par le travail de George Lucas, il intègre dix ans plus tard la société d\'effets spéciaux de son modèle, Industrial, Light and Magic. Durant ses quatre années passées chez ILM, Fincher travaille ainsi sur les effets spéciaux du Retour du Jedi, d\'Indiana Jones et le Temple maudit ou de L\'Histoire sans fin. Fort de cet acquis, il se spécialise ensuite dans la réalisation de publicités et de clips musicaux, créant sa propre société de production, Propaganda Films. Son travail pour la marque Nike et pour des artistes comme Madonna, Aerosmith ou les Rolling Stones l\'impose vite comme un jeune surdoué de l\'image.\r\n\r\nA 29 ans, David Fincher s\'engage avec la Fox pour signer son premier long métrage, Alien 3, troisième volet de la saga fantastique emmenée par Sigourney Weaver. Le résultat, très sombre et virtuose, n\'empêche pas le studio de brider le jeune cinéaste qui gardera un souvenir amer de cette expérience. Déterminé à acquérir au plus vite une vraie liberté d\'action, Fincher fait équipe en 1994 avec une petite société de production indépendante de l\'époque, New Line Cinema, pour réaliser le thriller Seven. Centré sur les sept pêchés capitaux, il devient instantanément un classique du film de serial killer. Mettant en vedette Brad Pitt et Morgan Freeman, il pose aussi un regard désespéré et très sombre sur la société.\r\n\r\nAprès le choc Seven, David Fincher prend tout le monde à contre-pied en réalisant, deux ans plus tard, The Game. Avec ce thriller manipulateur porté par Michael Douglas, qui ne remporte pas le même succès critique et public, le cinéaste creuse un peu plus son oeuvre désenchantée sur le monde contemporain. Il s\'attaque ensuite en 1999 à la réalisation de Fight club. Adaptation d\'un roman de Chuck Palahniuk, le film, sulfureux et très vite culte, ne laisse personne indifférent. Pour l\'occasion, Fincher retrouve Brad Pitt et confie à Edward Norton le rôle principal.\r\n\r\nVisiblement désireux de ne pas se replonger dans une œuvre aussi polémique, David Fincher réalise en 2002 Panic room, thriller très classique en forme de huis-clos dans lequel évoluent notamment Jodie Foster et Forest Whitaker. Après une parenthèse de cinq ans marquée par la réalisation de clips et l\'avortement de son M : i : III (pour divergences artistiques avec Tom Cruise), David Fincher revient sur le devant de la scène en 2007 avec l\'ambitieux Zodiac, centré sur les agissements d\'un des plus célèbres tueurs de l\'Histoire des Etats-Unis. Il enchaîne immédiatement avec la réalisation du film fantastique L\'Etrange histoire de Benjamin Button, qui marque sa troisième collaboration avec Brad Pitt.\r\n\r\nAvec son film suivant, The Social Network, Fincher se centre sur la création du réseau social Facebook. En découle un portrait mordant de son créateur Mark Zuckerberg, interprété par Jesse Eisenberg. Si le cinéaste y emprunte en partie le classicisme de la mise en scène de Zodiac, il renoue néanmoins avec une réalisation plus stylisée (cf. Seven, Fight Club). En 2011, il crée à nouveau l\'évènement en mettant en scène une nouvelle adaptation du premier tome de la trilogie Millenium de Stieg Larsson : Millenium : Les hommes qui n’aimaient pas les femmes, avec Daniel Craig et Rooney Mara dans les rôles principaux. En 2014 sort en salles son dixième long métrage de fiction, le thriller Gone Girl dans lequel Ben Affleck cherche par tous les moyens à savoir ce qui est arrivé à sa femme qui a mystérieusement disparu.\r\n\r\nParallèlement à sa carrière exceptionnelle de réalisateur pour le cinéma, David Fincher tente l\'aventure du petit écran, en produisant la série politique House of Cards portée par un Kevin Spacey en très grande forme.', '1962-08-28'),
(22, 'Fonda', 'Henry', 'henry-fonda.jpg', '', '1970-01-01'),
(23, 'Bronson', 'Charles', 'charles-bronson.jpg', '', '1970-01-01'),
(24, 'Wolff', 'Frank', 'frank-wolff.jpg', '', '1970-01-01'),
(25, 'Pitt', 'Brad', 'brad-pitt.jpg', 'Brad Pitt passe sa jeunesse à Springfield, dans le Missouri, avant de poursuivre ses études à l\'université de Columbia où il décroche un diplôme de journalisme. Il s\'installe alors à Los Angeles et suit des cours d\'art dramatique dans l\'atelier de Roy London. Son aisance naturelle et son charisme lui ouvrent les portes de la télévision. On le voit dans des séries telles 21 Jump Street (1987) ou des téléfilms comme Trop jeune pour mourir (1990, avec Juliette Lewis, qui sera sa compagne pendant les trois années suivantes.\r\n\r\nParallèlement, il débute au cinéma dans Neige sur Beverly Hills (1987), puis dans la comédie romantique Happy together (1989). Une chance s\'offre alors à lui : William Baldwin ayant renoncé à Thelma et Louise, il hérite en 1991 du rôle bref, mais capital, de l\'irrésistible auto-stoppeur qui dévalise Geena Davis.\r\n\r\n\r\nDès lors, Brad Pitt alterne les entreprises originales (Johnny Suede, Cool world), avec des œuvres lyriques et romanesques. Sous la direction de Robert Redford, il incarne ainsi le frère du héros de Et au milieu coule une rivière (1992), amoureux de la nature et artiste de la pêche au lancer mais promis à une mort prématurée. Il interprète également le fils préféré d\'Anthony Hopkins dans Légendes d\'automne (1994) d\'Edward Zwick, un rôle pour lequel il obtient une nomination aux Golden Globes. Il est enfin la victime à demi consentante et le compagnon d\'errance de Tom Cruise dans Entretien avec un vampire (1994) de Neil Jordan.\r\n\r\nPar ailleurs, il tente de casser son image de jeune premier romantique en acceptant des emplois beaucoup moins séduisants, mais lui permettant d\'explorer d\'autres facettes de son talent : le tueur en série hirsute et crasseux de Kalifornia (1993), le drogué de True romance (1993) ou l\'écologiste fou de L\'Armée des 12 singes (1995). Sa quête constante d\'une plus grande complexité l\'amène à refuser certains rôles qu\'il estime trop schématiques, comme Le Saint (échu finalement à Val Kilmer), au profit de personnages lui permettant d\'exprimer des aspects plus sombres de sa personnalité. Seven (1995), de David Fincher, qu\'il tourne avec sa nouvelle compagne Gwyneth Paltrow, en fait un policier intrépide et déterminé, mais aisément manipulé par un diabolique assassin. Dans Sleepers (1996) de Barry Levinson, il est un jeune et brillant assistant du procureur qui orchestre sa vengeance contre ses anciens gardiens de prison. Puis, face à Harrison Ford, il incarne un terroriste de l\'I.R.A. dans Ennemis rapprochés (1997).\r\n\r\nPar la suite, Jean-Jacques Annaud l\'imagine en Heinrich Harrar, l\'alpiniste autrichien dont la vie fut transformée par ses Sept ans au Tibet (1997) et la rencontre du Dalaï-Lama. Ce rôle lui vaut d\'être interdit d\'entrée en Chine. L\'année suivante, Martin Brest lui propose d\'incarner la Mort elle-même, face à Anthony Hopkins, dans Rencontre avec Joe Black. Brad Pitt retrouve ensuite David Fincher pour un film très controversé, Fight club (1999) avec Edward Norton, pour lequel son salaire atteint les 20 millions de dollars. Après Snatch (2000) et Spy game (2001), il retrouve Julia Roberts, sa partenaire du Mexicain, dans Ocean\'s eleven (2001), film qui marque son entrée dans le cercle de Steven Soderbergh et George Clooney, et ses suites (Ocean\'s 12, Ocean\'s 13). Apparu le temps d\'un clin d\'oeil dans la première réalisation de ce dernier, Confessions d\'un homme dangereux, Brad Pitt revient en 2004 sur le devant de la scène cinématographique en tenant le rôle du guerrier Achille dans la fresque épique Troie.\r\n\r\n\r\nSur le tournage du film d\'action Mr and Mrs Smith (2005), il fait la rencontre d\'Angelina Jolie, avec qui il forme désormais l\'un des couples les plus médiatisés de la planète. Il n\'en oublie toutefois pas sa carrière d\'acteur et remporte en 2007 la coupe Volpi du meilleur acteur à Venise pour son interprétation de Jesse James dans L\'Assassinat de Jesse James d\'Andrew Dominik. Il se distingue ensuite en alternant les genres : de la comédie avec Burn After Reading des frères Coen au fantastique avec L\'Etrange histoire de Benjamin Button, où il retrouve pour la troisième fois David Fincher. Attirant décidemment les réalisateurs prestigieux, il se voit confier l\'un des rôles principaux du film de guerre de Quentin Tarantino, Inglourious Basterds. Sept ans après Sinbad pour les studios Dreamworks en 2003, il prête de nouveau sa voix pour un film d\'animation, dans le rôle de l\'ennemi juré de Megamind. Au printemps 2011, il est à l\'affiche du très attendu The Tree of Life (Palme d\'or au festival de Cannes) de Terrence Malick où il joue le père autoritaire de Sean Penn, puis enfile son survêtement d’entraineur de baseball pour Le Stratège.\r\n\r\nFin 2012, Brad Pitt joue le personnage principal du violent Cogan : Killing Them Softly, un gangster chevronné et pragmatique. Ses films suivants témoignent une fois de plus de son éclectisme puisqu\'il se frotte aux zombies dans World War Z, campe un soldat aguerri dans Fury et se livre à une romance avec Marion Cotillard dans Alliés.\r\n\r\nBrad Pitt est également fondateur et maintenant seul propriétaire de la société de production Plan B Entertainment, créée en 2002. Il a ainsi produit de nombreux films, dont Charlie et la chocolaterie de Tim Burton (2004), Les Infiltrés de Martin Scorsese (2006), Kick-Ass de Matthew Vaughn (2010) ou encore l\'oscarisé 12 Years A Slave de Steve McQueen.', '1963-12-18'),
(26, 'Freeman', 'Morgan', 'morgan-freeman.jpg', 'Morgan Freeman est diplômé du lycée de Greenwood, dans le Mississippi. A dix-huit ans, il s\'engage dans l\'Air Force et, une fois ses obligations militaires accomplies, s\'installe en Californie pour étudier la danse et l\'art dramatique au Los Angeles City College. C\'est à Broadway qu\'il fait ses débuts de comédien en 1967 dans la reprise de Hello Dolly !. La même année, il se fait remarquer, toujours au théâtre, pour son interprétation dans The Nigger lovers. A la télévision, c\'est en incarnant le personnage populaire et récurrent d\'Easy Rider dans la série The Electric Company qu\'il se fait connaître du public américain.\r\n\r\n\r\nMorgan Freeman s\'impose sur le tard au cinéma. En 1987, son rôle de Fast Black dans La Rue lui vaut de nombreuses récompenses (Prix du Meilleur second rôle masculin remis par les New York Film Critics, Los Angeles Film Critics et National Society of Film Critics), ainsi qu\'une nomination aux Golden Globes. Il fait mieux avec Miss Daisy et son chauffeur, qui lui permet de remporter en 1990 un Golden Globe, un Ours d\'argent et une nomination aux Oscars. Dès lors, il enchaîne les succès commerciaux comme Glory de Edward Zwick, Robin des Bois, prince des voleurs de Kevin Reynolds ou encore Impitoyable de Clint Eastwood.\r\n\r\n\r\nOn confie souvent à Morgan Freeman des personnages qui s\'illustrent par leur sagesse et leur détermination : en 1991, il est le juge Leonard White dans Le Bûcher des vanités, puis un prisonnier modèle dans Les Evadés, un détective lucide et expérimenté face à l\'impétueux Brad Pitt dans Seven, le Président des Etats-Unis dans Deep impact, le supérieur hiérarchique de Jack Affleck Ryan dans La Somme de toutes les peurs ou encore l\'artiste aveugle prenant sous son aile Jet Li dans Danny the dog. En campant le profiler Alex Cross dans Le Collectionneur (1997) et Le Masque de l\'araignée (2001), le comédien impose encore un peu plus ce personnage calme et réfléchi.\r\n\r\n\r\nPar ses choix d\'acteur, Morgan Freeman fait par ailleurs preuve d\'un engagement politique certain. Prenant fait et cause pour la population noire-américaine, il incarne Malcom X à la télévision dans Death of the prophet (1981), un abolitionniste dans Amistad (1997), et dénonce les horreurs de l\'apartheid dans Bopha ! (1993), son premier film en tant que réalisateur. Parallèlement à ses rôles de vieux sage, il tente de diversifier son jeu : truand sauvage dans Nurse Betty (2000), il est un colonel aux ambitions troubles dans Dreamcatcher, l\'attrape-rêves (2003), voire carrément Dieu dans la comédie Bruce tout puissant (id.). En 2005, à l\'âge de 68 ans, il obtient enfin la reconnaissance de la profession en remportant l\'Oscar du Meilleur second rôle masculin pour sa prestation d\'ancien boxeur borgne dans Million dollar baby, réalisé par son fidèle ami Clint Eastwood.\r\n\r\n\r\nTrès éclectique, le populaire Morgan Freeman campe Lucius Fox dans les lucratifs et cultes Batman Begins (2004), The Dark Knight, Le Chevalier Noir (2008) et The Dark Knight Rises (2012). Présent au générique du survitaminé Wanted : choisis ton destin, il fait également profiter de son expérience les réalisateurs novices, s\'illustrant notamment en 2007 dans le Gone Baby Gone de Ben Affleck. En 2009, il incarne Nelson Mandela devant la caméra de Clint Eastwood (Invictus), avant d\'interpréter un agent de la CIA à la retraite, reprenant du service, dans la comédie d\'action Red. Dans Conan (remake du film culte qui propulsa Arnold Schwarzenegger au rang de superstar) et Born to Be Wild 3D, Morgan Freeman joue le \"rôle\" de celui qui raconte, comme il l\'a déjà fait dans Les Evadés une quinzaine d\'années auparavant.\r\n\r\n\r\nPrivilégiant les blockbusters et les franchises qui souvent rapportent, Morgan Freeman enchaîne des rôles dans Oblivion (2013), La Chute de la Maison Blanche (id.), Insaisissables (id.), Lucy (id.), Ted 2 (2015), La Chute de Londres (2016), Insaisissables 2 (2016) et Ben-Hur (id.). Parallèlement, on peut le voir en tête d\'affiche de longs métrages aux sujets plus ancrés dans le réel comme Un été magique (2012), Last Vegas (2013) et Braquage à l\'ancienne (2016).', '1937-07-01'),
(27, 'Paltrow', 'Gwyneth', 'gwyneth-paltrow.jpg', 'Fille du producteur de télévision Bruce Paltrow et de l\'actrice Blythe Danner.\r\n\r\nAyant grandi dans un milieu artistique, Gwyneth Paltrow trouve rapidement sa voie. En 1990, elle joue au théâtre aux côtés de sa mère dans la pièce Picnic. Des critiques encourageantes l\'incitent à abandonner ses études d\'histoire de l\'art pour devenir actrice à plein temps. C\'est en donnant la réplique à John Travolta et Heather Graham dans Shout (1991) de Jeffrey Hornaday qu\'elle effectue ses premiers pas au cinéma. La même année, elle assiste avec son père à une projection du Silence des agneaux en compagnie de Steven Spielberg et de son épouse Kate Capshaw : le cinéaste a alors l\'idée de lui proposer le rôle de Wendy dans Hook, une adaptation du conte Peter Pan que le cinéaste tourne en 1991.\r\n\r\nGwyneth Paltrow enchaîne dès lors les rôles secondaires (Malice, Mrs Parker et le cercle vicieux ou encore Jefferson à Paris de James Ivory) jusqu\'à ce que sorte en salles le thriller Seven de David Fincher en 1995. Le succès international du film, associé à son nouveau statut de petite amie de Brad Pitt, lui apporte une notoriété soudaine. Le tout Hollywood succombant à sa beauté diaphane, elle se voit dès lors cantonnée à des personnages féminins à la fois romantiques et fragiles, comme en témoignent ses prestations dans Emma l\'entremetteuse (1996), De grandes espérances (1998), Du venin dans les veines (id.) et Pile ou face (id.).\r\n\r\nEn 1999, elle obtient l\'Oscar de la Meilleure actrice pour son rôle d\'apprentie comédienne dans Shakespeare in love. Convoitée par les duos Michael Douglas / Viggo Mortensen (Meurtre parfait) et Jude Law / Matt Damon (Le Talentueux M. Ripley), elle tombe sous le charme à la ville comme à l\'écran de Ben Affleck, rencontré sur le tournage du drame sentimental Un amour infini (2001). Egalement à l\'aise dans la comédie et faisant preuve d\'autodérision, elle interprète une chanteuse de karaoké sous la direction de son père dans Duos d\'un jour (2001), intègre La Famille Tenenbaum (2002) de Wes Anderson, devient le pur fantasme de Jack Black dans L\'Amour extra large (id.) des frères Farrelly et rêve de \"s\'envoyer en l\'air\" dans Hôtesse à tout prix (2003).\r\n\r\nS\'essayant pour la première fois au fantastique en 2004, elle prête main forte au Capitaine Sky dans le film rétro-futuriste de Kerry Conran. Si les films qu\'elle enchaine ensuite sont relativement vite oubliés (Proof, Scandaleusement célèbre ou The Good Night), elle revient en force en 2008 avec le blockbuster Iron Man dans lequel elle fait succomber Robert Downey Jr. ainsi que dans Two Lovers de James Gray qu\'elle illumine aux cotés de Joaquin Phoenix. Victime du virus inoculé par Steven Soderbergh dans Contagion, la belle Gwyneth devient indissociable de son rôle de Pepper Potts après avoir tourné Iron Man 2 (2010), Avengers (2012) et Iron Man 3 (2013).', '1972-09-27');

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
  `resume_f` longtext NOT NULL,
  `video_f` varchar(255) NOT NULL,
  PRIMARY KEY (`id_f`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `films`
--

INSERT INTO `films` (`id_f`, `titre_f`, `poster_f`, `annee_f`, `resume_f`, `video_f`) VALUES
(18, 'Les Affranchis', 'les_affranchis.jpg', 1990, 'Depuis sa plus tendre enfance, Henry Hill, né d\'un père irlandais et d\'une mère sicilienne, veut devenir gangster et appartenir à la Mafia. Adolescent dans les années cinquante, il commence par travailler pour le compte de Paul Cicero et voue une grande admiration pour Jimmy Conway, qui a fait du détournement de camions sa grande spécialité. Lucide et ambitieux, il contribue au casse des entrepôts de l\'aéroport d\'Idlewild et épouse Karen, une jeune Juive qu\'il trompe régulièrement. Mais son implication dans le trafic de drogue le fera plonger...', 'https://www.youtube.com/embed/0v0NUEmeqbI'),
(19, 'Seven', 'seven.jpg', 1995, 'Pour conclure sa carrière, l\'inspecteur Somerset, vieux flic blasé, tombe à sept jours de la retraite sur un criminel peu ordinaire. John Doe, c\'est ainsi que se fait appeler l\'assassin, a decidé de nettoyer la societé des maux qui la rongent en commettant sept meurtres basés sur les sept pechés capitaux: la gourmandise, l\'avarice, la paresse, l\'orgueil, la luxure, l\'envie et la colère.', ''),
(20, 'Il Etait Une Fois Dans L\'ouest', 'il_etait_une_fois_dans_l.ouest.jpg', 1970, '', ''),
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `genre`
--

INSERT INTO `genre` (`id_g`, `genre_du_film`) VALUES
(3, 'Action'),
(4, 'Adventure'),
(5, 'Comedy'),
(6, 'Crime'),
(7, 'Drama'),
(8, 'Historical'),
(9, 'Musicals'),
(10, 'Sci-Fi'),
(11, 'War');

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
(18, 19),
(19, 25),
(19, 26),
(19, 27);

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
(16, 1),
(17, 2),
(18, 1),
(19, 1),
(20, 1),
(21, 2),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1);

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
(18, 17),
(19, 21);

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
