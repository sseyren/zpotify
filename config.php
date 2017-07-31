<?php
	session_start();
	//error_reporting(E_ALL);
	
	date_default_timezone_set('Europe/Istanbul');
	setlocale(LC_TIME, 'tr_TR');
	//setlocale(LC_TIME, 'tr_TR.UTF-8');
	
	$dsn = "mysql:dbname=ZPOTIFY_DATABASE;host=ZPOTIFY_HOST;charset=utf8";
	$kullanici = "ZPOTIFY_ADMIN";
	$sifre = "ZPOTIFY_PASSWORD";
	
	try {
	$db = new PDO($dsn, $kullanici, $sifre);
	} catch (PDOException $e) {
	echo "Veri tabanina baglanti saglanamadi! (" . $e->getMessage() . ")";
	}
?>