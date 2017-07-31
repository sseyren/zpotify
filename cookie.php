<?php
	if (isset($_GET["i"])){
		if ( $_GET["i"] == "rastgele"){
			if ( $_COOKIE["rastgele"] == "acik" ){
				setcookie("rastgele", "kapali");
				echo "false";
			}else{
				setcookie("rastgele", "acik");
				echo "true";
			}
		}
	}
?>