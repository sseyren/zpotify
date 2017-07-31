<?php
	if ( !isset($index_anahtar) ){
		header("Location:/");
	}else{
		$sth = $db -> prepare("SELECT COUNT(*) AS sayi FROM icerik WHERE yukleyen=?");
		$sth -> execute(array($islem["ka"]));
		$sayi = $sth -> fetch();

		$uzanti = array(
			"pf" => array("jpg","jpeg","png","bmp")
		);
		$sinir = array(
			"pf" => 2 //MB
		);

		if ( @$_POST["duzenle"] ){
			if ( $_SESSION["oturum"] == true && $_SESSION["ka"] == $islem["ka"] ){
				$eposta = trim(substr($_POST["eposta"], 0, 80));
				$sifre = trim(substr($_POST["sifre"], 0, 80));
				$sifre_tekrar = trim(substr($_POST["sifre_tekrar"], 0, 80));
				if ( !empty($eposta) ){
					if ( filter_var($eposta, FILTER_VALIDATE_EMAIL) ){
						$sth = $db -> prepare("UPDATE kullanici SET eposta = ? WHERE ka = ?");
						$sth -> execute(array($eposta,$islem["ka"]));
						$mesaj[0]["y"] = "E-posta'nız $eposta olarak güncellendi.";
					}else{
						$mesaj[0]["k"] = "E-posta geçersiz.";
					}
				}
				if ( !empty($sifre) ){
					if ( $sifre == $sifre_tekrar ){
						$sth = $db -> prepare("UPDATE kullanici SET sifre = ? WHERE ka = ?");
						$sth -> execute(array(sha1($sifre),$islem["ka"]));
						$mesaj[1]["y"] = "Şifreniz güncellendi.";
					}else{
						$mesaj[1]["k"] = "Şifreler uyuşmuyor.";
					}
				}
				if ( $_FILES["pf"]["name"] ){
					$pfadi = $_FILES["pf"]["name"];
					$pfuzanti = strtolower(pathinfo($pfadi)["extension"]);
					if ( in_array($pfuzanti, $uzanti["pf"]) ){
						if ( $_FILES["pf"]["size"] < $sinir["pf"]*1048576 ){
							if ( $_FILES["pf"]["error"] > 0 ){
								$mesaj[2]["k"] = "Yüklenirken bir hata oluştu";
							}else{
								$kayitadi = $islem["ka"].".".$pfuzanti;
								$tasi = move_uploaded_file($_FILES["pf"]["tmp_name"],"./pf/".$kayitadi);
								if ( $tasi ){
									$sth = $db -> prepare("UPDATE kullanici SET pf = ? WHERE ka = ?");
									$sth -> execute(array($pfuzanti,$islem["ka"]));
									$mesaj[2]["y"] = "Profil fotoğrafınız güncellendi.";
								}else{
									$mesaj[2]["k"] = "Dosya kopyalanırken sorun oluştu.";
								}
							}
						}else{
							$mesaj[2]["k"] = "Dosya en fazla ".$sinir["pf"]." MB boyutunda olmalıdır";
						}
					}else{
						$mesaj[2]["k"] = "Dosyanın uzantısı yüklemeye uygun değil.";
					}
				}
			}
		}
		if ( isset($mesaj) ){
			if ( isset( $mesaj[0] ) ){
				$mesaj_cikti = "<div id=\"mesaj\" onclick=\"this.style.display='none'\"";
				if ( isset($mesaj[0]["k"]) ){
					$mesaj_cikti = $mesaj_cikti." style=\"background-color:#FF5454\">".$mesaj[0]["k"];
				}elseif ( isset($mesaj[0]["y"]) ){
					$mesaj_cikti = $mesaj_cikti." style=\"background-color:#2CB835\">".$mesaj[0]["y"];
				}
				$mesaj_cikti = $mesaj_cikti."</div>";
			}
			if ( isset( $mesaj[1] ) ){
				$mesaj_cikti .= "<div id=\"mesaj\" onclick=\"this.style.display='none'\"";
				if ( isset($mesaj[1]["k"]) ){
					$mesaj_cikti = $mesaj_cikti." style=\"background-color:#FF5454\">".$mesaj[1]["k"];
				}elseif ( isset($mesaj[1]["y"]) ){
					$mesaj_cikti = $mesaj_cikti." style=\"background-color:#2CB835\">".$mesaj[1]["y"];
				}
				$mesaj_cikti = $mesaj_cikti."</div>";
			}
			if ( isset( $mesaj[2] ) ){
				$mesaj_cikti .= "<div id=\"mesaj\" onclick=\"this.style.display='none'\"";
				if ( isset($mesaj[2]["k"]) ){
					$mesaj_cikti = $mesaj_cikti." style=\"background-color:#FF5454\">".$mesaj[2]["k"];
				}elseif ( isset($mesaj[2]["y"]) ){
					$mesaj_cikti = $mesaj_cikti." style=\"background-color:#2CB835\">".$mesaj[2]["y"];
				}
				$mesaj_cikti = $mesaj_cikti."</div>";
			}
		}
	}
