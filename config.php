<?php
	header("Content-Type: text/html; charset=utf-8");
	
	// настройки
	$ArrConfig["Rows"]		 = 5;

	include_once("other.php");
	$sql = new class_sql;

	$sql->sql_set("login","denis");
	$sql->sql_set("passwd","denis");
	$sql->sql_set("database","tz2");
	$sql->sql_set("host","localhost");
	$sql->sql_set("db_prefix","tz_");
	$sql->sql_set("codepage","utf8");
?>