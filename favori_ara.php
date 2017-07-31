<?php
	require "config.php";
	function temizle($oge){
		$oge = preg_replace('/[^A-Za-z0-9şıöüğçİŞÖĞÜÇ ]/s', '', $oge);
		$oge = trim(preg_replace('/\s+/','',$oge));
		$oge = strip_tags($oge);
		return $oge;
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
	$sayfa_limit = 10;
	$s = intval(temizle($_REQUEST["s"]));
	$u = temizle($_REQUEST["u"]);
	$k = temizle($_REQUEST["k"]);
	$ss = $s;
	$s = ($s-1)*$sayfa_limit;
	//echo $s." - ".$ss." - ".$sayfa_limit;
	if ( $ss >= 1 ){
		$sth = $db -> prepare("SELECT * FROM favori WHERE ka = ? ORDER BY tarih DESC LIMIT ?,?");
		$sth -> bindValue(1, $u);
		$sth -> bindValue(2, $s, PDO::PARAM_INT);
		$sth -> bindValue(3, $sayfa_limit, PDO::PARAM_INT);
		$sth -> execute();
		$favori = $sth -> fetchAll();

		$sth = $db -> prepare("SELECT COUNT(ID) AS sayi FROM favori WHERE ka = ?");
		$sth -> execute(array($u));
		$sayi = $sth -> fetch();

		$sonuc = '<div class="element"><h2>Favoriler</h2></div>';
		if ( $favori ){
			foreach ($favori as $ic) {
				$sth = $db -> prepare("SELECT * FROM icerik WHERE kimlik = ?");
				$sth -> execute(array($ic["kimlik"]));
				$cunos = $sth -> fetch();
				$sonuc .= '<a href="/i/'.$cunos["kimlik"].'"><div class="element">
				<div class="sol"><span><i class="fa fa-play-circle" aria-hidden="true"></i>'.$cunos["sanatci"].' - '.$cunos["parca"].'</span></div>
				<div class="sag"><span>'.$cunos["uzunluk"].'</span></div>
				<div class="sag"><span>'.boyut("./icerik/".$cunos["kimlik"].".".$cunos["uzanti"],"MB").'</span></div>
			</div></a>';
			}
			$topsayfa = ceil(intval($sayi["sayi"])/$sayfa_limit);
			$sonuc .= '<div class="element son">';
			if ( $ss != 1 ){
				$sonuc .= '<div class="sol"><span style="cursor:pointer" onclick="sayfaf(\'azalt\')"><i class="fa fa-arrow-left" aria-hidden="true"></i>Önceki</span></div>';
			}else{
				$sonuc .= '<div class="sol"><span style="color:#999999"><i class="fa fa-arrow-left" aria-hidden="true" style="color:#999999"></i>Önceki</span></div>';
			}
			$sonuc .= '<div class="orta"><span>Sayfa '.$ss.'</span></div>';
			if ( $ss != $topsayfa ){
				$sonuc .= '<div class="sag"><span style="cursor:pointer" onclick="sayfaf(\'arttir\')">Sonraki<i class="fa fa-arrow-right" aria-hidden="true"></i></span></div>';
			}else{
				$sonuc .= '<div class="sag"><span style="color:#999999">Sonraki<i class="fa fa-arrow-right" aria-hidden="true" style="color:#999999"></i></span></div>';
			}
		}else{
			$sonuc .= '<div class="element son">
				<div class="orta"><span>Yok pek bişi.</span></div>
			</div>';
		}
	}
	echo $sonuc;
?>