<?php
	if ( $_SESSION["oturum"] == true ){
		$menu = '<a href="/yukle"><p><i class="fa fa-upload" aria-hidden="true"></i>Yükle</p></a>
						<a href="/p"><p><i class="fa fa-user" aria-hidden="true"></i>Profil</p></a>
						<a href="/f"><p><i class="fa fa-star" aria-hidden="true"></i>Favoriler</p></a>
						<a href="/cikis"><p class="son"><i class="fa fa-sign-out" aria-hidden="true"></i>Çıkış</p></a>
';
		$mobil_menu = '<div class="element">
					<div class="sol"><a href="/yukle"><span><i class="fa fa-upload" aria-hidden="true"></i>Yükle</span></a></div>
					<div class="sag"><a href="/p"><span><i class="fa fa-user" aria-hidden="true"></i>Profil</span></a></div>
				</div>
				<div class="element son">
					<div class="sol"><a href="/f"><span><i class="fa fa-star" aria-hidden="true"></i>Favoriler</span></a></div>
					<div class="sag"><a href="/cikis"><span><i class="fa fa-sign-out" aria-hidden="true"></i>Çıkış</span></a></div>
				</div>
';
		$ka = $_SESSION["ka"];
		$sth = $db -> prepare("SELECT pf FROM kullanici WHERE ka = ?");
		$sth -> execute(array($_SESSION["ka"]));
		$pfbilgi = $sth -> fetch();
		if ( $pfbilgi["pf"] == "yok" ){
			$ustpf = "/pp.png";
		}else{
			$ustpf = "/pf/".$_SESSION["ka"].".".$pfbilgi["pf"];
		}
	}else{
		$menu = '<a href="/kayit"><p><i class="fa fa-user-plus" aria-hidden="true"></i>Kayıt</p></a>
						<a href="/giris"><p class="son"><i class="fa fa-sign-in" aria-hidden="true"></i>Giriş</p></a>
';
		$mobil_menu = '<div class="element son">
					<div class="sol"><a href="/kayit"><span><i class="fa fa-user-plus" aria-hidden="true"></i>Kayıt</span></a></div>
					<div class="sag"><a href="/giris"><span><i class="fa fa-sign-in" aria-hidden="true"></i>Giriş</span></a></div>
				</div>
';
		$ka = "Ziyaretçi";
		$ustpf = "/pp.png";
	}
?>
<body>
	<div id="baslik-dis">
		<div id="baslik" class="ortala">
			<div id="bas-sol"><a href="/"><img id="logo" src="/zpotifyson.png"></a></div>
			<div id="masaustu">
				<div id="bas-sag">
					<div id="kullanici">
						<img id="pp" src="<?php echo $ustpf;?>">
						<p id="ka"><?php echo $ka; ?></p>
					</div>
				</div>
				<div id="bas-orta">
					<div id="menu">
						<?php echo $menu; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="mobil" class="ortala">
		<div id="mobil-menu">
			<div id="kullanici" onclick="if(document.getElementById('mobil-liste').style.display != 'none'){document.getElementById('mobil-liste').style.display='none';}else{document.getElementById('mobil-liste').style.display='';}">
				<img id="pp" src="<?php echo $ustpf;?>">
				<p id="ka"><?php echo $ka; ?></p>
			</div>
			<div class="liste" id="mobil-liste" style="display:none">
				<?php echo $mobil_menu; ?>
			</div>
		</div>
	</div>