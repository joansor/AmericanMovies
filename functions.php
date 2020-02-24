<?php

	###############################################################################
	#### REDIRECTION VERS $url APRES $tps #########################################
	###############################################################################

	function redirect($url, $temps)
	{
		$temps = $temps * 1000;

		echo"<script type=\"text/javascript\">

			function redirect(){ window.location='" . $url . "' } 
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
	#### METS EN FORME LE NOM D'UNE IMAGE D'APRES UN TITRE ########################
	###############################################################################

	function renome_image($rep_img, $titre, $ext)
	{
		$titre = str_replace($ext, "", $titre); // Retire l'extention du nom de l'image pour traiter le titre
		$titre = rewrite_url($titre); // Retourne l'url nettoyer, sans espace ..
		$titre = "". $titre .""; // Recompose le nouveau nom de l'image composer avec le titre (du film, de l'artiste ...)
		$url = "". $rep_img."/". $titre .".". $ext .""; // Chemin final de l'image

		return($url);
	}

	###############################################################################
	#### REDIMMENTAIONNE UNE IMAGE ################################################
	###############################################################################

	function redimentionne_image($rep_img, $url)
	{
		$img_screen = "250"; // Taille finale de l'image (mettra la plus grande valeur (height ou width) à 250px) tout en conservant les proportions de l'image
		$size = @getimagesize($url); // Taille actuelle de l'image

		$filename = substr(strrchr($url, '/'), 1 ); // Nom de l'image
		$ext = get_extension($url); // Retoune l'extention de l'image. Fonction placée dans racine->functions.php

		if (strstr($ext, "jpg") || strstr($ext, "jpeg")) $src = @imagecreatefromjpeg($url); // Si l'image est un jpg/jpeg
		if (strstr($ext, "png")) $src = @imagecreatefrompng($url); // Si l'image est un png
		if (strstr($ext, "gif")) $src = @imagecreatefromgif($url); // Si l'image est un gif

		$img = @imagecreatetruecolor($img_screen, round(($img_screen/$size['0'])*$size['1'])); //  Crée une nouvelle image en couleurs vraies
		if (!$img) $img = @imagecreate($img_screen, round(($img_screen/$size['0'])*$size['1'])); // Si methode imagecreatetruecolor() n'a rien donné -> imagecreate() Crée une nouvelle image à palette

		if (strstr($ext, "png")) // Si l'image est un png, le traitement est particulier
		{
			imagealphablending($img, false); // Modifie le mode de blending d'une image -> Gestion de la transparence
			imagesavealpha($img, true); // Détermine si les informations complètes du canal alpha doivent être conservées lors de la sauvegardes d'images PNG

			$trans_layer_overlay = imagecolorallocatealpha($img, 220, 220, 220, 127); // Alloue une couleur à une image tout en gerant la transparence
			imagefill($img, 0, 0, $trans_layer_overlay); // Effectue un remplissage avec la couleur color, dans l'image image, à partir du point de coordonnées (x, y) (le coin supérieur gauche est l'origine (0,0)).
		}

		@imagecopyresampled($img, $src, 0, 0, 0, 0, $img_screen, round($size['1']*($img_screen/$size['0'])), $size['0'], $size['1']); // Copie, redimensionne, rééchantillonne une image

		$miniature = "$rep_img/$filename"; // Chemin final de l'image

		if (strstr($ext, "jpg") || strstr($ext, "jpeg")) @ImageJPEG($img, $miniature); // crée un fichier jpeg depuis l'image fournie
		if (strstr($ext, "png"))  @ImagePNG($img, $miniature); // crée un fichier png depuis l'image fournie
		if (strstr($ext, "gif")) @ImageGIF($img, $miniature); // crée un fichier gif depuis l'image fournie

		return($url); // Retourne l'url de l'image
	}

/************************************************************************/
/************************ Paginator *************************************/
/************************************************************************/


	function number($nb_ligne, $url, $count, $p)
    {
        global $start;

// $count = Nombre de films total
// $nb_ligne = Nombre d'element a afficher par page
// $p = La page sur laquelle on est
// $start = A combien on demarre la requette
// $nb_page = Nombre de page total
// $nb_page2 = ?

// --- 19, 10, 1, 0, 1, 9 ----
 // 0, 1, 1, 0, 0, 0
        $variable = "<ul class=\"paginator\">\n";

            if ($nb_ligne > 0)
            { 
                $nb_page = intval($count / $nb_ligne);
                $nb_page2 = $count % $nb_ligne;

                if (!$p) $p = 1;
                $start = $p * $nb_ligne - $nb_ligne;

                // echo"--- $count, $nb_ligne, $p, $start, $nb_page, $nb_page2 ----";

                if ($nb_page2 > 0) $nb_page++;
                $i = 1;

                if ($p > 1)
                {
                    $end2 = $p - 1;

                    if($end2 > 0) $variable .= "<li class=\"paginator__item paginator__item--prev\"><a href=\"" . $url . "/" . $end2 . "\"><i class=\"icon ion-ios-arrow-back\"></i></a></li>"; // Fleche prev
                    // if($p > 6) $variable .="<li class=\"paginator__item paginator__item--active\"><a href=\"" . $url . "/1\" class=\"btn btn-default btn-xs m-r-xs\">1</a></li>"; // Element actif
                } 

                while ($i <= $nb_page && $nb_page <> 1)
                {
                    if (($i == ($p-1)) || ($i == $p) || ($i == ($p + 1)) || ($i >= 1 && $i < ($i + 5)) || ($i == $nb_page))
                    {
                        $numero_page = $i; 

                        if ($p < 6) 
                        { 
                            if ($i < 11) 
                            { 
                                if($p == $i) $variable .="<li class=\"paginator__item paginator__item--active\"><a href=\"#\">$numero_page</a></li>"; // Element actif
                                else $variable .="<li class=\"paginator__item\"><a href=\"" . $url . "/" . $i . "\" class=\"btn btn-default btn-xs m-r-xs\">$numero_page</a></li>";
                            } 
                        } 
                        else if ($p > ($nb_page-5))
                        { 
                            if ($i > $nb_page - 10) 
                            { 
                                if($p == $i) $variable .="<li class=\"paginator__item paginator__item--active\"><a href=\"#\">$numero_page</a></li>"; // Element actif
                                else $variable .="<li class=\"paginator__item\"><a href=\"" . $url . "/" . $i . "\" class=\"btn btn-default btn-xs m-r-xs\">$numero_page</a></li>";
                            }
                        } 
                        else 
                        {
                            if ($i > ($p-5) && $i < ($p+6)) 
                            { 
                                if($p == $i) $variable .="<li class=\"paginator__item paginator__item--active\"><a href=\"#\">$numero_page</a></li>"; // Element actif
                                else $variable .="<li class=\"paginator__item\"><a href=\"" . $url . "/" . $i . "\" class=\"btn btn-default btn-xs m-r-xs\">$numero_page</a></li>";
                            } 
                        }
                    } 

                    $i++;
                }

                $end = $start + $nb_ligne;

                if ($p < $nb_page)
                {
                    $end = $p + 1;

                    $variable .="<li class=\"paginator__item paginator__item--next\"><a href=\"" . $url . "/" . $end . "\"><i class=\"icon ion-ios-arrow-forward\"></i></a><li>"; // Fleche next
                } 
            } 

			$variable .="</ul>\n";

			return $variable;
    }
