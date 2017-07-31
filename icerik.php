<?php
	if ( !isset($index_anahtar) ){
		header("Location:/");
		exit();
	}
	$sth = $db -> prepare("SELECT kimlik FROM icerik ORDER BY RAND() LIMIT 1");
	$sth -> execute(array());
	$rastgl = $sth -> fetch();
?>
	<script>
		if ( getCookie("rastgele") != "acik" && getCookie("rastgele") != "kapali" )
			setCookie("rastgele", "acik");
	</script>
	<div id="icerik" class="ortala" style="margin-bottom:20px">
		<div id="parca-kapak">
			<img src="<?php if($islem["kf"]!="yok"){echo "/kf/".$islem["kimlik"].".".$islem["kf"];}else{echo "/kf.png";} ?>">
		</div>
		<div id="parca-icerik">
			<div class="liste">
				<div class="element"><h2>Parça Bilgisi</h2></div>
				<div class="element">
					<div class="sol"><span><i class="fa fa-user" aria-hidden="true"></i>Sanatçı: <?php echo $islem["sanatci"]?></span></div>
				</div>
				<div class="element">
					<div class="sol"><span><i class="fa fa-music" aria-hidden="true"></i>Parça: <?php echo $islem["parca"]?></span></div>
				</div>
				<div class="element">
					<div class="sol"><span><i class="fa fa-play-circle" aria-hidden="true"></i>Albüm: <?php if(empty($islem["album"])){echo "(belirtilmemiş)";}else{echo $islem["album"];} ?></span></div>
				</div>
				<div class="element">
					<div class="sol"><span><i class="fa fa-calendar" aria-hidden="true"></i>Yüklendiği Tarih: <?php echo date('d.m.Y H:i:s', $islem["tarih"])?></span></div>
				</div>
				<a href="/p/<?php echo $islem["yukleyen"]?>"><div class="element">
					<div class="sol"><span><i class="fa fa-share" aria-hidden="true"></i>Yükleyen: <u><?php echo $islem["yukleyen"]?></u></span></div>
				</div></a>
			</div>
			<div id="menu" class="rastgele_toggle" style="float:right;background-color:#B0FFAD;margin:-30px 0px 10px 0px;cursor:pointer" onclick="rastgele_toggle(this)">
				<p class="son"><i class="fa fa-toggle-on" aria-hidden="true"></i>Otomatik Oynatma: AÇIK</p>
			</div>
			<script>
				if ( getCookie("rastgele") == "acik" ){
					document.getElementsByClassName("rastgele_toggle")[0].style.backgroundColor = "#B0FFAD";
					document.getElementsByClassName("rastgele_toggle")[0].innerHTML = '<p class="son"><i class="fa fa-toggle-on" aria-hidden="true"></i>Otomatik Oynatma: AÇIK</p>';
				}else{
					document.getElementsByClassName("rastgele_toggle")[0].style.backgroundColor = "#dedede";
					document.getElementsByClassName("rastgele_toggle")[0].innerHTML = '<p class="son"><i class="fa fa-toggle-off" aria-hidden="true"></i>Otomatik Oynatma: KAPALI</p>';
				}
			</script>
			<audio controls autoplay preload="false" id="oynatici">
				<source src="/icerik/<?php echo $islem["kimlik"].".".$islem["uzanti"]; ?>" type="audio/mpeg">
				Tarayıcınız bu ses dosyasını desteklemmiyor.
			</audio>
			<script type="text/javascript">
				function rastgele(){ window.location="/i/<?php echo $rastgl["kimlik"];?>"; };
				document.getElementById('oynatici').addEventListener("ended",rastgele);
				if ( getCookie("rastgele") !== "acik" ){
					document.getElementById('oynatici').removeEventListener("ended",rastgele);
				}
			</script>
			<div id="icerik-gb" style="padding-bottom:10px">Zpotify'a yeni eklenen bir özellik sayesinde, parça bittikten sonra rastgele bir parçaya yönlendirilirsiniz.</div>
			<div id="icerik-gb" style="padding-bottom:10px">Eğer parçayı beğenmediyseniz, parçayı değiştirmek için sadece şuna tıklayın:</div>
			<div id="menu" style="float:left;background-color:#B0FFAD;margin:0px;cursor:pointer" onclick="rastgele()">
				<p class="son"><i class="fa fa-random" aria-hidden="true"></i>Rastgele</p>
			</div>
			<div>
				<!--<div id="menu" style="float:left;background-color:#dedede">
					<a href=""><p><i class="fa fa-thumbs-up" aria-hidden="true"></i>Evet</p></a>
					<a href=""><p class="son"><i class="fa fa-thumbs-down" aria-hidden="true"></i></i>Hayır</p></a>
				</div>-->
				<?php
					if ( $_SESSION["oturum"] == true ){
						$sth = $db -> prepare("SELECT yetki FROM kullanici WHERE ka = ?");
						$sth -> execute(array($_SESSION["ka"]));
						$kullanici = $sth -> fetch();
						if ( $_SESSION["ka"] == $islem["yukleyen"] or $kullanici["yetki"] == "yonetici" ){
							echo '<a href="/duzenle/'.$islem["kimlik"].'"><div id="menu" style="float:left;background-color:#dedede;margin:0px;cursor:pointer">
					<p class="son"><i class="fa fa-pencil" aria-hidden="true"></i>Düzenle</p>
				</div></a>';
						}
					}
					
					$sth = $db -> prepare("SELECT * FROM favori WHERE ka = ? && kimlik = ?");
					$sth -> execute(array($_SESSION["ka"],$islem["kimlik"]));
					$favdb = $sth -> fetch();
					if ( $favdb ):
				?>
				<div id="menu" style="float:right;background-color:#B0FFAD;margin:0px;cursor:pointer" onclick="favori(this)">
					<p class="son"><i class="fa fa-star" aria-hidden="true"></i>Favori</p>
				</div>
				<?php	else:	?>
				<div id="menu" style="float:right;background-color:#dedede;margin:0px;cursor:pointer" onclick="favori(this)">
					<p class="son"><i class="fa fa-star-o" aria-hidden="true"></i>Favori</p>
				</div>
				<?php	endif;	?>

			</div>
		</div>
		<div id="disqus_thread"></div>
