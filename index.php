<?php
require_once 'vendor/autoload.php';

    setlocale(LC_TIME, 'fr_FR.utf8','fra');

	$superglobals = array($_SERVER, $_ENV, $_FILES, $_COOKIE, $_POST, $_GET);

	foreach($superglobals as $superglobal)
	{ 
		foreach($superglobal as $key => $value) { if(!is_array($value)) { ${$key} = trim(rawurldecode($value)); /* echo "$key $value<br>"; */ }  else { ${$key} = $value; } }
	}

$router = new Router($_GET['url']);

//liste de nos routes
//deuxième niveau

$router->get("/artists/:categorie/show/:id", "Artists.show"); // Artists.show => Artists = ArtistsController.php ; show = function show(méthod)
$router->get('/artists/add', "Artists.add");
$router->post('/artists/insert', "Artists.insert");
$router->get('/artists/edition/:id', "Artists.edition");
$router->post('/artists/update/:id', "Artists.update");
$router->get('/artists/suppression/:id', "Artists.suppression");
$router->get("/artists/:categorie", "Artists.categorie");

$router->get('/films/show/:id', "Home.show");
$router->get('/films/add', "Home.add");
$router->post('/films/insert', "Home.insert");
$router->get('/films/edition/:id', "Home.edition");
$router->post('/films/update/:id', "Home.update");
$router->get('/films/suppression/:id', "Home.suppression");

$router->get("/genres/list/:id", "Genres.list");
$router->post('/admin/log', 'Admin.log');

//premier niveau

$router->get("/artists", "Artists.index");
$router->get("/admin", "Admin.form");
$router->get("/genres", "Genres.cloud");
$router->get("/films", "Home.listing");

//routes home
$router->get("/", "Home.listing");

$router->run();

    function redirect($url, $tps)
    {
        $temps = $tps * 1000;

        echo "<script type=\"text/javascript\">

            function redirect() 
            { 
                window.location='" . $url . "' 
            } 
            
            setTimeout('redirect()','" . $temps ."');

        </script>\n";
    }


    function get_extension($nom)
    {
        $nom = explode(".", $nom);
        $nb = count($nom);
        return strtolower($nom[$nb-1]);
    }

    function renome_image($rep_img, $titre, $ext)
    {
        $titre = stripslashes($titre);
        $titre = rewrite_suggestion($titre);
        $titre = trim($titre);
        $titre = str_replace("***", "_", $titre);
        $titre = strtolower($titre);
        $accents = "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇcÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ$@"; 
        $ssaccents = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNnsa"; 
        $titre = strtr($titre,$accents,$ssaccents); 
        $speciaux = "?!,:°#(){}[]/|^*+=£%µ§¤~²' "; 
        $ssspeciaux = "____________________________"; 
        $titre = strtr($titre,$speciaux,$ssspeciaux);
        $titre = str_replace(" ", "_", $titre);
        $titre = str_replace("S", "oe", $titre);
        $titre = str_replace("___", "_", $titre);
        $titre = str_replace("__", "_", $titre);
        $titre = str_replace("_.". $ext ."", ".". $ext ."", $titre);
        $url = "". $rep_img."/". $titre .".". $ext ."";
        $url = str_replace("_.". $ext ."", ".". $ext ."", $url);

        return($url);
    }

    function rewrite_suggestion($variable)
    {
        $variable = trim($variable);
        $variable = stripslashes($variable);
        $variable = str_replace("***","'",$variable);
        $variable = strtolower($variable); 
        $variable = $variable;
        $accents = "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ"; 
        $ssaccents = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn"; 
        $variable = strtr($variable,$accents,$ssaccents); 
        $speciaux = "?!,:°#(){}[]/|^*+_=£%µ§¤~²\"\$";
        $ssspeciaux = "........................... s";
        $variable = strtr($variable,$speciaux,$ssspeciaux); 
        $variable = str_replace("S", "oe", $variable);
        $variable = str_replace("",".",$variable);
        $variable = str_replace("'",".",$variable);
        $variable = str_replace("@","a",$variable);
        $variable = str_replace("...",".",$variable);
        $variable = str_replace("..",".",$variable);
        $variable = str_replace("  "," ",$variable);
        $variable = trim($variable);

        return($variable);
    }

    function redimentionne_image($rep_img, $url)
    {
        $img_screen1 = "250";
        $size = @getimagesize($url);

        $filename = substr(strrchr($url, '/'), 1 ); 
        $ext = get_extension($url);

        if (strstr($ext, "jpg") || strstr($ext, "jpeg")) $src = @imagecreatefromjpeg($url);
        if (strstr($ext, "png")) $src = @imagecreatefrompng($url);
        if (strstr($ext, "gif")) $src = @imagecreatefromgif($url);

        $img = @imagecreatetruecolor($img_screen1, round(($img_screen1/$size['0'])*$size['1']));
        if (!$img) $img = @imagecreate($img_screen1, round(($img_screen1/$size['0'])*$size['1']));

        if (strstr($ext, "png"))
        {
            imagealphablending($img, false);
            imagesavealpha($img, true);

            $trans_layer_overlay = imagecolorallocatealpha($img, 220, 220, 220, 127);
            imagefill($img, 0, 0, $trans_layer_overlay);
        }

        @imagecopyresampled($img, $src, 0, 0, 0, 0, $img_screen1, round($size['1']*($img_screen1/$size['0'])), $size['0'], $size['1']);

        $miniature = "$rep_img/$filename";

        if (strstr($ext, "jpg") || strstr($ext, "jpeg")) @ImageJPEG($img, $miniature);
        if (strstr($ext, "png"))  @ImagePNG($img, $miniature);
        if (strstr($ext, "gif")) @ImageGIF($img, $miniature);

        return($url);
    }
