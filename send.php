<?php
	include("config.php");
	
	$save = true;

	$ArrSave["tz_name"]	 = clear_str($_POST["tz-name"]);
	$ArrSave["tz_phone"] = clear_phone($_POST["tz-phone"]);
	$ArrSave["tz_email"] = (check_mail($_POST["tz-email"]))?trim($_POST["tz-email"]):"";

	if($ArrSave["tz_name"] == ""){
		$res = ['err'=>1,'msg'=>"Поле < Имя > не заполнено"];
		$save = false;
	}

	if($ArrSave["tz_email"] == ""){
		$res = ['err'=>1,'msg'=>"Поле < E-mail > не заполнено, либо заполненно не корректно"];
		$save = false;
	}

	$sql->sql_connect();
	$result = $sql->sql_query("SELECT * FROM ".$sql->db_prefix."form WHERE tz_email='".$ArrSave["tz_email"]."'");
	if($sql->sql_err){
		$res = ['err'=>1,'msg'=>"Ошибка SQL ".$sql->sql_err];
		$save = false;
	}
	else{
		if($sql->sql_rows($result)){
			$res = ['err'=>1,'msg'=>"Такой e-mail уже зарегистрирован, попробуйте ввести другой"];
			$save = false;
		}
	}

	if($save){
		$sql->sql_ExpandArr($ArrSave);
		if(!$sql->sql_insert("form")){
			$res = ['err'=>1,'msg'=>"Ошибка отправки формы. Попробуйте позже. "];
		}
		else{
			$res = ['err'=>0,'msg'=>"Форма успешно сохранена!"];
		}
	}
	$sql->sql_close();

	$res['data'] = $ArrSave;
	echo json_encode($res);
