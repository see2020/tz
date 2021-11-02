<?php
/**
 * Удаление записи
 */

include("config.php");

$sql->sql_connect();

if(!empty($_POST['id']) && is_numeric($_POST['id'])){
	if($sql->sql_delete('form',"id = '" . $_POST['id'] . "'")){
		echo 'Запись №: ' . $_POST['id'].' успешно удалена';
	}
	else{
		echo 'Ошибка БД при удалении записи №: ' . $_POST['id'];
	}
}
else{
	echo 'Ошибка удаления записи №: ' . $_POST['id'];
}

$sql->sql_close();