<?php
	require "config.php";
	function temizle($oge){
		$oge = preg_replace('/[^A-Za-z0-9şıöüğçİŞÖĞÜÇ ]/s', '', $oge);
		$oge = trim(preg_replace('/\s+/','',$oge));
		$oge = strip_tags($oge);
		return $oge;
	}
	if ( $_SESSION["oturum"] == true ){
		$f = temizle($_REQUEST["f"]);
		$sth = $db -> prepare("SELECT * FROM favori WHERE ka = ? && kimlik = ?");
		$sth -> execute(array($_SESSION["ka"],$f));
		$favdb = $sth -> fetch();
		if ( $favdb ){
			$sth = $db -> prepare("DELETE FROM favori WHERE ID = ?");
			$sth -> execute(array($favdb["ID"]));
			echo "false";
		}else{
			$sth = $db -> prepare("INSERT INTO favori (ka,kimlik,tarih) VALUES (?,?,?)");
			$sth -> execute(array($_SESSION["ka"],$f,time()));
			echo "true";
		}
	}else{
		echo "giris";
	}
?>