?>
	<div id="icerik" class="ortala">
		<div style="overflow:hidden;margin-bottom:40px">
			<div id="profil-foto">
				<img src="<?php if($islem["pf"]!="yok"){echo "/pf/".$islem["ka"].".".$islem["pf"];}else{echo "/pp.png";} ?>">
			</div>
			<div id="profil-icerik">
				<?php echo $mesaj_cikti; ?>
				<div class="liste" id="profil-bilgi" style="margin-bottom:10px">
					<div class="element"><h2>Profil Bilgisi</h2></div>
					<div class="element">
						<div class="sol"><span><i class="fa fa-user" aria-hidden="true"></i>Kullanıcı Adı: <?php echo $islem["ka"]?></span></div>
					</div>
					<div class="element">
						<div class="sol"><span><i class="fa fa-share" aria-hidden="true"></i>Paylaşım Sayısı: <?php echo $sayi["sayi"];?></span></div>
					</div>
					<a href="/f/<?php echo $islem["ka"];?>"><div class="element son">
						<div class="sol"><u><span><i class="fa fa-star" aria-hidden="true"></i>Kullanıcının favori listesini görmek için tıklayın.</span></u></div>
					</div></a>
				</div>
				<?php if ( $_SESSION["oturum"] == true and $_SESSION["ka"] == $islem["ka"] ): ?>
				<div>
					<div id="menu" style="float:right;background-color:#B0FFAD;margin:0;cursor:pointer" onclick="duzenle(this)">
						<p class="son"><i class="fa fa-pencil" aria-hidden="true"></i>Profili Düzenle</p>
					</div>
				</div>

				<div id="giris-form" style="margin-top:0;display:none">
					<form method="POST" enctype="multipart/form-data">
						<h3>Kullanıcı Fotoğrafı*:</h3>
						<input class="dosya" type="file" accept="image/*" name="pf">
						<h3>E-posta:</h3>
						<input class="arama-cubugu" type="text" maxlength="80" value="<?php echo $_POST["eposta"];?>" name="eposta">
						<h3>Şifre:</h3>
						<input class="arama-cubugu" type="password" maxlength="80" name="sifre">
						<h3>Şifre tekrar:</h3>
						<input class="arama-cubugu" type="password" maxlength="80" name="sifre_tekrar">
						<input class="input-buton" type="submit" value="Düzenle!" name="duzenle">
					</form>
					<p>Boş bırakılan hiçbir veri değiştirilmez.</p>
					<p>*: isteğe bağlıdır, jp(e)g, png veya bmp, max. 2MB ve max 500px x 500px olmalıdır.</p>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<div id="arama-yeri">
			<input id="arama" class="arama-cubugu" type="text" onkeyup="ara(true);" placeholder="<?php echo $islem["ka"]?> adlı kullanıcının yükledikleri...">
		</div>
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
			/*if ( document.getElementById("arama").value.length == 0 ){
				document.getElementById("arama-sonuc").innerHTML = "";
				document.getElementById("arama-sonuc").style.display = "none";
			}else{*/
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						document.getElementById("arama-sonuc").style.display = "";
						document.getElementById("arama-sonuc").innerHTML = this.responseText;
					}
				};
				xmlhttp.open("GET","/arama.php?q="+document.getElementById("arama").value+"&s="+sayfa+"&p=<?php echo $islem["ka"];?>",true);
				xmlhttp.send();
			//}
		}
		window.onload=ara();

		function duzenle(element){
			if ( document.getElementById("profil-bilgi").style.display == "none" ){
				element.innerHTML='<p class="son"><i class="fa fa-pencil" aria-hidden="true"></i>Profili Düzenle</p>';
				document.getElementById("profil-bilgi").style.display="";
				document.getElementById("giris-form").style.display="none";
			}else{
				element.innerHTML='<p class="son"><i class="fa fa-ban" aria-hidden="true"></i>Vazgeç</p>';
				document.getElementById("profil-bilgi").style.display="none";
				document.getElementById("giris-form").style.display="";
			}
		}
	</script>