<?php

	###############################################################################
	#### REDIRECTION VERS $url APRES $tps #########################################
	###############################################################################

	function redirect($url, $temps)
	{
		$temps = $temps;

		echo "<script type=\"text/javascript\">

			function redirect() 
			{ 
				window.location='" . $url . "' 
			} 
			
			setTimeout('redirect()','" . $temps ."');

		</script>\n";
	}

	###############################################################################
	#### RETOURNE L'EXTENTION D'UN FICHIER ########################################
	###############################################################################

	function get_extension($nom)
	{
		$nom = explode(".", $nom); // Explode la chaine a chaque point
		$nb = count($nom); // Compte le nombre de segments
		return strtolower($nom[$nb-1]); // Retourne le dernier segment
	}

	###############################################################################
	#### METS EN FORME LE NOM D'UNE IMAGE D'APRES UN TITRE ########################
	###############################################################################

	function renome_image($rep_img, $titre, $ext)
	{
		$titre = str_replace($ext, "", $titre); // Retire l'extention du nom de l'image pour traiter le titre
		$titre = rewrite_url($titre); // Retourne l'url nettoyer, sans espace ..
		$titre = "". $titre ."". $ext .""; // Recompose le nouveau nom de l'image composer avec le titre (du film, de l'artiste ...)
		$url = "". $rep_img."/". $titre .".". $ext .""; // Chemin final de l'image

		return($url);
	}

	###############################################################################
	#### METS EN FORME UN TITRE POUR CONSTRUIRE UNE URL AVEC ######################
	###############################################################################

	function rewrite_url($variable)
	{
		$variable = trim($variable); // Enleve les espace de début et de fin de chaine
		$variable = strtolower($variable); // Mets la chaine en minuscule
		$variable = stripslashes($variable); // Enleve les anti slashes de la chaine
		$variable = str_replace("'","-",$variable); // Remplace les apostrophes par un tiret
		$variable = str_replace("  "," ",$variable); // Remplace les tiples espaces par un espace
		$variable = str_replace(" ","-",$variable);  // Remplace les doubles espaces par un tiret
		$variable = str_replace(".","-",$variable);  // Remplace les espace par un tiret
		$accents = "@ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ"; // Caractères alpha indésirables
		$ssaccents = "aAAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn"; // Caractères alpha de remplacement
		$variable = strtr($variable,$accents,$ssaccents); // Remplace les caractères alpha indésirables
		$speciaux = "?!,:°#(){}[]/|^*+_=£%µ§¤~²\"\$"; // Caractères indésirables
		$ssspeciaux = "--------------------------- s"; // Caractères de remplacement
		$variable = strtr($variable,$speciaux,$ssspeciaux); // Remplace les caractères indésirables
		$variable = str_replace("S", "oe", $variable); // dans le mot oeil par exemple, bug a cause du caractère spécial qui retourne un S au lieu de oe. Corrige le bug
		$variable = str_replace("---","-",$variable); // Remplace les triples tirets par un tiret
		$variable = str_replace("--","-",$variable); // Remplace les doubles tirets par un tiret
		$variable = trim($variable);

		return($variable); // Retourne la variable qui composera l'url avec le titre vers un film, un artiste ...
	}

	###############################################################################
	#### REDIMMENTAIONNE UNE IMAGE ################################################
	###############################################################################

	function redimentionne_image($rep_img, $url)
	{
		$img_screen1 = "250"; // Taille finale de l'image (mettra la plus grande valeur (height ou width) à 250px) tout en conservant les proportions de l'image
		$size = @getimagesize($url); // Taille actuelle de l'image

		$filename = substr(strrchr($url, '/'), 1 ); // Nom de l'image
		$ext = get_extension($url); // Retoune l'extention de l'image. Fonction placée dans racine->functions.php

		if (strstr($ext, "jpg") || strstr($ext, "jpeg")) $src = @imagecreatefromjpeg($url); // Si l'image est un jpg/jpeg
		if (strstr($ext, "png")) $src = @imagecreatefrompng($url); // Si l'image est un png
		if (strstr($ext, "gif")) $src = @imagecreatefromgif($url); // Si l'image est un gif

		$img = @imagecreatetruecolor($img_screen1, round(($img_screen1/$size['0'])*$size['1'])); //  Crée une nouvelle image en couleurs vraies
		if (!$img) $img = @imagecreate($img_screen1, round(($img_screen1/$size['0'])*$size['1'])); // Si methode imagecreatetruecolor() n'a rien donné -> imagecreate() Crée une nouvelle image à palette

		if (strstr($ext, "png")) // Si l'image est un png, le traitement est particulier
		{
			imagealphablending($img, false); // Modifie le mode de blending d'une image -> Gestion de la transparence
			imagesavealpha($img, true); // Détermine si les informations complètes du canal alpha doivent être conservées lors de la sauvegardes d'images PNG

			$trans_layer_overlay = imagecolorallocatealpha($img, 220, 220, 220, 127); // Alloue une couleur à une image tout en gerant la transparence
			imagefill($img, 0, 0, $trans_layer_overlay); // Effectue un remplissage avec la couleur color, dans l'image image, à partir du point de coordonnées (x, y) (le coin supérieur gauche est l'origine (0,0)).
		}

		@imagecopyresampled($img, $src, 0, 0, 0, 0, $img_screen1, round($size['1']*($img_screen1/$size['0'])), $size['0'], $size['1']); // Copie, redimensionne, rééchantillonne une image

		$miniature = "$rep_img/$filename"; // Chemin final de l'image

		if (strstr($ext, "jpg") || strstr($ext, "jpeg")) @ImageJPEG($img, $miniature); // crée un fichier jpeg depuis l'image fournie
		if (strstr($ext, "png"))  @ImagePNG($img, $miniature); // crée un fichier png depuis l'image fournie
		if (strstr($ext, "gif")) @ImageGIF($img, $miniature); // crée un fichier gif depuis l'image fournie

		return($url); // Retourne l'url de l'image
	}
