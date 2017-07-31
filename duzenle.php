<?php
	$uzanti = array(
		"kf" => array("jpg","jpeg","png","bmp")
	);
	$sinir = array(
		"kf" => 2 //MB
	);
	
	$sth = $db -> prepare("SELECT yetki FROM kullanici WHERE ka = ?");
	$sth -> execute(array($_SESSION["ka"]));
	$kullanici = $sth -> fetch();
	
	if ( !isset($index_anahtar) ){
		header("Location:/");
	}else{
		if ( $_SESSION["ka"] == $islem["yukleyen"] or $kullanici["yetki"] == "yonetici" ){
			if ( @$_POST["sil"] == "sil" ){
				$sil_icerik = unlink("icerik/".$islem["kimlik"].".".$islem["uzanti"]);
				if ( is_file("kf/".$islem["kimlik"].".".$islem["kf"]) ){
					$sil_kf =  unlink("kf/".$islem["kimlik"].".".$islem["kf"]);
				}else{ $sil_kf = true; }
				if ( $sil_icerik && $sil_kf ){
					$sth = $db -> prepare("DELETE FROM icerik WHERE kimlik = ?");
					$sth -> execute(array($islem["kimlik"]));
					$sth = $db -> prepare("DELETE FROM favori WHERE kimlik = ?");
					$sth -> execute(array($islem["kimlik"]));
					header("Location:/");
				}else{
					$mesaj[0]["k"] = "Dosyalar silinirken bir hata oluştu";
				}
			}else{
				if ( @$_POST["duzenle"] ){
					$parca = trim(substr($_POST["parca"], 0, 150));
					$sanatci = trim(substr($_POST["sanatci"], 0, 100));
					$album = trim(substr($_POST["album"], 0, 100));
					if ( !empty($parca) ){
						$sth = $db -> prepare("UPDATE icerik SET parca = ? WHERE kimlik = ?");
						$sth -> execute(array($parca,$islem["kimlik"]));
						$mesaj[0]["y"] = "Parçanın adı $parca olarak güncellendi.";
					}
					if ( !empty($sanatci) ){
						$sth = $db -> prepare("UPDATE icerik SET sanatci = ? WHERE kimlik = ?");
						$sth -> execute(array($sanatci,$islem["kimlik"]));
						$mesaj[1]["y"] = "Sanatçının adı $sanatci olarak güncellendi.";
					}
					if ( !empty($album) ){
						$sth = $db -> prepare("UPDATE icerik SET album = ? WHERE kimlik = ?");
						$sth -> execute(array($album,$islem["kimlik"]));
						$mesaj[2]["y"] = "Albümün adı $album olarak güncellendi.";
					}
					if ( $_FILES["kf"]["name"] ){
						$kfadi = $_FILES["kf"]["name"];
						$kfuzanti = strtolower(pathinfo($kfadi)["extension"]);
						if ( in_array($kfuzanti, $uzanti["kf"]) ){
							if ( $_FILES["kf"]["size"] < $sinir["kf"]*1048576 ){
								if ( $_FILES["kf"]["error"] > 0 ){
									$mesaj[3]["k"] = "Yüklenirken bir hata oluştu";
								}else{
									$kayitadi = $islem["kimlik"].".".$kfuzanti;
									$tasi = move_uploaded_file($_FILES["kf"]["tmp_name"],"./kf/".$kayitadi);
									if ( $tasi ){
										$sth = $db -> prepare("UPDATE icerik SET kf = ? WHERE kimlik = ?");
										$sth -> execute(array($kfuzanti,$islem["kimlik"]));
										$mesaj[3]["y"] = "Kapak fotoğrafı güncellendi.";
									}else{
										$mesaj[3]["k"] = "Dosya kopyalanırken sorun oluştu.";
									}
								}
							}else{
								$mesaj[3]["k"] = "Dosya en fazla ".$sinir["kf"]." MB boyutunda olmalıdır";
							}
						}else{
							$mesaj[3]["k"] = "Dosyanın uzantısı yüklemeye uygun değil.";
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
				if ( isset( $mesaj[3] ) ){
					$mesaj_cikti .= "<div id=\"mesaj\" onclick=\"this.style.display='none'\"";
					if ( isset($mesaj[3]["k"]) ){
						$mesaj_cikti = $mesaj_cikti." style=\"background-color:#FF5454\">".$mesaj[3]["k"];
					}elseif ( isset($mesaj[3]["y"]) ){
						$mesaj_cikti = $mesaj_cikti." style=\"background-color:#2CB835\">".$mesaj[3]["y"];
					}
					$mesaj_cikti = $mesaj_cikti."</div>";
				}
			}
			$sth = $db -> prepare("SELECT * FROM icerik WHERE kimlik = ?");
			$sth -> execute(array($islem["kimlik"]));
			$islem = $sth -> fetch();
		}else{
			header("Location:/");
		}
	}
?>
	<script>document.getElementsByTagName("title")[0].innerHTML = "<?php echo $islem["sanatci"]." - ".$islem["parca"]." - Zpotify";?>"</script>
	<div id="icerik" class="ortala">
		<div style="overflow:hidden;margin-bottom:40px">
			<div id="profil-foto">
				<img src="<?php if($islem["kf"]!="yok"){echo "/kf/".$islem["kimlik"].".".$islem["kf"];}else{echo "/kf.png";} ?>">
				<div class="liste">
					<div class="element"><h2>Parça Bilgisi</h2></div>
					<div class="element">
						<div class="sol"><span><i class="fa fa-user" aria-hidden="true"></i>Sanatçı: <?php echo $islem["sanatci"]?></span></div>
					</div>
					<div class="element">
						<div class="sol"><span><i class="fa fa-music" aria-hidden="true"></i>Parça: <?php echo $islem["parca"]?></span></div>
					</div>
					<div class="element son">
						<div class="sol"><span><i class="fa fa-play-circle" aria-hidden="true"></i>Albüm: <?php if(empty($islem["album"])){echo "(belirtilmemiş)";}else{echo $islem["album"];} ?></span></div>
					</div>
				</div>
			</div>
			<div id="profil-icerik">
				<?php echo $mesaj_cikti; ?>
				<div>
					<form id="sil" method="post">
					<input type="hidden" value="sil" name="sil">
					<div id="menu" style="float:right;background-color:#FFB0AD;margin:0;cursor:pointer" onclick="document.getElementById('sil').submit()">
						<p class="son"><i class="fa fa-trash" aria-hidden="true"></i>İçeriği Sil</p>
					</div>
					</form>
				</div>
				<div id="giris-form" style="margin-top:0">
					<form method="POST" enctype="multipart/form-data">
						<h3>Albüm kapağı**:</h3>
						<input class="dosya" type="file" accept="image/*" name="kf">
						<h3>Parça adı:</h3>
						<input class="arama-cubugu" type="text" maxlength="150" name="parca">
						<h3>Sanatçı:</h3>
						<input class="arama-cubugu" type="text" maxlength="100" name="sanatci">
						<h3>Albüm:</h3>
						<input class="arama-cubugu" type="text" maxlength="100" name="album">
						<input class="input-buton" type="submit" value="Düzenle!" name="duzenle">
					</form>
					<p>Boş bırakılan hiçbir veri değiştirilmez.</p>
					<p>*: isteğe bağlıdır, jp(e)g, png veya bmp ve max. 2MB olmalıdır.</p>
				</div>
			</div>
		</div>
	</div>