<?php
/**
 * Список записей
 */

	include("config.php");
	$filter = "(1=1)";

	if(!empty($_POST["tz_name"])){
		$filter .= " AND tz_name LIKE '%" . clear_str_filt($_POST["tz_name"]) . "%'";
	}
	if(!empty($_POST["tz_phone"])){
		$filter .= " AND tz_phone LIKE '%" . clear_str_filt($_POST["tz_phone"]) . "%'";
	}
	if(!empty($_POST["tz_email"])){
		$filter .= " AND tz_email LIKE '%" . clear_str_filt($_POST["tz_email"]) . "%'";
	}

	$sql->sql_connect();
	if(!empty($_GET['pagenum']) && is_numeric($_GET['pagenum'])){$pg = $_GET['pagenum'];}else{$pg = 1;}
	$rowCount = $sql->sql_rows($sql->sql_query("SELECT * FROM `".$sql->db_prefix."form` WHERE ".$filter));
	$rowStart = page_get_count($pg,$rowCount,$ArrConfig["Rows"]);
	$result = $sql->sql_query("SELECT * FROM ".$sql->db_prefix."form  WHERE ".$filter."
		ORDER BY id DESC LIMIT ".$rowStart.",".$ArrConfig["Rows"]);
	if($sql->sql_err){echo "Ошибка получения списка записей ".$sql->sql_err;}
	else{
		if($sql->sql_rows($result)){
			echo "<table class=\"table table-striped\">";
			echo "<thead>";
			echo "<tr>";
			echo "<th>№</th>";
			echo "<th>Имя</th>";
			echo "<th>Телефон</th>";
			echo "<th>E-mail</th>";
			echo "<th>Удалить</th>";
			echo "</tr>";
			echo "</thead>";
			echo "<tbody>";
			while($query = $sql->sql_array($result)){
				echo "<tr>";
				echo "<td>".$query["id"]."</td>";
				echo "<td>".$query["tz_name"]."</td>";
				echo "<td>".$query["tz_phone"]."</td>";
				echo "<td>".$query["tz_email"]."</td>";
				echo "<td><a href='javascript:void(0);' onclick=\"del('".$query["id"]."');\">Удалить</a></td>";
				echo "</tr>";
			}
			echo "</tbody>";
			echo "</table>";
			echo page_list_show_aj($pg,$rowCount,$ArrConfig["Rows"]);
		}
		else{
			echo "Записей нет";
		}
	}
	$sql->sql_close();