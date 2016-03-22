<?	/* Récupération de la liste des répertoires */
        $dir_handle = opendir('.');
        $tab_dirs = array();
        while(false !== ($dir = readdir($dir_handle))) 
                if (is_dir($dir) && $dir  != '.' && $dir != '..')
                        $tab_dirs[] = $dir;
        closedir($dir_handle);
	sort($tab_dirs);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
<meta name="viewport" content="width=device-width"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="player.css" type="text/css" rel="stylesheet" />
<title>Dewplayer</title>

</head>

<body>
<? foreach($tab_dirs as $dir) { ?>
	<a href="<?=$dir?>" class="bouton"><?=$dir?></a ><br \>
<? } ?>
</body>
</html>



