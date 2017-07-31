<?php
	if ( !isset($index_anahtar) ){
		header("Location:/");
	}else{
		if( @$_POST["giris"] ){
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
					$sifre = sha1($_POST["sifre"]);
					$sifre = substr($sifre, 0, 80);
					if ( empty($ka) or empty($sifre) ){
						$mesaj["k"] = "Bazı bilgiler eksik.";
					}else{
						$sth = $db -> prepare("SELECT * FROM kullanici WHERE ka=? and sifre=? LIMIT 1");
						$sth -> execute(array($ka,$sifre));
						$islem = $sth -> fetch();
						if ( $islem ){
							$_SESSION["oturum"] = true;
							$_SESSION["anahtar"] = anahtar();
							$_SESSION["ka"] = $islem["ka"];
							$mesaj["y"] = "Giriş başarılı. Yönlendiriliyorsunuz...";
							header("refresh:1;url=/");
						}else{
							$mesaj["k"] = "Kullanıcı veya şifre hatalı.";
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
				<h3>Şifre:</h3>
				<input class="arama-cubugu" type="password" maxlength="80" required name="sifre">
				<input class="input-buton" type="submit" value="Giriş!" name="giris">
			</form>
			<p>Giriş yaparak <u><a href="/anasayfa/kk">Kullanım Koşullarını</a></u> kabul etmiş olursunuz.</p>
			<p>Hesabın yok mu? <u><a href="/kayit">Kayıt ol!</a></u></p>
		</div>
	</div>