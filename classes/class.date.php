<?php
class dateOp {	
	function dateOp($dat,$format="jj/mm/aaaa hh:ii:ss") {
		$this->errno = array();
		
		if (strlen($dat)!=strlen($format)) {
			$this->_error("Format de date incompatible avec la date fournie");
			return false;
		}
	
		$this->dat['origine']=$dat;
		$this->format=strtolower($format);
		return $this->_ExplodeDate($this->dat,$this->format);
	}
	
	function AjouteJours($nb) {
		$this->dat['jj']+=floatval($nb);
		return true;
	}
	function AjouteMois($nb) {
		$this->dat['mm']+=floatval($nb);
		return true;
	}
	function AjouteAnnees($nb) {
		$this->dat['aaaa']+=floatval($nb);
		return true;
	}
	function AjouteHeures($nb) {
		$this->dat['hh']+=floatval($nb);
		return true;
	}
	function AjouteMinutes($nb) {
		$this->dat['ii']+=floatval($nb);
		return true;
	}
	function AjouteSecondes($nb) {
		$this->dat['ss']+=floatval($nb);
		return true;
	}
	function DiffenrenceEntreDate($dat,$format="jj/mm/aaaa hh:ii:ss") {
		if (strlen($dat)!=strlen($format)) {
			$this->_error("Format de date incompatible avec la date fournie");
			return false;
		}
		$this->dat2['origine']=$dat;
		$this->format2=strtolower($format);
		$this->_ExplodeDate($this->dat2,$this->format2);
		$d1=mktime($this->dat['hh'],$this->dat['mm'],$this->dat['ss'],$this->dat['mm'],$this->dat['jj'],$this->dat['aaaa']);
		$d2=mktime($this->dat2['hh'],$this->dat2['mm'],$this->dat2['ss'],$this->dat2['mm'],$this->dat2['jj'],$this->dat2['aaaa']);
		
		if ($d2>$d1)
			$d=$d2-$d1;
		else
			$d=$d1-$d2;
		
		return array("ans"=>date('Y',$d)-1970,"mois"=>date('m',$d)-1,"jours"=>date('d',$d)-1,"joursTotal"=>$d/60/60/24);
	}
	
	function GetDate($format="jj/mm/aaaa") {
		$format=str_replace(array('jj','j','m','nn','aaaa','aa','hh','h','ii','ss'),array('d','D','n','m','Y','y','H','G','i','s'),$format);
		return date($format,mktime($this->dat['hh'],$this->dat['ii'],$this->dat['ss'],$this->dat['mm'],$this->dat['jj'],$this->dat['aaaa']));
	}
 
	function _ExplodeDate(&$dat,$format) {
		$j[0]=2;
		if (($j[1]=strpos($format,'jj'))===false) {
			$j[0]=1;
			if (($j[1]=strpos($format,'j'))===false)
				$this->_error($format." : Les jours n'ont pas été trouvés... Les jours doivent être précisés par 'jj' ou par 'j' (ex: jj/mm/aaaa)");	
		}
		$m[0]=2;
		if (($m[1]=strpos($format,'mm'))===false)
			$m[0]=1;
			if (($m[1]=strpos($format,'m'))===false)
				$this->_error($format." : Les mois n'ont pas été trouvés... Les mois doivent être précisés par 'mm' ou par 'm' (ex: jj/mm/aaaa)");
		$a[0]=4;
		if (($a[1]=strpos($format,'aaaa'))===false) {
			//cherche pour un aa au lieu de aaaa
			$a[0]=2;
			if (($a[1]=strpos($format,'aa'))===false)
				$this->_error($format." : Les années n'ont pas été trouvés... Les années doivent être précisés par 'aaaa' ou par 'aa' (ex: jj/mm/aaaa)");
		}
		$h[0]=2;
		if (($h[1]=strpos($format,'hh'))===false)
			$h[0]=1;
			if (($h[1]=strpos($format,'h'))===false)
				$this->_error($format." : Les heures n'ont pas été trouvées... Les heures doivent être précisées par 'hh' ou 'h' (ex: jj/mm/aaaa hh:ii:ss)");
		$i[0]=2;
		if (($i[1]=strpos($format,'ii'))===false)
			$i[0]=1;
			if (($i[1]=strpos($format,'i'))===false)
				$this->_error($format." : Les minutes n'ont pas été trouvées... Les minutes doivent être précisées par 'ii' ou 'i' (ex: jj/mm/aaaa hh:ii:ss)");
		$s[0]=2;
		if (($s[1]=strpos($format,'ss'))===false)
			$s[0]=1;
			if (($s[1]=strpos($format,'s'))===false)
				$this->_error($format." : Les secondes n'ont pas été trouvées... Les secondes doivent être précisés par 'ss' ou 's' (ex: jj/mm/aaaa hh:ii:ss)");
		$dat['jj']	=($j[1]!==false)?floatval(substr($dat['origine'],$j[1],$j[0])):1;
		$dat['mm']	=($m[1]!==false)?floatval(substr($dat['origine'],$m[1],$m[0])):1;
		$dat['aaaa']	=($a[1]!==false)?floatval(substr($dat['origine'],$a[1],$a[0])):1970;
		if ($a[0]==2)
			$dat['aaaa']=floatval(substr(date('Y'),0,2).$dat['aaaa']);
		$dat['hh']	=($h[1]!==false)?floatval(substr($dat['origine'],$h[1],$h[0])):0;
		$dat['ii']	=($i[1]!==false)?floatval(substr($dat['origine'],$i[1],$i[0])):0;
		$dat['ss']	=($s[1]!==false)?floatval(substr($dat['origine'],$s[1],$s[0])):0;
		return true;
	}
	function _error($str) {
		$this->errno[]=$str;
		return true;
	}
}
?>