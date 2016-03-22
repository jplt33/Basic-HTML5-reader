<?php

function makeUL($dir, $decalage = '') {
	global $url;

	echo "{$decalage}Ouverture de {$dir}\n";
	$dir_handle = opendir($dir);

	$tab_files = array();
	while(false !== ($file = readdir($dir_handle))) {
		echo "{$decalage}Analyse {$file} : ";
		if (is_dir($file) && $file != '.' && $file != '..') {
			echo "{$file} est un repertoire => on relance makeUL\n";
			makeUL($dir.'/'.$file, $decalage."   ");
		} else if (!preg_match("`.mp3`i", $file)) {
			echo "Pas mp3 => on saute\n";
		} else {
			$tab_files[] = $file;
		}
	}

	// Si on a trouvé des fichiers mp3, on créé la page du player
	if (count($tab_files)) {
		$index = $dir.'/index.php';

		// On trie les musiques pour les avoir dans l'ordre
		sort($tab_files);

		echo "{$decalage}Creation du fichier index.php\n";

		if (!$handle = fopen($index, "w")) {
			echo "{$decalage}Impossible de creer le fichier ($index)\n";
			exit;
		}

		// On récupère le 1er fichier mp3 de la liste
		$first_file = array_shift($tab_files);

		$debut_lecteur = '<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width"/>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="robots" content="noindex, nofollow">
<meta name="googlebot" content="noindex, nofollow">
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.js"></script>
<link href="../playlist.css" type="text/css" rel="stylesheet"></link>
<style type="text/css">
#playlist,audio{background:#666;width:400px;padding:20px;}
.active a{color:#5DB0E6;text-decoration:none;}
li a{color:#eeeedd;background:#333;padding:5px;display:block;}
li a:hover{text-decoration:none;}
</style>
<title>Playlist</title>

<script type="text/javascript">//<![CDATA[
$(window).load(function(){
var audio;
var playlist;
var tracks;
var current;

init();
function init(){
    current = 0;
    audio = $("audio");
    playlist = $("#playlist");
    tracks = playlist.find("li a");
    len = tracks.length - 1;
    audio[0].volume = .10;
    playlist.find("a").click(function(e){
        e.preventDefault();
        link = $(this);
        current = link.parent().index();
        run(link, audio[0]);
    });
    audio[0].addEventListener("ended",function(e){
        current++;
        if(current == len){
            current = 0;
            link = playlist.find("a")[0];
        }else{
            link = playlist.find("a")[current];
        }
        run($(link),audio[0]);
    });
}
function run(link, player){
        player.src = link.attr("href");
        par = link.parent();
        par.addClass("active").siblings().removeClass("active");
        audio[0].load();
        audio[0].play();
}
});//]]>

</script>
</head>

<body>
<a href=".." class="bouton"><< Retour</a><br \>
<audio id="audio" preload="auto" tabindex="0" controls="" type="audio/mpeg">
	<source type="audio/mp3" src="'.$first_file.'">
	Sorry, your browser does not support HTML5 audio.
</audio>
<ul id="playlist">
';
		fwrite($handle, $debut_lecteur);
		$first_mp3 = '<li class="active"><a href="'.$first_file.'">'.$first_file.'</a></li>
';
		fwrite($handle, $first_mp3);

		foreach($tab_files as $file) {
			$mp3 = '<li><a href="'.$file.'">'.$file.'</a></li>
';
			fwrite($handle, $mp3);
		}

		$last_mp3 = '<li class="active"><a href=""></a></li>
';
		fwrite($handle, $last_mp3);


		$fin_lecteur = '</ul>
</body>
</html>';

		fwrite($handle, $fin_lecteur);

		echo "{$decalage}Fermeture du fichier index.php\n";
		fclose($handle);
	}

	echo "{$decalage}Fermeture du repertoire {$dir}\n";
	closedir($dir_handle);
}

makeUL('.');
?> 
