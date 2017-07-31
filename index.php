<?php
	if($_SERVER["HTTPS"] != "on"){
	    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
	    exit();
	}
	require "config.php";
	//$girdi = $_GET["girdi"];
	//list($a,$b) = explode("/", $girdi, 2);
	function temizle($oge){
		$oge = preg_replace('/[^A-Za-z0-9şıöüğçİŞÖĞÜÇ ]/s', '', $oge);
		$oge = trim(preg_replace('/\s+/','',$oge));
		$oge = strip_tags($oge);
		return $oge;
	}
	// Google recaptcha için...
	function curlKullan($url) {
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
	    $curlData = curl_exec($curl);
	    curl_close($curl);
	    return $curlData;
	}
	function anahtar() {
	    $SecretKey = "b19a2192022d905d56801b389d40f05e";
	    return md5(sha1(md5($_SERVER['REMOTE_ADDR'] . $SecretKey . $_SERVER['HTTP_USER_AGENT'])));
	}
	function boyut($dosya,$birim){
		if ( $birim == "KB" ){
			$boyut = filesize($dosya)/1024;
		}elseif( $birim == "MB" ){
			$boyut = filesize($dosya)/1048576;
		}elseif ( $birim == "GB" ){
			$boyut = filesize($dosya)/1073741824;
		}
		$boyut = round($boyut,1);
		return $boyut.$birim;
	}
	if( empty($_GET["a"]) ){
		$a = "anasayfa";
	}else{
		$a = $_GET["a"];
	}
	$b = temizle($_GET["b"]);
	//echo $a." - ".$b;
	if ( $_SESSION["oturum"] == true ){
		if ( anahtar() != $_SESSION["anahtar"] ){
			session_destroy();
			$a = "giris";
		}
	}
	$index_anahtar = true;
	if ( $_SESSION["oturum"] == true ){
		$sth = $db -> prepare("SELECT ban,ban_sebep FROM kullanici WHERE ka=?");
		$sth -> execute(array($_SESSION["ka"]));
		$ban = $sth -> fetch();
		if ( $ban["ban"] == "var" ){
			$a = "ban";
		}
	}
	switch ($a) {
	case 'anasayfa':
		if ( $b == "kk" ){
			$baslik = "Kullanım Koşulları | Zpotify";
		}else{
			$baslik = "Ana Sayfa | Zpotify";
		}
		require "head.php";
		require "ust.php";
		if ( $b == "kk" ){
			require "kk.php";
		}else{
			require "anasayfa.php";
			require "alt.php";
		}
		echo "</body></html>";
		break;
	case 'i':
		$sth = $db -> prepare("SELECT * FROM icerik WHERE kimlik=?");
		$sth -> execute(array($b));
		$islem = $sth -> fetch();
		if ( !$islem ){
			header("Location:/404");
		}else{
			$baslik = $islem["sanatci"]." - ".$islem["parca"]." | Zpotify";
			require "head.php";
			require "ust.php";
			require "icerik.php";
			require "alt.php";
			echo "</body></html>";
		}
		break;
	case 't':
		$sth = $db -> prepare("SELECT COUNT(ID) AS sayi FROM icerik");
		$sth -> execute();
		$sayi = $sth -> fetch();
		$sayfa_limit = 15;
		$topsayfa = ceil(intval($sayi["sayi"])/$sayfa_limit);
		$b = intval($b);
		if ( is_int($b) && $b > 0 ){
			if ( $b > $topsayfa ){
				$b = $topsayfa;
			}
		}else{
			$b = 1;
		}
		$baslik = "Sayfa ".$b." - Tüm İçerik | Zpotify";
		require "head.php";
		require "ust.php";
		require "tum.php";
		require "alt.php";
		echo "</body></html>";
		break;
	case 'duzenle':
		if ( $_SESSION["oturum"] == true ){
			if ( empty($b) ){
				header("Location:/404");
			}else{
				$sth = $db -> prepare("SELECT * FROM icerik WHERE kimlik = ?");
				$sth -> execute(array($b));
				$islem = $sth -> fetch();
				if ( $islem ){
					$baslik = $islem["sanatci"]." - ".$islem["parca"]." | Zpotify";
					require "head.php";
					require "ust.php";
					require "duzenle.php";
					require "alt.php";
					echo "</body></html>";
				}else{
					header("Location:/404");
				}
			}
		}else{
			header("Location:/giris");
		}
		break;
	case 'p':
		if ( empty($b) ){
			if ( $_SESSION["oturum"] == true ){
				$b = $_SESSION["ka"];
			}else{
				header("Location:/404");
			}
		}
		$sth = $db -> prepare("SELECT * FROM kullanici WHERE ka=?");
		$sth -> execute(array($b));
		$islem = $sth -> fetch();
		if ( !$islem ){
			header("Location:/404");
		}else{
			$baslik = $islem["ka"]." | Zpotify";
			require "head.php";
			require "ust.php";
			require "profil.php";
			require "alt.php";
			echo "</body></html>";
		}
		break;
	case 'yukle':
		if ( $_SESSION["oturum"] == true ){
			$sth = $db -> prepare("SELECT * FROM kullanici WHERE ka=?");
			$sth -> execute(array($_SESSION["ka"]));
			$islem = $sth -> fetch();
			$baslik = "Yükle | Zpotify";
			require "head.php";
			require "ust.php";
			require "yukle.php";
			require "alt.php";
			echo "</body></html>";
		}else{
			header("Location:/giris");
		}
		break;
	case 'f':
		if ( empty($b) ){
			if ( $_SESSION["oturum"] == true ){
				$b = $_SESSION["ka"];
			}else{
				header("Location:/404");
			}
		}
		$sth = $db -> prepare("SELECT * FROM kullanici WHERE ka=?");
		$sth -> execute(array($b));
		$islem = $sth -> fetch();
		if ( !$islem ){
			header("Location:/404");
		}else{
			$baslik = $islem["ka"]." | Zpotify";
			require "head.php";
			require "ust.php";
			require "favori_liste.php";
			require "alt.php";
			echo "</body></html>";
		}
		break;
	case 'giris':
		if ( $_SESSION["oturum"] == true ){
			header("Location:/");
		}else{
			$baslik = "Giriş | Zpotify";
			require "head.php";
			require "giris.php";
			echo "</body></html>";
		}
		break;
	case 'kayit':
		if ( $_SESSION["oturum"] == true ){
			header("Location:/");
		}else{
			$baslik = "Kayıt | Zpotify";
			require "head.php";
			require "kayit.php";
			echo "</body></html>";
		}
		break;
	case 'cikis':
		session_destroy();
		header("Location:/");
		break;
	case 'ban':
		if ( $_SESSION["oturum"] == true && $ban["ban"] == "var" ){
			session_destroy();
			require "head.php";
			require "ust.php";
			echo "<h1 style='text-align:center'>Banlandınız!</h1><p style='text-align:center'>Ban sebebi: ".$ban["ban_sebep"]."</p>";
			require "alt.php";
		}else{
			header("Location:/");
		}
		break;
	default:
		$baslik = "404 | Zpotify";
		require "head.php";
		require "ust.php";
		require "404.php";
		require "alt.php";
		echo "</body></html>";
		break;
	}
?>