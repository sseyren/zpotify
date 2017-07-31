<?php
	if ( !isset($index_anahtar) ){
		header("Location:/");
	}else{
		$sth = $db -> prepare("SELECT * FROM icerik ORDER BY tarih DESC LIMIT 0,10");
		$sth -> execute();
		$islem = $sth ->fetchAll();
		$ey = "";
		if ( $islem ){
			foreach ($islem as $cunos) {
				$ey .= '<a href="/i/'.$cunos["kimlik"].'"><div class="element">
					<div class="sol"><span><i class="fa fa-play-circle" aria-hidden="true"></i>'.$cunos["sanatci"].' - '.$cunos["parca"].'</span></div>
					<div class="sag"><span>'.$cunos["uzunluk"].'</span></div>
					<div class="sag"><span>'.boyut("./icerik/".$cunos["kimlik"].".".$cunos["uzanti"],"MB").'</span></div>
				</div></a>';
			}
			$ey .= '<a href="/t"><div class="element son">
					<div class="orta"><span><i class="fa fa-arrow-down" aria-hidden="true"></i>Daha fazlası için tıklayın.<i class="fa fa-arrow-down" aria-hidden="true"></i></span></div>
				</div></a>';
		}else{
			$sonuc .= '<div class="element son">
				<div class="orta"><span>Henüz hiçbir şey yüklenmemiş :(</span></div>
			</div>';
		}
	}
?>
	<div id="icerik" class="ortala">
		<h1 id="slogan">Hiçbir zaman hiçbir yerde!</h1>
		<div id="arama-yeri">
			<input id="arama" class="arama-cubugu" type="text" onkeyup="ara(true);" placeholder="Ne dinlemek istersin?" autofocus>
		</div>
		<div class="liste" id="arama-sonuc" style="display:none"></div>
		<div class="liste" id="en-yeniler">
			<div class="element"><h2>En Yeniler</h2></div>
			<?php echo $ey; ?>
		</div>
	</div>
	<script>
		var sayfa = 1;
		function sayfaf(arg){
			if ( arg == "arttir" ){
				sayfa++;
 			}else if( arg == "azalt" && sayfa > 1 ){
 				sayfa--;
			}
			ara();
		}
		function ara(sifirlama){
			if ( sifirlama == true ){sayfa = 1;}
			if ( document.getElementById("arama").value.length == 0 ){
				document.getElementById("arama-sonuc").innerHTML = "";
				document.getElementById("arama-sonuc").style.display = "none";
			}else{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						document.getElementById("arama-sonuc").style.display = "";
						document.getElementById("arama-sonuc").innerHTML = this.responseText;
					}
				};
				xmlhttp.open("GET", "/arama.php?q="+document.getElementById("arama").value+"&s="+sayfa , true);
				xmlhttp.send();
			}
		}
	</script>