<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title>PasteBin</title>
	<script src="http://www.woow-fr.com/stats/stats.php?id=E00012" type="text/javascript"></script>
	<script type="text/javascript" charset="utf-8" src="js/prototype.js"></script>	
	<script type="text/javascript" charset="utf-8" src="js/effects.js"></script>
	<script type="text/javascript" charset="utf-8" src="js/xmlhttp.js"></script>
	<script type="text/javascript" charset="utf-8" src="js/actions.js"></script>
	<link rel="shortcut icon" href="img/favicon.ico" />
	<link rel="icon" href="img/favicon.gif" type="image/gif" />
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<style type="text/css" id="ParsedStyle">
	</style>
	<?php
	if (!isset($_GET['id'])) {
		$me=explode('?',$_SERVER['REQUEST_URI']);
		$me=$me[1];
		if (strlen(trim($me)))
			$_GET['id']=$me;
	}
	
	if (isset($_GET['id'])) {
		echo '<script type="text/javascript">window.onload = function() { SendID(\''.$_GET['id'].'\'); };</script>';
	}
	?>
</head>
<body>
	<form action="" method="post" onsubmit="return false;">
	<div id="menu">
		<a href="javascript:pasteCode();" class="linkCode" id="pasteCode" title="Coller du code"><img src="img/emblem-note.gif" title="Coller du code" alt="Coller du code" /><span>Coller du code</span></a>
		<a href="javascript:showCode();" class="linkCode" id="showCode" title="Afficher du code"><img src="img/emblem-cvs-sticky-tag.gif" title="Afficher du code" alt="Afficher du code" /><span>Afficher le code n&deg;</span><input type="text" name="numcode" id="numcode" value="" /></a>		
	</div>
	<a id="wikistuce" href="http://www.wikistuce.info">wikistuce - astuces, classes et fonctions pour webmaster</a>
	<div style="display:none;" id="Load"><div>Chargement...</div></div>
	<div style="display:none;" id="SubmitCodeBorder">
		<div id="SubmitCode">
			<a href="javascript:CloseSource();" title="Fermer" id="closeSource"><img src="img/emblem-noreads.gif" alt="Fermer" title="Fermer" /></a>
			
				<h3>Code source :</h3>
				<textarea id="source" rows="6" cols="6" name="source"></textarea>
				<h3>Langage :</h3>
				<select id="language" name="language">
				<?php
				$dir = opendir('geshi/geshi/');
				$languages = array();
				while ( $file = readdir($dir) ) {
					if ( $file == '..' || $file == '.' || !stristr($file, '.') || $file == 'css-gen.cfg' ) continue;
					$lang = substr($file, 0,  strpos($file, '.'));
				    $languages[] = $lang;
				}
				closedir($dir);
				sort($languages);
				foreach ($languages as $lang) {
				    echo '<option '.(($lang=='php')?' selected="selected" ':'').'value="' . $lang . '">' . $lang . "</option>\n";
				}

				?>
				</select>
				<br />
				<input onclick="SendSource();" type="submit" id="submit" name="submit" value="Ok" />
			
		</div>
	</div>
	<div id="ParsedCode">
	&nbsp;
	</div>
	</form>

</body>
</html>