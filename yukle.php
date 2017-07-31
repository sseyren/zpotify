<?php
	$uzanti = array(
		"parca_dosya" => array("mp3"),
		"kf" => array("jpg","jpeg","png","bmp")
	);
	$sinir = array(
		"parca_dosya" => 16, //MB
		"kf" => 2 //MB
	);
	if ( !isset($index_anahtar) ){
		header("Location:/");
	}else{
		if ( $_SESSION["oturum"] == true && $islem["ban"] == "yok" ){
			if( @$_POST["yukle"] ){
				$parca = trim(substr($_POST["parca"], 0, 150));
				$sanatci = trim(substr($_POST["sanatci"], 0, 100));
				$album = trim(substr($_POST["album"], 0, 100));
				if ( empty($parca) or empty($sanatci) ){
					$mesaj["k"] = "Bazı bilgiler eksik.";
				}else{
					if ( $_FILES["parca_dosya"]["name"] ){
						$dosyaadi = $_FILES["parca_dosya"]["name"];
						$dosyauzanti = strtolower(pathinfo($dosyaadi)["extension"]);
						if ( in_array($dosyauzanti, $uzanti["parca_dosya"]) ){
							if ( $_FILES["parca_dosya"]["size"] < $sinir["parca_dosya"]*1048576 ){
								if ( $_FILES["parca_dosya"]["error"] > 0 ){
									$mesaj["k"] = "Yüklenirken bir hata oluştu";
								}else{
									$debug = true;
									while($debug == true){
										$kimlik = substr(md5(time().rand(0,255)), 0, 10);
										$sth = $db ->prepare("SELECT kimlik FROM icerik WHERE kimlik=? LIMIT 1");
										$sth -> execute(array($kimlik));
										$islem = $sth -> fetch();
										if ( !$islem ){
											$debug = false;
										}
									}
									if ( $_FILES["kf"]["name"] ){
										$kfadi = $_FILES["kf"]["name"];
										$kfuzanti = strtolower(pathinfo($kfadi)["extension"]);
										if ( in_array($kfuzanti, $uzanti["kf"]) ){
											if ( $_FILES["kf"]["size"] < $sinir["kf"]*1048576 ){
												if ( $_FILES["kf"]["error"] > 0 ){
													$mesaj["k"] = "Yüklenirken bir hata oluştu";
												}else{
													$kayitadi = $kimlik.".".$kfuzanti;
													$tasi = move_uploaded_file($_FILES["kf"]["tmp_name"],"./kf/".$kayitadi);
													if ( $tasi ){
														$kf = $kfuzanti;
													}else{
														$mesaj["k"] = "Dosya kopyalanırken sorun oluştu.";
													}
												}
											}else{
												$mesaj["k"] = "Dosya en fazla ".$sinir["kf"]." MB boyutunda olmalıdır";
											}
										}else{
											$mesaj["k"] = "Dosyanın uzantısı yüklemeye uygun değil.";
										}
									}else{
										$kf = "yok";
									}
									if ( !isset($mesaj["k"]) ){
										$kayitadi = $kimlik.".".$dosyauzanti;
										$tasi = move_uploaded_file($_FILES["parca_dosya"]["tmp_name"],"./icerik/".$kayitadi);
										if ( $tasi ){
											require "mp3file.class.php";
											$mp3file = new MP3File("./icerik/".$kayitadi);
											$duration = $mp3file->getDuration();
											$uzunluk = MP3File::formatTime($duration);
											$sth = $db -> prepare("INSERT INTO icerik (kimlik,sanatci,parca,album,yukleyen,tarih,uzanti,kf,uzunluk) VALUES (?,?,?,?,?,?,?,?,?)");
											$sth -> execute(array($kimlik,$sanatci,$parca,$album,$_SESSION["ka"],time(),$dosyauzanti,$kf,$uzunluk));
											$mesaj["y"] = "İçerik başarıyla yüklendi! İçeriğin linki: <a href='/i/".$kimlik."' style='color:#fff'>/i/".$kimlik."</a>";
										}else{
											$mesaj["k"] = "Dosya kopyalanırken sorun oluştu.";
										}
									}
								}
							}else{
								$mesaj["k"] = "Dosya en fazla ".$sinir["parca_dosya"]." MB boyutunda olmalıdır";
							}
						}else{
							$mesaj["k"] = "Dosyanın uzantısı yüklemeye uygun değil.";
						}
					}else{
						$mesaj["k"] = "Bir dosya seçmelisin.";
					}
				}
			}
		}else{
			header("Location:/");
		}
	}
	if ( isset($mesaj) ){
		$mesaj_cikti = "<div id=\"mesaj\"";
		if ( isset($mesaj["k"]) ){
			$mesaj_cikti = $mesaj_cikti." style=\"background-color:#FF5454\">".$mesaj["k"];
		}elseif ( isset($mesaj["y"]) ){
			$mesaj_cikti = $mesaj_cikti." style=\"background-color:#2CB835\">".$mesaj["y"];
		}
		$mesaj_cikti = $mesaj_cikti."</div>";
	}
