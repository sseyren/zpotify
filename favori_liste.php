<?php
	if ( !isset($index_anahtar) ){
		header("Location:/");
	}
?>
	<div id="icerik" class="ortala">
		<div class="liste" id="arama-sonuc"></div>
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
			/*if ( document.getElementById("arama-cubugu").value.length == 0 ){
				document.getElementById("arama-sonuc").innerHTML = "";
				document.getElementById("arama-sonuc").style.display = "none";
			}else{*/
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						document.getElementById("arama-sonuc").innerHTML = this.responseText;
					}
				};
				xmlhttp.open("GET", "/favori_ara.php?s="+sayfa+"&u=<?php echo $islem["ka"];?>" , true);
				xmlhttp.send();
			//}
		}
		function sil(kimlik){
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					ara();
				}
			};
			xmlhttp.open("GET", "/favori_ara.php?k="+kimlik , true);
			xmlhttp.send();
		}
		window.onload=ara();
	</script>