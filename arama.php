<?php
	require "config.php";
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
	$q = $_REQUEST["q"];
	$q = trim(preg_replace('/[^A-Za-z0-9şıöüğçİŞÖĞÜÇ ]/s', '', $q));
	$s = $_REQUEST["s"];
	$s = intval(trim(preg_replace('/[^A-Za-z0-9şıöüğçİŞÖĞÜÇ ]/s', '', $s)));
	$p = $_REQUEST["p"];
	$p = trim(preg_replace('/[^A-Za-z0-9şıöüğçİŞÖĞÜÇ ]/s', '', $p));
	$ss = $s;
	$s = ($s-1)*$sayfa_limit;
	$q = '%'.$q.'%';
	if ( !empty($q) or $q != " " or !empty($s) or $s > 0 ){
		if ( empty($p) ){
			$sth = $db -> prepare("SELECT * FROM icerik WHERE parca LIKE ? || sanatci LIKE ? || album LIKE ? ORDER BY tarih DESC LIMIT ?,?");
			$sth -> bindValue(1, $q);
			$sth -> bindValue(2, $q);
			$sth -> bindValue(3, $q);
			$sth -> bindValue(4, $s, PDO::PARAM_INT);
			$sth -> bindValue(5, $sayfa_limit, PDO::PARAM_INT);
		}else{
			$sth = $db -> prepare("SELECT * FROM icerik WHERE yukleyen=? && ( parca LIKE ? || sanatci LIKE ? || album LIKE ? ) ORDER BY tarih DESC LIMIT ?,?");
			$sth -> bindValue(1, $p);
			$sth -> bindValue(2, $q);
			$sth -> bindValue(3, $q);
			$sth -> bindValue(4, $q);
			$sth -> bindValue(5, $s, PDO::PARAM_INT);
			$sth -> bindValue(6, $sayfa_limit, PDO::PARAM_INT);
		}
		$sth -> execute();
		$sec = $sth -> fetchAll();
		
		if ( empty($p) ){
			$sth = $db -> prepare("SELECT COUNT(ID) AS sayi FROM icerik WHERE parca LIKE ? || sanatci LIKE ? || album LIKE ?");
			$sth -> execute(array($q,$q,$q));
		}else{
			$sth = $db -> prepare("SELECT COUNT(ID) AS sayi FROM icerik WHERE yukleyen=? && ( parca LIKE ? || sanatci LIKE ? || album LIKE ? )");
			$sth -> execute(array($p,$q,$q,$q));
		}
		$sayi = $sth -> fetch();
		
		$sonuc = '<div class="element"><h2>Sonuçlar</h2></div>';
		if ( $sec ){
			foreach ($sec as $cunos) {
				$sonuc .= '<a href="/i/'.$cunos["kimlik"].'"><div class="element">
				<div class="sol"><span><i class="fa fa-play-circle" aria-hidden="true"></i>'.$cunos["sanatci"].' - '.$cunos["parca"].'</span></div>
				<div class="sag"><span>'.$cunos["uzunluk"].'</span></div>
				<div class="sag"><span>'.boyut("./icerik/".$cunos["kimlik"].".".$cunos["uzanti"],"MB").'</span></div>
			</div></a>';
			}
			//$onceki = $sonraki = true;
			$topsayfa = ceil(intval($sayi["sayi"])/$sayfa_limit);
			/*if ( $ss == 1 ){ $onceki = false; }
			if ( $ss == $topsayfa ){ $sonraki = false; }*/
			//if ( $onceki != false or $sonraki != false ){
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
			//}
		}else{
			$sonuc .= '<div class="element son">
				<div class="orta"><span>Yok pek bişi.</span></div>
			</div>';
		}
	}
	echo $sonuc;
?>