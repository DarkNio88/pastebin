<?php
header ('Content-Type: text/html; charset=utf-8');
include 'geshi/geshi.php';
include 'classes/class.bdd.php';
include 'classes/class.date.php';
function getfullURL() {
	return 'http'.(($_SERVER['HTTPS']=='on')?'s':'').'://'.$_SERVER['HTTP_HOST'].(($_SERVER['SERVER_PORT']!='80')?':'.$_SERVER['SERVER_PORT']:'').$_SERVER['REQUEST_URI'];
}

$sql=new BDD("root","","test","localhost","mysql");

$sql->CreateTable('pastebin_data','`p_idx` int(11) NOT NULL auto_increment,  
                    `p_titre` varchar(255) default NULL,      
                    `p_who` varchar(255) default NULL,       
                    `p_date` varchar(14) default NULL, 
					`p_lang` varchar(255) default NULL,       
                    `p_data` LONGTEXT default NULL,
                    PRIMARY KEY  (`p_idx`)'); 
	
if (isset($_POST['id'])) {
	if (!is_numeric($_POST['id'])) {
		echo "Erreur, veuillez saisir un identifiant numérique";
		exit;
	}
	$sql->Query("select p_titre,p_who,p_date,p_lang,p_data from pastebin_data where p_idx=".$_POST['id']);
	$data=$sql->FetchCurrentRow();
	if ($data['p_data']!='') {
		$source=unserialize(gzuncompress(($data['p_data'])));
		$language=$data['p_lang'];
		$dte=new dateOp($data['p_date'],'aaaammjj');
		$dte=$dte->GetDate('jj/mm/aaaa');
	} else {
		$source='//Ce code source n\'existe pas !';
		$language='php';
		$dte=date("d/m/Y");
	}
} elseif (isset($_POST['source'])) {
	$source=utf8_encode(rawurldecode($_POST['source']));
	$language=rawurldecode($_POST['language']);
	if ( get_magic_quotes_gpc() ) $source = stripslashes($source);
	$dte=date("d/m/Y");
	$sql->Query("insert into pastebin_data (p_titre,p_who,p_date,p_lang,p_data) value ('','','".date('Ymd')."','".$language."',\"".addslashes(gzcompress(serialize($source)))."\")");
	$sql->Query("select p_idx from pastebin_data order by p_idx DESC limit 0,1");	
	$_POST['id']=$sql->FetchCurrentRow();
	$_POST['id']=$_POST['id']['p_idx'];
} else {
	echo "Erreur non identifiée";
	exit;
}

$geshi =& new GeSHi($source, $language);
$geshi->set_header_type(GESHI_HEADER_DIV);
$geshi->enable_classes();
$geshi->set_link_target("_blank");
$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 5);
//$geshi->set_overall_style('color: #000066; border: 1px solid #d0d0d0; background-color: #f0f0f0;', true);
//$geshi->set_line_style('font: normal normal 95% \'Courier New\', Courier, monospace; color: #003030;', 'font-weight: bold; color: #006060;', true);
//$geshi->set_code_style('color: #000020;', 'color: #000020;');
//$geshi->set_link_styles(GESHI_LINK, 'color: #000060;');
//$geshi->set_link_styles(GESHI_HOVER, 'background-color: #f0f000;');
$urle=str_replace(basename(__file__),'?'.$_POST['id'],getfullURL());
$geshi->set_header_content('id n'.utf8_encode('°').$_POST['id'].' en date du : '.$dte.' - URL externe : <a href="'.$urle.'" target="_blank" title="URL externe">'.$urle.'</a>');
//$geshi->set_header_content_style('font-family: Verdana, Arial, sans-serif; color: #808080; font-size: 70%; font-weight: bold; background-color: #f0f0ff; border-bottom: 1px solid #d0d0d0; padding: 2px;');
$geshi->set_footer_content('Pars&eacute; en <TIME> secondes,  avec GeSHi <VERSION>');
//$geshi->set_footer_content_style('font-family: Verdana, Arial, sans-serif; color: #808080; font-size: 70%; font-weight: bold; background-color: #f0f0ff; border-top: 1px solid #d0d0d0; padding: 2px;');

echo $geshi->get_stylesheet();
echo "ZQSGDFG65465TESRGSDFGSDFG";
echo $geshi->parse_code();
?>