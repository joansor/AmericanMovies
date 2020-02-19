-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3308
-- Généré le :  mer. 19 fév. 2020 à 12:37
-- Version du serveur :  8.0.18
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
(6, 18),
(7, 18),
(7, 37);

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
  `biographie_a` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `date_de_naissance_a` date NOT NULL,
  PRIMARY KEY (`id_a`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `artistes`
--

INSERT INTO `artistes` (`id_a`, `nom_a`, `prenom_a`, `photo_a`, `biographie_a`, `date_de_naissance_a`) VALUES
(15, 'De Niro', 'Robert', 'robert-de_niro.jpg', 'Robert De Niro grandit dans le quartier de Little Italy à New York. Il quitte l\'école à seize ans pour suivre les cours d\'art dramatique du Dramatic Workshop du Luther James Studio et de l\'école de théâtre de Stella Adler. Il suit également l\'enseignement de Lee Strasberg à l\'Actors\' Studio, où il fait la rencontre d\'Harvey Keitel, qu\'il retrouvera à plusieurs reprises au long de sa carrière. Il monte pour la première fois sur scène dans L\'Ours d\'Anton Tchekhov, et fait des débuts discrets au cinéma, en 1965, comme figurant dans le film de Marcel Carné, Trois chambres à Manhattan.\r\n\r\nUn an plus tard, Robert De Niro fait la connaissance de Brian De Palma avec qui il tourne trois comédies semi-improvisées : The Wedding Party (1966), Greetings (1968) et Hi, Mom ! (1969). Mais c\'est Martin Scorsese qui révèle ses talents de comédien au grand public grâce au polar Mean Streets (1973), dans lequel il interprète le fougueux Johnny Boy. L\'année suivante, son personnage de jeune Vito Corleone dans Le Parrain, 2ème partie de Francis Ford Coppola lui vaut l\'Oscar du Meilleur second rôle, et après sa troublante performance dans Taxi Driver (1976), Robert De Niro s\'affirme comme un acteur de composition, épousant totalement le profil de ses rôles. Perfectionniste, il n\'hésite pas à apprendre le saxophone pour la comédie musicale New York, New York (1977), à vivre aux côtés de mineurs-sidérurgistes pour Voyage au bout de l\'enfer (1978), à prendre trente kilos pour jouer un boxeur sur le déclin dans Raging Bull (1980), performance qui lui vaut l\'Oscar du Meilleur acteur, ou encore à apprendre la messe en latin pour les besoins de Sanglantes confessions (id.).\r\n\r\nParallèlement à la fructueuse collaboration qu\'il poursuit avec Martin Scorsese (La Valse des pantins, Les Affranchis, Les Nerfs à vif, Casino), Robert De Niro joue la carte de la diversité avec plus ou moins de succès. On le retrouve ainsi au générique de films de gangsters, dont il s\'est fait une spécialité (Il était une fois en Amérique, Les Incorruptibles), de films d\'anticipation (Brazil, 1985), de fresques historiques (Mission, Palme d\'or à Cannes en 1986), de thrillers surnaturels comme Angel Heart (1987) ou encore de comédies comme Midnight Run (1988). Également fidèle au cinéaste Irwin Winkler (La Liste noire, La Loi de la nuit), l\'acteur s\'essaie en 1994 à la réalisation, avec le drame Il était une fois le Bronx. Douze ans plus tard, il réitérera l\'expérience avec Raisons d\'Etat, centré sur la naissance de la CIA. Entre-temps, il se mesure à quelques cadors comme Kenneth Branagh, pour qui il interprète la créature de Frankenstein en 1995, Al Pacino (Heat, et douze ans plus tard La Loi et l\'ordre), Sylvester Stallone (Copland, 1997), Dustin Hoffman (Des hommes d\'influence, 1998) ou encore Marlon Brando (The Score, 2001).\r\n\r\nAprès avoir tourné en 1998 sous la houlette de deux \"pointures\" (Quentin Tarantino pour Jackie Brown et John Frankenheimer pour le film d\'action Ronin), Robert De Niro \"cachetonne\" dans des comédies grand public comme Mafia Blues (1999) et sa suite (2003), Personne n\'est parfait(e) (2000), Showtime (2001) ou encore la trilogie Mon beau-père et moi. On le voit également s\'égarer dans des thrillers de peu d\'intérêt (15 minutes, Père et flic, Godsend, Trouble jeu), mais il parvient toutefois à surprendre en s\'illustrant dans des registres auxquels il n\'était guère habitué jusqu\'à présent, comme l\'animation (Gang de requins, Arthur et les Minimoys) et l\'heroic fantasy (Stardust, le mystère de l\'étoile), tout en s\'éloignant d\'un jeu parfois caricatural et trop \"grimaçant\" (Panique à Hollywood, Everybody\'s Fine).\r\n\r\nEn 2010, l\'acteur est à l\'affiche de la comédie d\'action Machete de Robert Rodriguez et Ethan Maniquis, où il interprète un sénateur véreux. D\'un rôle de sénateur au cinéma, il devient dans la vie le 64e président du jury du Festival de Cannes en 2011, où il consacre The Tree of Life de Terrence Malick. La même année, il s\'illustre dans pas moins de cinq films, aux genres très différents : les thrillers Stone et Limitless, le film d\'action Killer Elite et les comédies romantiques Happy New Year et L\'Amour a ses raisons (tourné en italien, langue qu\'il n\'avait plus utilisée à l\'écran depuis Le Parrain, 2ème partie, 37 ans plus tôt). L\'année suivante, c\'est dans le rôle du père marginal de Paul Dano, dans le drame Monsieur Flynn, qu\'on le retrouve. Cette même année, il est également à l\'affiche de Red Lights et Unités d\'Elite, deux films qui ne parviennent pas à redorer le blason de la grande star qu\'il était.\r\n\r\nL\'Italo-Américain refait toutefois rapidement parler de lui en 2013, à l\'occasion de la comédie dramatique Happiness Therapy. Dans le rôle du paternel ahurissant de Bradley Cooper, il livre une prestation très remarquée. Il termine cette année avec deux comédies : Malavita de Luc Besson et Last Vegas où il partage l\'affiche avec Morgan Freeman, Michael Douglas et Kevin Kline. En janvier 2014, les Français voient Robert De Niro rehausser ses gants de boxe en compagnie de Sylvester Stallone, pour les besoins de Match Retour. \r\n\r\nLe comédien alterne ensuite le pire et le meilleur, caricaturant notamment ses rôles de mafieux dans L\'Instinct de tuer ou Bus 657. Il délivre également une prestation gênante dans la comédie potache Dirty Papy aux côtés de Zac Efron. Mais De Niro démontre finalement qu\'il reste un des plus grands acteurs, brillant par exemple dans The Comedian, dans lequel il campe un personnage proche de Rupert Pupkin de La Valse des Pantins. L\'artiste interprète ensuite l\'entraîneur de boxe Ray Arcel dans Hands of Stone, biopic centré sur le boxeur panaméen Roberto Duràn.\r\n\r\nL\'acteur reste dans le biopic en prêtant ses traits au sulfureux financier Bernard Madoff, l\'escroc de Wall Street, dans The Wizard of Lies. Ce dernier a été condamné en 2009 à 150 ans de prison pour avoir détourné près de 65 milliards de dollars. En 2019, De Niro se confronte au Joker en personne ! Il incarne un animateur de télévision qui va se retrouver opposé au clown prince du crime joué par Joaquin Phoenix. La même année, De Niro retrouve Martin Scorsese pour la 9ème fois dans The Irishman. Aux côtés de Joe Pesci et Al Pacino, le comédien campe le gangster Frank Sheeran, soupçonné d\'avoir fait disparaitre le dirigeant syndicaliste Jimmy Hoffa en 1975.', '1943-08-17'),
(16, 'Pacino', 'Al', 'al-pacino.jpg', 'Fils de Salvator Pacino tailleur de pierre et Rose Gerard sans profession, tous les deux originaires de la Sicile. Elevé par ses grands-parents maternels James et Kate Gerard. Alfred James Pacino collectionne depuis ses débuts les nominations aux Oscars, mais c\'est seulement en 1992 qu\'il obtient sa première statuette pour Le Temps d\'un week-end (adaptation du roman Parfum de femme de Giovanni Arpino). Vingt ans plus tôt, il connaissait sa première nomination pour son interprétation de Michael Corleone dans Le Parrain de Francis Ford Coppola.\r\n\r\nL\'acteur passe son enfance dans le Bronx. Envahi par la passion de la comédie, il entre à l\'âge de quatorze ans à la High School of Performing Arts de Manhattan, mais, sans diplôme, ni bagages culturels, il se retrouve en difficulté face aux méthodes utilisées. A seize ans, il décide de tout arrêter et passe deux ans à multiplier les petits boulots, pour revenir ensuite à sa vocation première. Après avoir échoué à l\'entrée de l\'Actors Studio, il poursuit sa formation d\'acteur aux cours d\'Herbert Berghof et a pour professeur Charles Laughton, qui deviendra un de ses pères spirituels.\r\n\r\nIl obtient son premier rôle principal avec la pièce de William Saroyan Hello, Out There et reçoit un Obie (équivalent des Oscars pour le théâtre) pour son interprétation dans The Indian Wants the Bronx. Les rôles au théâtre se succèdent, provocant louanges et critiques, et couronnés de plusieurs prix. En 1966, il est enfin admis à l\'Actors Studio, il y rencontre Dustin Hoffman et Robert De Niro, la nouvelle génération d\'acteurs formée selon \"la méthode \"qui occuperont le devant de la scène durant les années 70 jusqu\'au milieu des années 80. Il y fait également la connaissance de Lee Strasberg qui deviendra son nouveau mentor.\r\n\r\nQuand on le découvre en 1969 dans son premier film Me, Natalie, Al Pacino a déjà une jolie carrière théâtrale derrière lui, mais c\'est Panique à Needle Park qui va réellement le révéler et trois ans plus tard c\'est la consécration : Le Parrain lui ouvre les portes de la gloire, il a trente-deux ans. S\'enchaînent ensuite plusieurs films et plusieurs citations aux Oscars, dont Le Parrain, 2ème partie, c\'est sa première rencontre à l\'écran avec De Niro, qu\'il retrouvera dans Heat dix ans plus tard. Suite à ses interprétations cinématographiques de 1974, il est déclaré meilleur acteur de l\'année par La British Academy.\r\n\r\nIl connaît par la suite une longue traversée du désert, refuse plusieurs rôles phares : Apocalypse Now, Les Moissons du ciel et Kramer contre Kramer. Il fait de mauvais choix et doit faire face en 1985 à un échec cuisant avec Revolution, son premier film en costume. Le cinéma est alors volontairement délaissé au profit du théâtre, sa passion et première vocation. En 1996 il réalise et produit son premier long métrage (il a co-réalisé The Local Stigmatic en 1990 un film de 56 minutes) : Looking for Richard, un essai sur la mise en scène de la pièce de Shakespeare Richard III (pièce qu\'il avait entre autre déjà joué en 1973 à Boston). Mi documentaire, mi adaptation c\'est sans doute, le film qui permet le mieux de comprendre l\'acteur et d\'apprécier son interprétation subtile et riche en nuances qui a fait sa renommée.\r\n\r\nSon retour cinématographique a réellement été marqué avec Mélodie pour un meurtre en 1989. Quatre ans plus tard, il retrouve Brian De Palma avec qui il avait déjà signé Scarface pour L\' Impasse. Le comédien saura jouer avec son image et varier les rôles, de Dick Tracy, Frankie & Johnny jusqu\'au troisième opus du Parrain qui le fait entrer dans la légende. En 2002, il tourne avec deux jeunes cinéastes remarqués. Il est le réalisateur en crise de Simone, une star est... créée d\'Andrew Niccol et le policier désabusé d\'Insomnia de Christopher Nolan.\r\n\r\nReconnu aujourd\'hui pour son fabuleux don de faire oublier l\'acteur caché derrière un personnage, Al Pacino, tend à présent à multiplier les casquettes comme dans Chinese coffee (adapté de la pièce de théâtre) où il est réalisateur et interprète. En acteur reconnu, celui-ci n\'hésite pas à donner la réplique à de jeunes talents comme Colin Farrell dans La Recrue (2003), Ben Affleck et Jennifer Lopez dans Amours troubles (2003) ou encore Matthew McConaugheydans Two for the Money (2006).\r\n\r\nEn 2007 il devient expert universitaire en psychiatrie criminelle pour les besoins de 88 minutes de Jon Avnet avant de rejoindre l\'équipe de braqueurs cools sur le tournage d\' Ocean\'s 13 de Steven Soderbergh. La même année, Andrew Niccol, avec qui Al Pacino avait déjà collaboré en 2002 pour Simone, lui offre le rôle de Salvador Dali dans le drame Dali and I : The Surreal Story.', '1940-04-25'),
(17, 'Scorsese', 'Martin', 'martin-scorsese.jpg', 'Souffrant d\'asthme, Martin Scorsese fréquente assidument les salles de cinéma de Little Italy dès son adolescence. Il souhaite devenir peintre puis prêtre avant d\'entamer ses études à la New York University où il tourne ses premiers courts métrages. En 1965, il commence à travailler sur Who\'s that knocking at my door ?, son premier long métrage avec Harvey Keitel dans le rôle principal, qu\'il ne terminera que quatre années plus tard. Entre temps, il se fait renvoyer du tournage des Tueurs de la lune de miel au bout d\'une semaine et effectue quelques travaux de montage. Le cinéaste est alors approché par Roger Corman qui lui propose de financer son second long métrage, Bertha Boxcar (1972). Mécontent des contraintes imposées par le producteur, il cherche à revenir à un sujet plus personnel et finit par trouver les fonds nécessaires au tournage de Mean streets (1973). Le film marque sa première collaboration avec son acteur fétiche Robert De Niro et impose Scorsese comme l\'un des réalisateurs les plus prometteurs de sa génération.\r\n\r\nLe cinéaste accepte ensuite de faire ses preuves sur une œuvre plus commerciale. Alice n\'habite plus ici (1974) démontre qu\'il peut s\'adapter à tous les types de sujets. Ellen Burstyn remporte l\'Oscar de la Meilleure actrice pour sa performance dans le film. En 1976, le cinéaste fait sensation à Cannes en remportant la Palme d\'or pour Taxi Driver, l\'histoire d\'un vétéran du Vietnam solitaire obsédé par la saleté des rues new yorkaises. Scorsese entame alors une période difficile de sa vie. Le tournage de New York, New York (1977), totalement désordonné, laisse le cinéaste déprimé. Abusant des drogues, il est au bord du suicide avant de se reprendre. De Niro lui propose de tourner une biographie du boxeur Jack La Motta : Scorsese fait de Raging Bull (1980) sa rédemption. En 1990, les critiques américains l\'élisent comme le meilleur film de la décennie.\r\n\r\nLes années 1980 sont commercialement difficiles pour Scorsese. Il se tourne vers la comédie avec La Valse des pantins (1983) et After Hours (1985), Prix de la mise en scène à Cannes, qui ne séduisent pas le grand public. Il doit donc à nouveau tourner un film ouvertement plus commercial, La Couleur de l\'argent (1987), pour financer un projet qui lui tient à coeur depuis plus de dix ans, La Derniere Tentation du Christ, qui choque l\'Amérique puritaine. L\'investissement d\'Universal sur ce dernier projet amène Scorsese à tourner pour le studio Les Nerfs à vif (1991), remake du film homonyme de Jack Lee Thompson.\r\n\r\nEn 1990 puis 1995, Scorsese offre deux brillantes explorations du monde de la Mafia avec Les Affranchis et Casino. Il y démontre avec style les impasses du rêve américain. Les films bénéficient des performances inoubliables de Joe Pesci et Robert De Niro. Cadre d\'un drame amoureux dans Le Temps de l\'innocence (1993) ou de la crise spirituelle d\'un ambulancier dans A tombeau ouvert (1999), la ville de New York tient une place de choix dans l\'oeuvre du cinéaste comme en témoigne, en 2002, Gangs of New York, fresque portée par Leonardo DiCaprio et Daniel Day-Lewis, qui retrace l\'affrontement entre Américains et nouveaux immigrants en pleine guerre civile. Un Leonardo DiCaprio qu\'il retrouvera à plusieurs reprises, pour Aviator (2005), biopic consacré au milliardaire excentrique Howard Hughes et hommage du cinéphile Scorsese à l\'âge d\'or d\'Hollywood, et pour le polar Les Infiltrés, remake du film hongkongais Infernal affairs, également emmené par Matt Damon et Jack Nicholson, et qui lui vaudra les Oscars du Meilleur Film et du Meilleur Réalisateur en 2007.\r\n\r\nMartin Scorsese entame cette nouvelle décennie en tant que producteur et réalisateur du pilote de la série Boardwalk Empire emmenée par un Steve Buscemi en très grande forme ; véritable immersion dans le monde mafieux d\'Atlantic City, cité du crime des années 20. Boardwalk Empire récolte au passage de nombreux prix à l\'issue de sa première saison dont celui de \"meilleure série dramatique\" aux Golden Globes 2011. Succès oblige, elle est renouvelée pour deux saisons supplémentaires la même année.\r\n\r\nCôté cinéma, le cinéaste retrouve encore une fois Leonardo DiCaprio en 2010, et l\'envoie enquêter au coeur d\'un asile psychiatrique dans Shutter Island, adapté du roman homonyme de Dennis Lehane. Nouvelle année et nouvelle adaptation en 2011 pour le cinéaste qui s\'attaque cette fois-ci, dans un tout autre registre, aux aventures parisiennes et mystérieuses d\'Hugo Cabret dans lequel il dirige le jeune Asa Butterfield, ainsi que Jude Law.\r\n\r\nMartin Scorsese a toujours voué un culte sans précédent au \"rock\'n\'roll\", au \"rock classic\" ainsi qu\'au \"blues\". Ces genres musicaux font véritablement partie intégrante des œuvres de sa filmographie. D\'Eric Clapton aux Rolling Stones, ces morceaux choisis ont contribué à donner une puissance sonore indéniable à ses films. Ainsi, en véritable passionné, il s\'est prêté à la réalisation et à la production de nombreux documentaires et/ou concerts concernant ses idoles. Parmi eux, on peut citer : le clip Bad pour Michael Jackson en 1987, les documentaires Eric Clapton: Nothing But the Blues en 1995 et No Direction Home: Bob Dylan en 2005, le concert Rolling Stones : Shine a Light en 2008 ou plus récemment le documentaire consacré à l\'ancien Beatles, George Harrison: Living in the Material World en 2011.\r\n\r\nEn 2013 sort Le Loup de Wall Street, adaptation du roman éponyme de l’ancien courtier Jordan Belfort, rédigé à sa sortie de prison. Le cinéaste de Little Italy met une cinquième fois en scène Leonardo DiCaprio pour l’occasion. Quatre ans plus tard, le natif de New York réalise enfin son rêve, adapter à l\'écran le roman du japonais Shusaku Endo, Silence. Près de 30 ans après avoir découvert cette histoire, le mythique cinéaste parvient enfin au but, nous emmener au 17ème siècle pour nous raconter le parcours de deux prêtres jésuites en mission dans un Japon hostile persécutant les chrétiens.\r\n\r\nAuteurs : Thomas Colpaert / Kévin Poujoulat', '1942-11-17'),
(18, 'Liotta', 'Ray', 'ray-liotta.jpg', 'Raymond Allen Liotta, dit Ray, nait dans le New Jersey et est adopté six mois plus tard par un couple de fervents démocrates. Après le lycée, il part étudier la comédie à l\'université de Miami, où il devient ami avec l\'acteur Steven Bauer. Quelques temps plus tard, il fait ses débuts à la télévision dans le soap-opera Another World dans lequel il joue de 1978 à 1981. Il apparait ensuite dans quelques courtes séries et téléfilms avant de faire ses premiers pas au cinéma avec The Lonely lady (1983). C\'est en 1986, sur la recommandation de Melanie Griffith (alors mariée à Steven Bauer), qu\'il joue aux côtés de l\'actrice son premier rôle important, celui d\'un mari psychopathe, dans Dangereuse sous tous rapports qui lui vaut une nomination au Golden Globe du meilleur second rôle. Cependant, il accède véritablement à la consécration en 1990 avec Les Affranchis de Martin Scorsese, film de mafia culte dans lequel il incarne le gangster Henry Hill, apparaissant ainsi comme le digne successeur de Robert De Niro qui joue son mentor dans le film.\r\n\r\nPar la suite, Ray Liotta se spécialise dans les polars. Il interprète, par exemple, un flic peu recommandable dans Obsession fatale (1992) de Jonathan Kaplan, un médecin légiste à la recherche du meurtrier de sa femme dans Memoires suspectes (1996) de John Dahl, un policier toxicomane dans Copland (1997) de James Mangold (qui lui permet de retrouver Robert De Niro), ou encore un agent du FBI ambigu dans Hannibal (2001) de Ridley Scott. En 2002, il produit, via sa société Tiara Blu Films, Narc, un polar nerveux signé Joe Carnahan dans lequel il interprète un officier des stups aux méthodes expéditives. L\'année suivante, il entre dans la peau d\'un flic trouble dans le thriller labyrinthique Identity dans lequel il est à nouveau dirigé par James Mangold, avant d\'enchaîner par une série de films aux succès limités et qui ne sortiront pas du marché américain.\r\n\r\nHormis son interprétation du héros du film de science-fiction Absolom 2022 en 1994 (échec cuisant au box office), le comédien est majoritairement cantonné à des rôles de seconds couteaux marquants et, de ce fait, enchaine les projets en alternant les genres. A l\'aise dans les polars, il l\'est tout autant dans les comédies légères : père de famille veuf dans la comédie familiale Corrina, Corrina (1994), homme riche trompé par Sigourney Weaver dans Beautés empoisonnées en 2001, biker qui doit faire face à John Travolta, Martin Lawrence, Tim Allen et William H. Macy dans Bande de sauvages (2007), ou encore gangster dans le déjanté Crazy Night (2010).\r\n\r\nEn 2004, Ray Liotta apparait dans un épisode de la onzième saison d\'Urgences, Time of Death, qui suit en temps réel la dernière heure de vie de son personnage. Pour ce rôle, il remporte l’Emmy Award du meilleur acteur invité dans une série télévisée dramatique. Après cette distinction, il est de retour en tête d’affiche au cinéma grâce à Guy Ritchie qui lui confie le rôle d\'un truand de haute volée aux prises avec son rival de toujours interprété par Jason Statham pour les besoins de Revolver (2005), et l’année suivante il retrouve Joe Carnahan pour l\'explosif Mi$e à prix. Toujours en 2006, il fait son retour à la télévision en tenant le rôle principal de la série Dossier Smith qui ne dure que le temps de 7 épisodes.\r\n\r\nEn 2010, Ray Liotta rejoint Harrison Ford dans Droit de passage, un drame policier centré sur l\'immigration illégale qui est, comme beaucoup de ses films, un échec cuisant au box office. Comme à son habitude, il continue d\'enchainer les films mineurs destinés au marché du dvd (The Line, Itinéraire manqué), ce qui ne l\'empêche pas d\'être à l\'affiche de productions de qualité. Ainsi, en 2012, il fait partie du casting du film en compétition officielle au Festival de Cannes qui met en vedette Brad Pitt (Killing Them Softly), et donne, la même année, la réplique à Bradley Cooper pour les besoins du thriller The Place Beyond The Pines.\r\n\r\nAprès un passage devant la caméra de Nick Cassavetes dans Yellow, Ray enchaîne les thrillers au succès modéré – Le diable en personne, Pawn, The Iceman … Puis il varie les genres. On le retrouve dans la rom-com Blonde sur Ordonnance, dans la comédie Opération Muppets ou encore dans le drame musical inspiré de la vie du King, The Identical. Robert Rodriguez et Frank Miller lui offrent une apparition dans Sin City 2. En 2014, il joue dans Secret d’Etat, film acclamé par la critique et emmené par l’excellent Jeremy Renner (Démineurs, Mission Impossible : protocole fantôme). Sa prestation dans la mini-série Texas Rising lui vaut une nomination aux Screen Actors Guild Awards. Après un détour plein d’auto-dérision par Modern Family, il est choisi pour donner la réplique à Jennifer Lopez dans Shades of Blue, nouvelle série de NBC lancée en janvier 2016. C’est son premier rôle majeur depuis quelques années. Son personnage de flic corrompu, le lieutenant Matt Wozniak, est la cible d’une enquête menée par le FBI.', '1954-12-18'),
(19, 'Pesci', 'Joe', 'joe-pesci.jpg', 'Joe Pesci débute sa carrière d\'acteur dès son plus jeune âge, en apparaissant au milieu des années 50 dans la série télévisée \"Star Time Kids\". Il abandonne rapidement les études pour tenter une carrière de chanteur de bars, et devient même guitariste pour un groupe, les Joe Dee & the Starliters. En 1961, il fait ses premiers pas sur grand écran, en tenant un rôle non crédité dans Hey, Let\'s Twist. Expérience sans lendemain pour lui, puisqu\'il lui faut attendre quinze années avant de retrouver les caméras pour The Death collector.\r\n\r\nLa chance lui sourit néamoins puisque sa prestation a été remarquée par Robert De Niro et Martin Scorsese, qui le contacte pour tenir le rôle du frère de Jake La Motta dans Raging Bull: une performance saluée par une citation à l\'Oscar du Meilleur second rôle. Après un détour en 1984 chez Sergio Leone pour l\'immense fresque d\'Il était une fois en Amérique, il offre un contrepoint comique au duo Mel Gibson / Danny Glover avec le personnage de Leo Getz, dans le second volet de la franchise L\'Arme fatale. Un personnage qu\'il retrouvera à deux reprises en 1992 et 1998, toujours sous la houlette de Richard Donner. En 1990, Martin Scorsese fait de nouveau appel à lui pour Les Affranchis, une saga du crime dans lequel il incarne le gangster psychopathe Tommy DeVito, qui profite de la moindre étincelle pour déclencher un carnage. Unanimement saluée par le public et la critique, sa brillante performance est récompensée par l\'Oscar du Meilleur second rôle. Un univers auquel il est décidemment habitué, puisque le réalisateur lui offre en 1995 le rôle du névrotique Nicky Santoro dans le flamboyant Casino, où il joue le bras droit de Robert De Niro et succombe à la vénale Sharon Stone.\r\n\r\nSans doute las des rôles de mafieux auquel les producteurs l\'ont souvent cantonné, Joe Pesci s\'est surtout imposé dans les années 1990 dans le registre de la comédie, avec plus ou moins de succès. Gangster raté et pathétique dans Maman, j\'ai raté l\'avion et sa suite, il fait un bref détour par le thriller politique avec JFK, avant d\'incarner un avocat désopilant dans la comédie Mon Cousin Vinny. Mais en dépit de quelques rôles mineurs, dont une brève apparition dans Il était une fois le Bronx, la première réalisation de son vieux complice Robert De Niro, l\'acteur se fait rare. En 2007, Raisons d\'Etat marque son retour après huit ans d\'absence du grand écran.', '1943-02-09'),
(20, 'Leone', 'Sergio', 'sergio-leone.jpg', 'Né le 3 janvier 1929 à Rome, fils du metteur en scène italien Roberto Roberti et de l\'actrice Bice Valerian, Sergio Leone était, comme qui dirait, prédestiné au cinéma. Il débute dans le milieu en tant qu\'assistant, aussi bien de cinéastes italiens (Le Voleur de bicyclette de Vittorio De Sica, 1949) que de cinéastes américains tournant en Italie (Quo Vadis de Mervyn LeRoy, 1951 ; Ben-Hur de William Wyler, 1960). C\'est vers la fin des années 50 qu\'il commence à écrire ses premiers scénarios, puis remplace le réalisateur Mario Bonnard sur le tournage de Les Derniers Jours de Pompei (1959), au générique duquel il sera crédité comme co-réalisateur.\r\n\r\nSuite au succès de cette aventure, il se voit confier la réalisation d\'un peplum, Le Colosse de Rhodes (1961). Après avoir dirigé la seconde équipe du film de Robert Aldrich, Sodome et Gomorrhe (1961), et face au déclin progressif du western américain, le cinéaste italien s\'approprie ce genre en accouchant d\'un remake du film Le Garde du corps d\'Akira Kurosawa : Pour une poignée de dollars (1964), qu\'il réalise sous le pseudonyme de Bob Robertson. Par ce deuxième long-métrage, Leone s\'impose comme le chantre d\'un style nouveau, celui du western \"spaghetti\". Le cinéaste s\'evertue en effet à briser les codes du western traditionnel, en en parodiant les situations typiques, en privilégiant la lenteur et en étirant les scènes à l\'excès, en usant des gros plans (colts, visages, regards) comme s\'il filmait des paysages... La naissance de ce style propre à Leone marque aussi la première collaboration du maître avec le décorateur Carlo Simi et le compositeur Ennio Morricone, qui signera la bande originale de tous ses autres films. En plus d\'être un succès mondial, le film contribue à l\'émergence d\'une star américaine, Clint Eastwood, qui reprendra d\'ailleurs le rôle du célèbre Homme sans nom dans les deux autres opus de la trilogie dite \" des dollars \".\r\n\r\nDans Et pour quelques dollars de plus (1965), Leone paufine et approfondit ce qui faisait l\'esprit et l\'atmosphère de son premier western, et travaille avec des pointures comme les comédiens Lee Van Cleef et Gian Maria Volonte (que l\'on avait déjà vu dans Pour une poignée de dollars). Le troisième volet de sa trilogie, Le Bon, la brute et le truand (1966), peut se voir comme l\'affirmation pleine et entière de son style, auquel il adjoint une dimension historique. Clint Eastwood partage l\'affiche avec Lee Van Cleef et Eli Wallach.\r\n\r\nMalgré une certaine lassitude face aux westerns, Leone s\'associe à la Paramount pour réaliser l\'ambitieux Il était une fois dans l\'Ouest (1968), véritable opéra moderne dans lequel le cinéaste convie des stars internationales comme Henry Fonda, Charles Bronson et Claudia Cardinale. Le film s\'effondre au box-office américain, mais triomphe en France (14 millions d\'entrées). Beaucoup le considèrent comme son chef d\'oeuvre. Il était une fois dans l\'Ouest, dont l\'histoire fut co-signée par Bernardo Bertolucci et Dario Argento, est aussi l\'oeuvre introductrice d\'une seconde trilogie, consacrée à l\'histoire de l\'Amérique. Après trois années d\'absence, Leone réalise péniblement Il était une fois la révolution (1971), avec Rod Steiger et James Coburn, film dans lequel il dépeint la révolution méxicaine et les massacres de 1913. Par ailleurs, il produit et participe à la réalisation de deux westerns spaghettis, Mon nom est Personne (1973) de Tonino Valerii et Un génie, deux associés, une cloche (1975) de Damiano Damiani, tous deux avec Terence Hill.\r\n\r\nAprès avoir décliné la réalisation du premier Le Parrain (1972), qui sera finalement confiée au jeune Francis Ford Coppola, le cinéaste italien se penche sur son propre projet de film de gangsters, Il était une fois en Amérique (1984). Leone mettra plus de dix ans à monter cette fresque new-yorkaise, qui s\'étend des années 20 aux années 60, et dans laquelle on retrouve notamment Robert De Niro, James Woods et Joe Pesci.\r\nPeu avant sa mort, qui survient le 30 avril 1989, le cinéaste avait développé un ultime scénario, construit autour du siège de Leningrad entre 1941 et 1944. En seulement six films, Leone a su imposer un style cinématographique personnel. Son oeuvre a exercé une influence fondamentale sur le cinéma contemporain, particulièrement chez les réalisateurs cinéphiles comme Quentin Tarantino.', '1921-01-03'),
(21, 'Fincher', 'David', 'david-fincher.jpg', 'Dès l\'âge de huit ans, David Fincher réalise de nombreux films dans le cadre familial. Passionné par le travail de George Lucas, il intègre dix ans plus tard la société d\'effets spéciaux de son modèle, Industrial, Light and Magic. Durant ses quatre années passées chez ILM, Fincher travaille ainsi sur les effets spéciaux du Retour du Jedi, d\'Indiana Jones et le Temple maudit ou de L\'Histoire sans fin. Fort de cet acquis, il se spécialise ensuite dans la réalisation de publicités et de clips musicaux, créant sa propre société de production, Propaganda Films. Son travail pour la marque Nike et pour des artistes comme Madonna, Aerosmith ou les Rolling Stones l\'impose vite comme un jeune surdoué de l\'image.\r\n\r\nA 29 ans, David Fincher s\'engage avec la Fox pour signer son premier long métrage, Alien 3, troisième volet de la saga fantastique emmenée par Sigourney Weaver. Le résultat, très sombre et virtuose, n\'empêche pas le studio de brider le jeune cinéaste qui gardera un souvenir amer de cette expérience. Déterminé à acquérir au plus vite une vraie liberté d\'action, Fincher fait équipe en 1994 avec une petite société de production indépendante de l\'époque, New Line Cinema, pour réaliser le thriller Seven. Centré sur les sept pêchés capitaux, il devient instantanément un classique du film de serial killer. Mettant en vedette Brad Pitt et Morgan Freeman, il pose aussi un regard désespéré et très sombre sur la société.\r\n\r\nAprès le choc Seven, David Fincher prend tout le monde à contre-pied en réalisant, deux ans plus tard, The Game. Avec ce thriller manipulateur porté par Michael Douglas, qui ne remporte pas le même succès critique et public, le cinéaste creuse un peu plus son oeuvre désenchantée sur le monde contemporain. Il s\'attaque ensuite en 1999 à la réalisation de Fight club. Adaptation d\'un roman de Chuck Palahniuk, le film, sulfureux et très vite culte, ne laisse personne indifférent. Pour l\'occasion, Fincher retrouve Brad Pitt et confie à Edward Norton le rôle principal.\r\n\r\nVisiblement désireux de ne pas se replonger dans une œuvre aussi polémique, David Fincher réalise en 2002 Panic room, thriller très classique en forme de huis-clos dans lequel évoluent notamment Jodie Foster et Forest Whitaker. Après une parenthèse de cinq ans marquée par la réalisation de clips et l\'avortement de son M : i : III (pour divergences artistiques avec Tom Cruise), David Fincher revient sur le devant de la scène en 2007 avec l\'ambitieux Zodiac, centré sur les agissements d\'un des plus célèbres tueurs de l\'Histoire des Etats-Unis. Il enchaîne immédiatement avec la réalisation du film fantastique L\'Etrange histoire de Benjamin Button, qui marque sa troisième collaboration avec Brad Pitt.\r\n\r\nAvec son film suivant, The Social Network, Fincher se centre sur la création du réseau social Facebook. En découle un portrait mordant de son créateur Mark Zuckerberg, interprété par Jesse Eisenberg. Si le cinéaste y emprunte en partie le classicisme de la mise en scène de Zodiac, il renoue néanmoins avec une réalisation plus stylisée (cf. Seven, Fight Club). En 2011, il crée à nouveau l\'évènement en mettant en scène une nouvelle adaptation du premier tome de la trilogie Millenium de Stieg Larsson : Millenium : Les hommes qui n’aimaient pas les femmes, avec Daniel Craig et Rooney Mara dans les rôles principaux. En 2014 sort en salles son dixième long métrage de fiction, le thriller Gone Girl dans lequel Ben Affleck cherche par tous les moyens à savoir ce qui est arrivé à sa femme qui a mystérieusement disparu.\r\n\r\nParallèlement à sa carrière exceptionnelle de réalisateur pour le cinéma, David Fincher tente l\'aventure du petit écran, en produisant la série politique House of Cards portée par un Kevin Spacey en très grande forme.', '1962-08-28'),
(22, 'Fonda', 'Henry', 'henry-fonda.jpg', 'Né le 16 mai 1905 dans le Nebraska et mort le 12 août 1982 à Los Angeles, Henry Fonda est le père de l\'acteur Peter Fonda (Easy Rider), de l\'actrice Jane Fonda (La Poursuite impitoyable) et le grand-père l\'actrice Bridget Fonda (Jackie Brown).\r\n\r\nConsidéré comme l\'un des acteurs au palmarès le plus impressionnant de tous les temps, Henry Fonda a joué dans plus de 90 films, et fut fidèle à plusieurs metteurs en scène incontournables de l\'histoire du septième art, tels Fritz Lang, Henry King, Henry Hathaway, Sidney Lumet et surtout John Ford, avec qui il collabora à huit reprises.\r\n\r\nA l\'université, alors qu\'il compte devenir journaliste, Henry Fonda se dirige vers le théâtre, en suivant les cours de Dorothy Brando, la mère de Marlon. Entre 1925 et 1934, il se consacre ainsi à la scène, comme avec les pièces You and I de Philip Barry et surtout New Faces of 1934, dont le succès est considérable. En 1935, le comédien est remarqué par le réalisateur Victor Fleming, qui lui offre son premier rôle au cinéma dans The Farmer takes a wife, l\'adaptation d\'une pièce de théâtre éponyme, dans laquelle il avait déjà joué. Sous contrat avec 20th Century Fox, l’acteur voit sa carrière cinématographique décoller, mais il est contraint de jouer dans des productions qui ne lui plaisent guère (en dehors de L\' Etrange incident de William Wellman, Henry Fonda déclare n\'avoir joué que dans des films de mauvaise qualité pendant les années 1930). En 1942, il s’engage dans la marine pour prendre part à La Seconde Guerre mondiale, et est démobilisé en octobre 1945. De retour aux Etats-Unis, il poursuit sa carrière cinématographique qui s’apprête à connaître son apogée.\r\n\r\nSi, durant les années 1930, Henry Fonda s\'illustre dans plusieurs films policiers ou romantiques (J\'ai le droit de vivre de Fritz Lang, Spendthrift de Raoul Walsh, etc.), c\'est surtout le western qui caractérise l\'orientation de la carrière de l\'acteur dans la décennie suivante, avec une certaine tendance à jouer des cow-boys sympathiques et courageux. Parmi ses films les plus marquants, on peut compter Les Raisins de la colère (1940), pour lequel il est nommé à l\'Oscar du meilleur acteur, ainsi que La Poursuite infernale (1946) et Le Massacre de Fort Apache (1948), deux westerns mis en scène par John Ford.\r\n\r\nEntre 1948 et 1951, il revient au théâtre, notamment avec la pièce Mister Roberts qu\'il joue 1671 fois, montrant à quel point sa réussite sur grand écran ne le conduit pas à délaisser la scène. Dans les années 1950, il enchaîne trois rôles cultes dans trois films qui ne le sont pas moins : Guerre et paix de King Vidor, Le Faux Coupable d’Alfred Hitchcock et 12 hommes en colère de Sidney Lumet, sur lequel il est également producteur.\r\n\r\nMême si sa carrière connaît une légère perte de vitesse à partir des années 1960, Henry Fonda est tout de même à l’affiche de Le Jour le plus long (1962), La Conquête de l\'Ouest (1962), L\' Etrangleur de Boston (1968), et surtout Il était une fois dans l\'Ouest de Sergio Leone en 1969, où il tient un rôle de personnage antipathique, bien loin des cow-boys héroïques qu\'il avait l\'habitude d\'incarner...\r\n\r\nParallèlement à sa carrière cinématographique, Henry Fonda tourne également dans plusieurs téléfilms, comme par exemple L\' Homme en fuite (TV) de Don Siegel (L\' Inspecteur Harry) en 1967. Pendant les années 1970, l’acteur s’illustre ainsi principalement dans des productions pour la télévision, mais continue tout de même de s’afficher à travers des films pour le grand écran. Parmi eux, on peut compter Le Reptile de Joseph L. Mankiewicz, dans lequel il incarne un directeur de prison souhaitant améliorer le sort des détenus, et encore La Bataille de Midway, un film se déroulant pendant la Seconde guerre mondiale, où il côtoie Charlton Heston.\r\n\r\nEn 1982, il remporte enfin l’Oscar du meilleur acteur pour sa prestation dans La Maison du lac, aux côtés de Katharine Hepburn et de sa fille Jane Fonda. Atteint d’un cancer, il meurt à Los Angeles la même année, à l’âge de 77 ans.', '1905-05-16'),
(23, 'Bronson', 'Charles', 'charles-bronson.jpg', 'Né Charles Buchinski le 3 novembre 1921 à Ehrenfeld en Pennsylvanie dans une famille d\'immigrés lituaniens, Charles Bronson commence en travaillant dans des mines de charbon. Après avoir effectué son service militaire pendant la Seconde Guerre mondiale, il étudie la comédie en Californie avant de débuter au cinéma dans les années cinquante, où son visage très typé et sa musculature de boxeur le vouent durablement aux rôles de \"durs\" tout au long d\'une carrière qui s\'étale sur près de quarante ans.\r\n\r\nD\'abord cantonné aux séries B où divers cinéastes exploitent son physique imposant et monolithique, Charles Bronson apparaît également chez Robert Aldrich, dans Bronco Apache ou chez Samuel Fuller dans Le Jugement des fleches.\r\n\r\nC\'est en 1960 qu\'il a pour la première fois l\'occasion de se distinguer, dans Sept mercenaires de John Sturges et trois ans plus tard, sous la direction du même réalisateur, aux côtés de Steve McQueen dans La Grande évasion. Présent au générique de Propriété interdite de Sydney Pollack, Charles Bronson accède définitivement à la célébrité en 1967 en campant l\'un des Douze Salopards pour Robert Aldrich.\r\n\r\nDevenu une star internationale, l\'acteur multiplie alors les productions de premier plan, partenaire d\'Alain Delon dans Adieu l\'ami de Jean Herman et surtout chez Sergio Leone, dans Il était une fois dans l\'Ouest, où son interprétation de l\'Homme à l\'Harmonica face à Henry Fonda et Claudia Cardinale sur la légendaire musique d\'Ennio Morricone reste sans doute son rôle le plus célèbre sur grand écran.\r\n\r\nMarié à l\'actrice Jill Ireland, Charles Bronson tourne par la suite à plusieurs reprises seul ou avec son épouse dans des films où ses compositions se font de plus en plus stéréotypées, à l\'image du héros archétypal qu\'il incarne en 1974 pour Michael Winner dans Un Justicier dans la ville.\r\n\r\nL\'acteur qui souffrait depuis la fin des années 90 de la maladie d\'Alzheimer s\'est éteint en 2003 des suites d\'une pneumonie.\r\nPère de trois enfants, Charles Bronson, qui s\'était progressivement éloigné des plateaux de cinéma, avait auparavant signé un retour remarqué en 1991 dans l\'Indian Runner de Sean Penn, où muré dans son silence et sa solitude, il avait créé pour l\'occasion une mémorable figure paternelle.', '1921-11-03'),
(24, 'Wolff', 'Frank', 'frank-wolff.jpg', 'l débute dans son pays avant de s\'installer en Europe où il travaille principalement dans le cinéma italien. On le voit aussi bien dans des films d\'auteur que dans des films de genre, dont beaucoup de westerns spaghetti. Il tient l\'un de ses rôles les plus importants dans Salvatore Giuliano de Francesco Rosi, et apparaît également dans Il était une fois dans l\'Ouest, de Sergio Leone. Il se suicide en décembre 1971.', '1928-05-11'),
(25, 'Eastwood', 'Clint', 'clint-eastwood.jpg', 'Né d\'un père comptable, le jeune Clinton mène avec ses parents une vie de nomade. Il fait des petits boulots sans grande conviction, puis part à l\'armée, où ses rencontres l\'amènent à travailler chez Universal. Il fait sa première apparition à l\'écran en 1955 dans La Revanche de la créature, puis enchaîne les petits rôles anecdotiques.\r\n\r\nSon ascension débute avec un rôle dans la série Rawhide. Entre 1956 et 1958, il apparaît successivement dans Ne dites jamais adieu, La Corde est prête, Escapade au Japon, et C\'est la guerre. Peinant à percer dans son pays, Clint accepte de partir en Italie, et c\'est grâce à Sergio Leone et la trilogie Pour une poignée de dollars, Et pour quelques dollars de plus et Le Bon, la brute et le truand qu\'il devient très populaire. Devenu une star en quelques années, Eastwood retourne aux États-Unis et crée sa propre maison de production, Malpaso Productions, s\'offrant ainsi un peu d\'indépendance. De sa rencontre avec Don Siegel naît une belle amitié et une longue collaboration (cinq films, dont Les Proies, L\'Inspecteur Harry ou encore L\'Évadé d\'Alcatraz).\r\n\r\nProfitant du succès de Quand les aigles attaquent (1968), Eastwood, qui se spécialise dans les westerns et les films policiers, passe derrière la caméra en 1971 avec Un frisson dans la nuit. L\'année suivante, L\'Inspecteur Harry (qui aura quatre suites), dans lequel il incarne un flic violent, le consacre encore plus auprès du grand public. Il continue alors de réaliser et de jouer dans ses propres films : L\' Homme des hautes plaines (1972), Josey Wales hors la loi (1976) ou encore Honkytonk Man (1982).\r\n\r\nBird, film sur la vie de Charlie Parker, confirme la passion du réalisateur pour le jazz. En 1992, son western Impitoyable, à l\'ambiance crépusculaire, est plébiscité par ses pairs : le film remporte quatre Oscars dont ceux du Meilleur film et du Meilleur réalisateur. L\'acteur/réalisateur, alors âgé de 65 ans, est au sommet et fait preuve, les années passant, d\'une maturité qui grandit son cinéma. Avec toujours la double casquette de réalisateur et acteur, il bouleverse dans Sur la route de Madison puis enchaîne avec Minuit dans le jardin du bien et du mal, Jugé coupable, Space Cowboys et Créance de sang.\r\n\r\nEn 2003, Clint Eastwood signe le drame Mystic River, porté par Sean Penn, Tim Robbins et Kevin Bacon, qui lui fait monter les marches du Festival de Cannes pour la quatrième fois. Deux ans plus tard, avec le drame Million dollar baby, le cinéaste remporte à nouveau, douze ans après Impitoyable, l\'Oscar du Meilleur film et du Meilleur réalisateur. Le succès du film est total, ses comédiens Hilary Swank et Morgan Freeman étant sacrés Meilleure actrice et Meilleur second rôle masculin.\r\n\r\nClint Eastwood change alors de registre et décide de réaliser un diptyque autour de la bataille d\'Iwo Jima : Mémoires de nos pères pour le point de vue américain, et Lettres d\'Iwo Jima pour le point de vue japonais. En 2008, cette légende du cinéma enchaine la réalisation de deux films, L\'Echange, drame emmené par Angelina Jolie, et Gran Torino, qui marque son grand retour devant la caméra. Le film remporte le César du Meilleur film étranger, et Eastwood annonce qu\'il s\'agit de sa dernière prestation en tant qu\'acteur.\r\n\r\nEn 2010, le réalisateur confie le rôle de l\'homme politique Nelson Mandela à Morgan Freeman dans Invictus, avec Matt Damon. Ce dernier s\'illustre de nouveau dans le film suivant d\'Eastwood : Au-delà, une réflexion crépusculaire sur une éventuelle vie après la mort. Après Mandela, c\'est au tour du directeur du FBI John Edgar Hoover de faire l\'objet d\'un traitement par le metteur en scène dans J. Edgar, avec un Leonardo DiCaprio méconnaissable dans le rôle-titre. En 2012, Clint Eastwood reprend finalement son rôle d\'acteur pour le film Une nouvelle chance, réalisé par Robert Lorenz.\r\n\r\nIl prend ensuite les rênes d\'un genre auquel il ne s\'était jamais confronté auparavant en réalisant l\'entraînant Jersey Boys, l\'adaptation cinématographique de la comédie musicale homonyme de Broadway créée en 2005,sur l\'histoire de Frankie Valli et les Four Seasons. En 2015 sort American Sniper, qui suit le parcours de Chris Kyle, le tireur d\'élite le plus redoutable de l\'histoire des États-Unis et qui aurait tué plus de 200 personnes durant toute sa carrière militaire. L\'année suivante, il fait tourner Tom Hanks pour la première fois dans Sully, l\'histoire exceptionnelle du pilote qui a fait amerrir un avion sur l\'Hudson.\r\n\r\nEn 2018, il met en scène Le 15h17 pour Paris, portant à l\'écran l\'histoire vraie de trois Américains ayant contribué à faire avorter une attaque terroriste dans le train Thalys reliant Paris à Amsterdam en 2015.', '1930-05-31');

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
  `resume_f` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `duree` varchar(255) NOT NULL,
  `video_f` varchar(255) NOT NULL,
  PRIMARY KEY (`id_f`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `films`
--

INSERT INTO `films` (`id_f`, `titre_f`, `poster_f`, `annee_f`, `resume_f`, `duree`, `video_f`) VALUES
(18, 'Les Affranchis', 'les-affranchis.jpg', 1990, 'Dans les années 1950, à Brooklyn, le jeune Henry Hill a l\'occasion de réaliser son rêve : devenir gangster lorsqu\'un caïd local l\'intègre à son équipe. C\'est alors qu\'il rencontre James et Tommy, 2 truands d\'une rare brutalité. Impressionné, Henry s\'associe avec eux pour débuter un trafic très lucratif. Lorsqu\'il séduit la ravissante Karen, le jeune mafieux s\'imagine que plus rien ni personne ne pourra jamais lui résister.', '2h28m', 'https://www.dailymotion.com/embed/video/x4ouyhw'),
(19, 'Seven', 'seven.jpg', 1996, 'Peu avant sa retraite, l\'inspecteur William Somerset, un flic désabusé, est chargé de faire équipe avec un jeune idéaliste, David Mills. Ils enquêtent tout d\'abord sur le meurtre d\'un homme obèse que son assassin a obligé à manger jusqu\'à ce que mort s\'ensuive. L\'enquête vient à peine de commencer qu\'un deuxième crime, tout aussi macabre, est commis, puis un troisième. Petit à petit, les deux policiers font le lien entre tous ces assassinats.', '2h08m', 'https://www.dailymotion.com/embed/video/x193cm'),
(20, 'Il etait une fois dans l\'Ouest', 'il_etait_une_fois_dans_l.ouest.jpg', 1969, 'Alors qu\'il prépare une fête pour sa femme, Bet McBain est tué avec ses trois enfants. Jill McBain hérite alors les terres de son mari, terres que convoite Morton, le commanditaire du crime (celles-ci ont de la valeur maintenant que le chemin de fer doit y passer). Les soupçons se portent sur un aventurier, Cheyenne.', '2h55m', 'https://www.dailymotion.com/embed/video/x2ksbdl'),
(21, 'Le Bon, La Brute Et Le Truand', 'le_bon._la_brute_et_le_truand.jpg', 1968, 'Alors que la guerre de Sécession fait rage aux Etats-Unis, trois bandits n\'ont qu\'une préoccupation : l\'argent. Joe livre régulièrement à la justice son copain Tuco, dont la tête est mise à prix, puis empoche la prime et délivre son complice. Sentenza abat, avec un égal sang-froid, l\'homme qu\'il devait tuer moyennant récompense, et celui qui l\'avait mandaté pour cette exécution.', '3h06m', 'https://www.dailymotion.com/embed/video/x21jawn'),
(22, 'Il Etait Une Fois En Amerique', 'il_etait_une_fois_en_amerique.jpg', 1984, 'Anéanti par la perte, Noodles laisse les souvenirs remonter à la surface de sa mémoire dans une fumerie d\'opium du quartier chinois. Quarante ans plus tôt, avec ses amis d\'enfance, ils formaient une bande de gamins débrouillards déjà prêts à affronter tous les dangers pour sortir de la pauvreté. Puis, il y avait la première flamme d\'amour avec l\'inaccessible Deborah. Pour sauver ses amis, il les avait vendus. Cependant, l\'arrestation tourna à la boucherie et tous furent tués.', '3h49', 'https://www.dailymotion.com/embed/video/xjdef4'),
(23, 'Gladiator', 'gladiator.jpg', 2000, 'Le général romain Maximus est le plus fidèle soutien de l\'empereur Marc Aurèle, qu\'il a conduit de victoire en victoire. Jaloux du prestige de Maximus, et plus encore de l\'amour que lui voue l\'empereur, le fils de Marc Aurèle, Commode, s\'arroge brutalement le pouvoir, puis ordonne l\'arrestation du général et son exécution. Maximus échappe à ses assassins, mais ne peut empêcher le massacre de sa famille. Capturé par un marchand d\'esclaves, il devient gladiateur et prépare sa vengeance.', '2h51m', 'https://www.dailymotion.com/embed/video/x2cst92'),
(24, 'Tu ne tueras point', 'tu_ne_tueras_point.jpg', 2016, 'Quand la Seconde Guerre mondiale a éclaté, Desmond, un jeune américain, s\'est retrouvé confronté à un dilemme : comme n\'importe lequel de ses compatriotes, il voulait servir son pays, mais la violence était incompatible avec ses croyances et ses principes.', '2h19m', 'https://www.dailymotion.com/embed/video/x544l65'),
(25, 'Lion', 'lion.jpg', 2017, 'A 5 ans, Saroo se retrouve seul dans un train traversant l\'Inde qui l\'emmène malgré lui à des milliers de kilomètres de sa famille. Perdu, le petit garçon doit apprendre à survivre seul dans l\'immense ville de Calcutta. Après des mois d\'errance, il est recueilli dans un orphelinat et adopté par un couple d\'Australiens. 25 ans plus tard, Saroo est devenu un véritable Australien mais pense toujours à sa famille en Inde.', '2h09', 'https://www.dailymotion.com/embed/video/x5u3jcz'),
(26, 'Fight Club', 'fight_club.jpg', 1999, 'Jack est un jeune expert en assurance insomniaque, désillusionné par sa vie personnelle et professionnelle. Lorsque son médecin lui conseille de suivre une thérapie afin de relativiser son mal-être, il rencontre dans un groupe d\'entraide Marla avec qui il parvient à trouver un équilibre.', '2h31m', 'https://www.dailymotion.com/embed/video/x19h54z'),
(27, 'Vol au-dessus d\'un nid de coucou', 'vol_au-dessus_d.un_nid_de_coucou.jpg', 1976, 'Pour échapper à la prison, le détenu du droit commun Randall P. McMurphy se fait interner en simulant la folie. Dès son arrivée à l\'hôpital psychiatrique, il assiste aux traitements thérapeutiques dispensés par miss Ratched, l\'autoritaire et tyrannique infirmière en chef dont il cherche à bouleverser les méthodes.', '2h14m', 'https://www.dailymotion.com/embed/video/x2jt3z1'),
(28, 'Pulp Fiction', 'pulp_fiction.jpg', 1994, 'L\'odyssée sanglante et burlesque de petits malfrats dans la jungle de Hollywood à travers trois histoires qui s\'entremêlent. Dans un restaurant, un couple de jeunes braqueurs, Pumpkin et Yolanda, discutent des risques que comporte leur activité. Deux truands, Jules Winnfield et son ami Vincent Vega, qui revient d\'Amsterdam, ont pour mission de récupérer une mallette au contenu mystérieux et de la rapporter à Marsellus Wallace.', '2h58m', 'https://www.dailymotion.com/embed/video/x55vx5h'),
(29, 'Django Unchained', 'django_unchained.jpg', 2013, 'Un ancien esclave s\'associe avec un chasseur de primes d\'origine allemande qui l\'a libéré : il accepte de traquer avec lui des criminels recherchés. En échange, il l\'aidera à retrouver sa femme perdue depuis longtemps et esclave elle-aussi. Un western décoiffant.', '2h45m', 'https://www.dailymotion.com/embed/video/x3lyid4'),
(30, 'Les Evades', 'les_evades.jpg', 1995, 'En 1947, Andy Dufresne, un jeune banquier, est condamné à la prison à vie pour le meurtre de sa femme et de son amant. Ayant beau clamer son innocence, il est emprisonné à `Shawshank\', le pénitencier le plus sévère de l\'Etat du Maine. Il y fait la rencontre de Red, un homme désabusé, détenu depuis 20 ans. Commence alors une grande histoire d\'amitié entre les deux hommes.', '2h22m', 'https://www.dailymotion.com/embed/video/x4opl5t'),
(31, 'Bohemian Rhapsody', 'bohemian_rhapsody.jpg', 2018, 'Du succès fulgurant de Freddie Mercury à ses excès, risquant la quasi-implosion du groupe, jusqu\'à son retour triomphal sur scène lors du concert Live Aid, alors qu\'il était frappé par la maladie, découvrez la vie exceptionnelle d\'un homme qui continue d\'inspirer les \'outsiders\', les rêveurs et tous ceux qui aiment la musique.', '2h13m', 'https://www.dailymotion.com/embed/video/x6w72oh'),
(32, 'Coco', 'coco.jpg', 2017, 'Depuis plusieurs générations, la musique est bannie dans la famille de Miguel. Un vrai déchirement pour le jeune garçon dont le rêve est de devenir un musicien aussi accompli que son idole, Ernesto de la Cruz. Bien décidé à prouver son talent, Miguel, par un étrange concours de circonstances, se retrouve propulsé dans un endroit aussi étonnant que coloré : le Pays des Morts. Là, il se lie d\'amitié avec Hector, un gentil garçon mais un peu filou sur les bords', '1h49m', 'https://www.dailymotion.com/embed/video/x61oc5m'),
(33, 'Le Parrain', 'le_parrain.jpg', 1972, 'En 1945, à New York, les Corleone sont une des 5 familles de la mafia. Don Vito Corleone, `parrain\' de cette famille, marie sa fille à un bookmaker. Sollozzo, `parrain\' de la famille Tattaglia, propose à Don Vito une association dans le trafic de drogue, mais celui-ci refuse. Sonny, un de ses fils, y est quant à lui favorable. Afin de traiter avec Sonny, Sollozzo tente de faire tuer Don Vito, mais celui-ci en réchappe.', '2h58m', 'https://www.dailymotion.com/embed/video/x7uzuv'),
(34, '12 hommes en colere', '12_hommes_en_colere.jpg', 1957, 'Un jeune homme d\'origine modeste est accusé du meurtre de son père et risque la peine de mort. Le jury composé de douze hommes se retire pour délibérer et procède immédiatement à un vote : onze votent coupable, or la décision doit être prise à l\'unanimité. Le juré qui a voté non-coupable, sommé de se justifier, explique qu\'il a un doute et que la vie d\'un homme mérite quelques heures de discussion. Il s\'emploie alors à les convaincre un par un.', '1h36m', 'https://www.dailymotion.com/embed/video/x3golre'),
(35, 'La Liste de Schindler', 'la_liste_de_schindler.jpg', 1994, 'Les Allemands, victorieux de la Pologne, regroupent les Juifs dans des ghettos dans le but de s\'en servir comme main d\'oeuvre bon marché. Oskar Schindler, industriel et bon vivant, rachète pour une bouchée de pain une fabrique d\'ustensiles de cuisine.', '3h17m', 'https://www.dailymotion.com/embed/video/x72ia34'),
(36, 'La Ligne verte', 'la_ligne_verte.jpg', 2000, 'Paul Edgecomb, pensionnaire centenaire d\'une maison de retraite, est hanté par ses souvenirs. Gardien-chef du pénitencier de Cold Mountain, en 1935, en Louisiane, il était chargé de veiller au bon déroulement des exécutions capitales au bloc E (la ligne verte) en s\'efforçant d\'adoucir les derniers moments des condamnés. Parmi eux se trouvait un colosse du nom de John Coffey, accusé du viol et du meurtre de deux fillettes.', '3h09m', 'https://www.dailymotion.com/embed/video/x4rxux'),
(37, 'Gran Torino', 'gran-torino.jpg', 2009, 'Walt Kowalski, un vétéran de la guerre de Corée, vient de perdre sa femme. Seul, misanthrope, bougon et raciste, il veille jalousement sur sa Ford Gran Torino, râlant sans cesse contre les habitants de son quartier, en majorité d\'origine asiatique. Un jour, son jeune voisin, Tao, tente de lui voler sa voiture sous la pression d\'un gang. Walt s\'aperçoit bientôt que l\'adolescent est littéralement harcelé par les jeunes caïds. Prenant la défense de Tao, Walt devient malgré lui le héros du quartier.', '2h', 'https://www.dailymotion.com/embed/video/x86ron');

-- --------------------------------------------------------

--
-- Structure de la table `genre`
--

DROP TABLE IF EXISTS `genre`;
CREATE TABLE IF NOT EXISTS `genre` (
  `id_g` int(11) NOT NULL AUTO_INCREMENT,
  `genre_du_film` varchar(255) NOT NULL,
  PRIMARY KEY (`id_g`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

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
(10, 'Sci-Fi');

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
(37, 25);

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
(24, 1),
(25, 1),
(25, 2),
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
(37, 25);

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
  PRIMARY KEY (`id_u`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_u`, `type_user`, `username`, `password`, `email`, `create_time`) VALUES
(1, 'admin', 'admin', '$2y$10$IYI.S0ns159RpnhlGAD2FObR7m7TGzkutwL0Ytbz2nRTpDMSg8A4u', 'admin@admin.fr', '2020-02-07 07:27:18'),
(8, 'admin', 'ludo', '$2y$10$juQ0YGpYvd20PsiGrSuBKOFr9Sr4XZHy.CadWMOUdpMZju5tsiEvO', 'rap2fr@hotmail.fr', '2020-02-10 15:02:01'),
(11, 'admin', 'test', '$2y$10$lXa/r6dffmBZDz6NTOodm.zanWd9sRtD7vzNe0w1aGcIH.7vSQqp2', 'test@test.fr', '2020-02-16 18:23:26');

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
