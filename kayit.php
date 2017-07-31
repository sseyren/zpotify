<?php
	if ( !isset($index_anahtar) ){
		header("Location:/");
	}else{
		if( @$_POST["uye-kayit"] ){
			/*$recaptcha = $_POST['g-recaptcha-response'];
			if ( !empty($recaptcha) ){
				$google_url = "https://www.google.com/recaptcha/api/siteverify";
				$secret = 'SEEECCRRREEEETTTTT';
				$ip = $_SERVER['REMOTE_ADDR'];
				$url = $google_url . "?secret=" . $secret . "&response=" . $recaptcha . "&remoteip=" . $ip;
				$res = curlKullan($url);
				$res = json_decode($res, true);
				if ( $res['success'] ){*/
					$ka = $_POST["ka"];
					$ka = substr($ka, 0, 50);
					$ka = temizle($ka);
					$eposta = $_POST["eposta"];
					$eposta = substr($eposta, 0, 80);
					$sifre = $_POST["sifre"];
					$sifre = substr($sifre, 0, 80);
					$sifre_tekrar = $_POST["sifre_tekrar"];
					$sifre_tekrar = substr($sifre_tekrar, 0, 50);
					if ( empty($ka) or empty($eposta) or empty($sifre) or empty($sifre_tekrar) ){
						$mesaj["k"] = "Bazı bilgiler eksik.";
					}elseif ( $sifre != $sifre_tekrar ){
						$mesaj["k"] = "Şifreler uyuşmuyor.";
					}elseif ( !filter_var($eposta, FILTER_VALIDATE_EMAIL) ){
						$mesaj["k"] = "E-posta geçersiz.";
					}else{
						$sorgu = $db -> prepare("SELECT ka FROM kullanici WHERE ka=? LIMIT 1");
						$sorgu -> execute(array($ka));
						$islem = $sorgu -> fetch();
						if ( $islem ){
							$mesaj["k"] = "Böyle bir kullanıcı bulunmakta.";
						}else{
							$sth = $db -> prepare("INSERT INTO kullanici (ka,eposta,sifre) VALUES (?,?,?)");
							$sth -> execute(array($ka,$eposta,sha1($sifre)));
							$mesaj["y"] = "Kayıt başarılı. Yönlendiriliyorsunuz...";
							header("refresh:1;url=/giris");
						}
					}
				/*}else{
					$mesaj["k"] = "Robot olmadığınızı doğrulayın.";
				}
			}else{
				$mesaj["k"] = "Robot olmadığınızı doğrulayın.";
			}*/
		}
		if ( isset($mesaj) ){
			$mesaj_cikti = "<div id=\"mesaj\" onclick=\"this.style.display='none'\"";
			if ( isset($mesaj["k"]) ){
				$mesaj_cikti = $mesaj_cikti." style=\"background-color:#FF5454\">".$mesaj["k"];
			}elseif ( isset($mesaj["y"]) ){
				$mesaj_cikti = $mesaj_cikti." style=\"background-color:#2CB835\">".$mesaj["y"];
			}
			$mesaj_cikti = $mesaj_cikti."</div>";
		}
	}
?>
<body>
	<div id="giris">
		<a href="/"><div id="logo" style="text-align:center"><img id="logo" src="/zpotifyson.png"></div></a>
		<?php echo $mesaj_cikti; ?>
		<div id="giris-form">
			<form method="post">
				<h3>Kullanıcı adı:</h3>
				<input class="arama-cubugu" type="text" maxlength="50" value="<?php echo $_POST["ka"]; ?>" required autofocus name="ka">
				<h3>E-posta:</h3>
				<input class="arama-cubugu" type="text" maxlength="80" value="<?php echo $_POST["eposta"]; ?>" required name="eposta">
				<h3>Şifre:</h3>
				<input class="arama-cubugu" type="password" maxlength="80" required name="sifre">
				<h3>Şifre tekrar:</h3>
				<input class="arama-cubugu" type="password" maxlength="80" required name="sifre_tekrar">
				<!--<div class="g-recaptcha" data-sitekey="6LdERCkTAAAAABFMzTkDljaNVkyR4OAuivt1LDe0"></div>-->
				<input class="input-buton" type="submit" value="Kayıt!" name="uye-kayit">
			</form>
			<p>Kayıt olarak <u><a href="/anasayfa/kk">Kullanım Koşullarını</a></u> kabul etmiş olursunuz.</p>
			<p>Hesabın var mı? <u><a href="/giris">Giriş yap!</a></u></p>
		</div>
	</div>