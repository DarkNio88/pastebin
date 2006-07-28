<?php
class BDD {
	/*CLASSE D'ACCESS A UNE BASE DE DONNEE*/
	/* http://creativecommons.org/licenses/by-nc-sa/2.0/ */
 
	/*
	Initialisation de la classe
	Connexion à la base
	-------------------------------------------------------
	$user	-> utilisateur
	$pass	-> mot de passe
	$db		-> base de donnée (facultatif, vide par defaut)
	$server	-> server (facultatif, localhost par defaut)
	$type	-> type de base (oracle ou mysql) (facultatif, mysql par defaut)
	-------------------------------------------------------
	retourne	false en cas d'echec
			true en cas de réussite
	-------------------------------------------------------
	*/
	function BDD($user,$pass,$db="",$server="localhost",$type="mysql") {
		//$type = mysql ou oracle
		$this->USER		= $user;
		$this->PASS		= $pass;
		$this->DB		= $db;
		$this->SERVER	= $server;
		$this->TYPE		= strtolower($type);
		return $this->Connect();
	}
	/*
	Affichage des erreurs
	A appeller si une fonction renvoie false.
	-------------------------------------------------------
	retourne	une chaine string de la derniere erreur trouvée
	-------------------------------------------------------
	*/
	function errno() {
		if ($this->TYPE	== 'oracle') {
			$ERROR=oci_error(@$this->LINK);
			$ERROR=@$ERROR['message'];
		} elseif ($this->TYPE	== 'mysql')
			$ERROR=mysql_error(@$this->LINK);
		return $ERROR;
	}
	/*
	Connexion à la base de données
	-------------------------------------------------------
	retourne	false en cas d'echec
			true en cas de réussite
	-------------------------------------------------------
	*/	
	function Connect() {
		if ($this->TYPE	== 'oracle') {
			if (!($this->LINK = @ocilogon($this->USER,$this->PASS,$this->SERVER)))
				return false;
		} elseif ($this->TYPE	== 'mysql') {
			if (!($this->LINK = @mysql_connect($this->SERVER, $this->USER,$this->PASS)))
				return false;
			if (!@mysql_select_db($this->DB))
				return false;
		}
		return true;
	}
	/*
	Déconnexion de la base de données
	-------------------------------------------------------
	retourne	false en cas d'echec
			true en cas de réussite
	-------------------------------------------------------
	*/	
	function Deconnect() {
		if ($this->TYPE == 'oracle') {
			if (!@ocilogoff($this->LINK))
				return false;
		} elseif ($this->TYPE == 'mysql') {
			if (!@mysql_close($this->LINK))
				return false;
		}
		return true;
	}
	/*
	Execution d'une requête
	-------------------------------------------------------
	retourne	false en cas d'echec
			handle de requête en cas de réussite
	-------------------------------------------------------
	*/	
	function Query($sql) {
		if ($this->TYPE == 'oracle') {
			if (!($stmt = @OCIParse($this->LINK, $sql)))
				return false;
			if (!($r = @OCIExecute($stmt,OCI_DEFAULT)))
				return false;
			if (!($t=@oci_commit($this->LINK)))
				return false;
			$this->STMT=$stmt;
			return $stmt;
		}
		if ($this->TYPE== 'mysql') {
			if (!($result = @mysql_query($sql,$this->LINK)))
				return false;
			$this->STMT=$result;
			return $result;
		}
	}
	/*
	Place le curseur a la ligne suivante dans la requête
	-------------------------------------------------------
	$result_query	-> handle de requête (facultatif, null par defaut)
	Si $result_query est homis, le dernier handle de requête créer sera utlisé
	-------------------------------------------------------
	retourne	false en cas d'echec
			true en cas de réussite
	-------------------------------------------------------
	*/		
	function FetchCurrentRow($result_query=null) {
		if ($result_query==null)
			$result_query = $this->STMT;
		if ($this->TYPE == 'oracle')
			return @oci_fetch_array (@$result_query, OCI_BOTH);
		if ($this->TYPE == 'mysql')
			return @mysql_fetch_assoc(@$result_query);
	}
	
	function CreateTable($table,$content) {
        $Qr=$this->Query('CREATE TABLE IF NOT EXISTS '.$table.' ('.$content.');');
        return $Qr;
    }
}
?>