<script>


(function() { // DON'T EDIT BELOW THIS LINE
var d = document, s = d.createElement('script');
s.src = 'https://zpotify.disqus.com/embed.js';
s.setAttribute('data-timestamp', +new Date());
(d.head || d.body).appendChild(s);
})();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
                                
	</div>
	<script>
		function favori(element){
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					if ( this.responseText == "true"){
						element.style.backgroundColor = "#B0FFAD";
						element.innerHTML ='<p class="son"><i class="fa fa-star" aria-hidden="true"></i>Favori</p>';
					}else if ( this.responseText == "false" ){
						element.style.backgroundColor = "#dedede";
						element.innerHTML ='<p class="son"><i class="fa fa-star-o" aria-hidden="true"></i>Favori</p>';
					}else if ( this.responseText == "giris" ){
						element.style.backgroundColor = "#dedede";
						element.innerHTML ='<p class="son">Önce giriş yapmalısın.</p>';
					}
				}
			};
			xmlhttp.open("GET", "/favori.php?f=<?php echo $islem["kimlik"]?>", true);
			xmlhttp.send();
		}
		function rastgele_toggle(element){
			if ( getCookie("rastgele") === "acik" ){
				setCookie("rastgele", "kapali");
				element.style.backgroundColor = "#dedede";
				element.innerHTML ='<p class="son"><i class="fa fa-toggle-off" aria-hidden="true"></i>Otomatik Oynatma: KAPALI</p>';
				document.getElementById('oynatici').removeEventListener("ended",rastgele);
			}else{
				setCookie("rastgele", "acik");
				element.style.backgroundColor = "#B0FFAD";
				element.innerHTML ='<p class="son"><i class="fa fa-toggle-on" aria-hidden="true"></i>Otomatik Oynatma: AÇIK</p>';
				document.getElementById('oynatici').addEventListener("ended",rastgele);
			}
		}
	</script>
