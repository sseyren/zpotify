<?php
	if ( !isset($index_anahtar) ){
		header("Location:/");
	}else{
		$sayfa_limit = 15;
		$ss = $b;
		$b = ($b-1)*$sayfa_limit;

		$sth = $db -> prepare("SELECT * FROM icerik ORDER BY tarih DESC LIMIT ?,?");
		$sth -> bindValue(1, $b, PDO::PARAM_INT);
		$sth -> bindValue(2, $sayfa_limit, PDO::PARAM_INT);
		$sth -> execute();
		$sec = $sth -> fetchAll();

		$sth = $db -> prepare("SELECT COUNT(ID) AS sayi FROM icerik");
		$sth -> execute();
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
					$sonuc .= '<div class="sol"><span><a href="/t/'.($ss-1).'"><i class="fa fa-arrow-left" aria-hidden="true"></i>Önceki</a></span></div>';
				}else{
					$sonuc .= '<div class="sol"><span style="color:#999999"><i class="fa fa-arrow-left" aria-hidden="true" style="color:#999999"></i>Önceki</span></div>';
				}
				$sonuc .= '<div class="orta"><span>Sayfa '.$ss.'</span></div>';
				if ( $ss != $topsayfa ){
					$sonuc .= '<div class="sag"><span><a href="/t/'.($ss+1).'">Sonraki<i class="fa fa-arrow-right" aria-hidden="true"></i></a></span></div>';
				}else{
					$sonuc .= '<div class="sag"><span style="color:#999999">Sonraki<i class="fa fa-arrow-right" aria-hidden="true" style="color:#999999"></i></span></div>';
				}
			//}
		}else{
			$sonuc .= '<div class="element son">
					<div class="orta"><span>Yok pek bişi.</span></div>
				</div>';
		}
		$sth = $db -> prepare("SELECT COUNT(ID) AS kullanici FROM kullanici");
		$sth -> execute();
		$kullanici = $sth -> fetch();
	}
?>
	<div id="icerik" class="ortala">
		<div class="liste" id="arama-sonuc"><?php echo $sonuc;?></div>
		<p style="text-align:center;font-size:20px;margin-top:20px">Şu ana kadar <?php echo $kullanici["kullanici"]?> kullanıcı tarafından <?php echo $sayi["sayi"]?> adet içerik yüklenmiş!</p>
	</div>