?>
	<div id="icerik" class="ortala">
		<div id="yukleme-alani">
			<div id="yukleme-cubugu" style="display:none">
				<div id="ilerleme" style="width:0%"><span id="ilerleme-metin"></span></div>
			</div>
			<?php echo $mesaj_cikti; ?>
			<div id="giris-form" style="margin-top:0">
				<form method="POST" id="yukle" enctype="multipart/form-data">
					<input type="hidden" value="yukle" name="<?php echo ini_get("session.upload_progress.name"); ?>">
					<h3>Parça*:</h3>
					<input class="dosya" type="file" accept="audio/*" required name="parca_dosya">
					<h3>Albüm kapağı**:</h3>
					<input class="dosya" type="file" accept="image/*" name="kf">
					<h3>Parça adı:</h3>
					<input class="arama-cubugu" type="text" maxlength="150" required name="parca">
					<h3>Sanatçı:</h3>
					<input class="arama-cubugu" type="text" maxlength="100" required name="sanatci">
					<h3>Albüm:</h3>
					<input class="arama-cubugu" type="text" maxlength="100" placeholder="(yoksa boş bırakın)" name="album">
					<input class="input-buton" type="submit" value="Yükle!" name="yukle">
				</form>
				<p>*: Sadece mp3 ve max. 16MB olmalıdır.</p>
				<p>**: isteğe bağlıdır, jp(e)g, png veya bmp ve max. 2MB olmalıdır.</p>
				<script>
					function toggleBarVisibility() {
						var e = document.getElementById("yukleme-cubugu");
						e.style.display = (e.style.display == "block") ? "none" : "block";
					}

					function createRequestObject() {
						var http;
						if (navigator.appName == "Microsoft Internet Explorer") {
							http = new ActiveXObject("Microsoft.XMLHTTP");
						}
						else {
							http = new XMLHttpRequest();
						}
						return http;
					}

					function sendRequest() {
						var http = createRequestObject();
						http.open("GET", "ilerleme.php");
						http.onreadystatechange = function () { handleResponse(http); };
						http.send(null);
					}

					function handleResponse(http) {
						var response;
						if (http.readyState == 4) {
							response = http.responseText;
							document.getElementById("ilerleme").style.width = response + "%";
							if ( response >= 10 ){
								document.getElementById("ilerleme-metin").innerHTML = response + "%";
							}
							if (response < 100) {
								setTimeout("sendRequest()", 500);
							}
							else {
								toggleBarVisibility();
								document.getElementById("ilerleme-metin").innerHTML = "Done.";
							}
						}
					}

					function startUpload() {
						toggleBarVisibility();
						document.getElementsByTagName("input")
						setTimeout("sendRequest()", 500);
					}

					(function () {
						document.getElementById("yukle").onsubmit = startUpload;
					})();
				</script>
			</div>
		</div>
	</div>