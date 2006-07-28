<?php
class BDD {
	/*CLASSE D'ACCESS A UNE BASE DE DONNEE*/
	/* http://creativecommons.org/licenses/by-nc-sa/2.0/ */
 
	/*
	Initialisation de la classe
	Connexion � la base
	-------------------------------------------------------
	$user	-> utilisateur
	$pass	-> mot de passe
	$db		-> base de donn�e (facultatif, vide par defaut)
	$server	-> server (facultatif, localhost par defaut)
	$type	-> type de base (oracle ou mysql) (facultatif, mysql par defaut)
	-------------------------------------------------------
	retourne	false en cas d'echec
			true en cas de r�ussite
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
	retourne	une chaine string de la derniere erreur trouv�e
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
	Connexion � la base de donn�es
	-------------------------------------------------------
	retourne	false en cas d'echec
			true en cas de r�ussite
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
	D�connexion de la base de donn�es
	-------------------------------------------------------
	retourne	false en cas d'echec
			true en cas de r�ussite
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
	Execution d'une requ�te
	-------------------------------------------------------
	retourne	false en cas d'echec
			handle de requ�te en cas de r�ussite
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
	Place le curseur a la ligne suivante dans la requ�te
	-------------------------------------------------------
	$result_query	-> handle de requ�te (facultatif, null par defaut)
	Si $result_query est homis, le dernier handle de requ�te cr�er sera utlis�
	-------------------------------------------------------
	retourne	false en cas d'echec
			true en cas de r�ussite
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