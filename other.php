<?php
	class class_sql{
		private $sqlArrSetting = array();
		private $conn_id;
		public $sql_ExpandArr = array();
		public $db_prefix;
		public $sql_err;
		public $sql_QueryString;
		public $sql_insertLastId;
		
		public function __construct(){
			//parent::__construct();
		}
		
		public function sql_set($key, $val){
			// ["login"]		 = "";
			// ["passwd"]		 = "";
			// ["database"]		 = "";
			// ["host"]			 = "localhost";
			// ["db_prefix"]	 = "tbl_";
			// ["codepage"]		 = "utf8";

			$this->sqlArrSetting["MySQL"][$key] = $val;
			if($key == "db_prefix"){
				$this->db_prefix = $val;	
			}
			
		}
		
		//-------------------------------------------------------------------------- 
		// Функция делает соединение с DB 
		//-------------------------------------------------------------------------- 
		// Параметры: 
		// На выходе: возвращает идентификатор соединения 
		//-------------------------------------------------------------------------- 
		public function sql_connect(){
			if($this->conn_id = @mysqli_connect($this->sqlArrSetting["MySQL"]['host'], $this->sqlArrSetting["MySQL"]['login'], $this->sqlArrSetting["MySQL"]['passwd'], $this->sqlArrSetting["MySQL"]['database'])){
				if(!mysqli_query($this->conn_id, "set names ".$this->sqlArrSetting["MySQL"]['codepage']) ||
					!mysqli_query($this->conn_id, "set character_set_client='".$this->sqlArrSetting["MySQL"]['codepage']."'") ||
					!mysqli_query($this->conn_id, "set character_set_results='".$this->sqlArrSetting["MySQL"]['codepage']."'") || 
					!mysqli_query($this->conn_id, "set collation_connection='".$this->sqlArrSetting["MySQL"]['codepage']."_general_ci'")){
					$this->sql_err = @mysqli_error($this->conn_id);
					return(FALSE);
				}
			}
			else{
				$this->sql_err = @mysqli_error($this->conn_id);
				return(FALSE);
			}
			return(TRUE);
		}

		//-------------------------------------------------------------------------- 
		// Функция закрывает соединение с DB 
		//-------------------------------------------------------------------------- 
		// Параметры: 
		// На выходе: 
		//-------------------------------------------------------------------------- 
		public function sql_close(){
			unset($this->sql_QueryString);
			if($this->conn_id){
				if(!mysqli_close($this->conn_id)){
					$this->sql_err = @mysqli_error($this->conn_id);
					return(FALSE);
				}
			}
			return(TRUE);
		}
		
		//-------------------------------------------------------------------------- 
		// Функция выполняет зарос и возвращает результат выполнения
		//-------------------------------------------------------------------------- 
		// Параметры: query - строка запроса 
		// На выходе: result - результат выполения запроса
		//-------------------------------------------------------------------------- 
		public function sql_query($query){
			$this->sql_QueryString = $query;
			
			if(!$this->conn_id){
				$this->sql_err = @mysqli_error($this->conn_id);
				return(FALSE);
			}
			
			if($result = @mysqli_query($this->conn_id, $query)){
				return($result);
			}
			else{
				if($this->sql_err = @mysqli_error($this->conn_id)){}
				return(FALSE);
			}
		}
		
		//-------------------------------------------------------------------------- 
		// Функция возвращает количество строк 
		// из результата выполения функции sql_query()
		//-------------------------------------------------------------------------- 
		// Параметры: query - результат выполения функции sql_query()
		// На выходе: result - количество записей в результате запрса или false
		//-------------------------------------------------------------------------- 
		public function sql_rows($query){
			if($result = @mysqli_num_rows($query)){
				return($result);
			}
			else{
				if($this->sql_err = @mysqli_error($this->conn_id)){}
				return(false);
			}
		}
		//-------------------------------------------------------------------------- 
		// Возвращает ассоциативный массив с названиями индексов, соответсвующими названиям колонок или FALSE если рядов больше нет
		//-------------------------------------------------------------------------- 
		// Параметры: $query - ряд результата запроса 
		// На выходе: $result(array) - ассоциативный массив ряда результата запроса с названиями индексов
		//            false - если возникала ошибка при выполенеии или достигнут конец рядов результата запроса (если вызов в цыкле) 
		//-------------------------------------------------------------------------- 
		public function sql_array($query){
			if($result = @mysqli_fetch_assoc($query)){
				return($result);
			}
			else{
				if($this->sql_err = @mysqli_error($this->conn_id)){}
				return(FALSE);
			}
		}
		
		
		//-------------------------------------------------------------------------- 
		// Функция создания записи в таблице
		// возвращает true, если запрос выполнен успешно и false если произошла ошибка. 
		// в переменную $sql_err будет занесено значение ошибки MySQL
		//-------------------------------------------------------------------------- 
		// Параметры: 	$TableName - название таблицы без префикса
		// На выходе: true/false
		//-------------------------------------------------------------------------- 
		public function sql_insert($TableName){
			$this->sql_insertLastId = 0;
			if($result = $this->sql_query("INSERT INTO `".$this->db_prefix.$TableName."` (".$this->sql_ExpandArr['ListField'].") VALUES(".$this->sql_ExpandArr['ListValue'].")")){
				$this->sql_insertLastId = mysqli_insert_id($this->conn_id);
				$this->sql_ExpandArr = array();
				return(TRUE);
			}
			else{
				$this->sql_err = @mysqli_error($this->conn_id);
				$this->sql_ExpandArr = array();
				return(FALSE);
			}
		}
		
	/*
		// $TableName - название таблицы без префикса
		// $FieldAndValue - строка определение параметров name='".$name."', templ='".$templ."', qwe='123', ...='...'
		// $WhereValue - правильное условие MySQL запроса qwe1='123', qwe2='456', ...='...' 
		// возвращает true, если запрос выполне успешно и false если произошла ошибка. в переменную $sql_err будет занесено значение ошибки MySQL
		public function sql_update($TableName, $WhereValue = ""){
			$result	 = $this->sql_query("UPDATE `".$this->db_prefix.$TableName."` SET ".$this->sql_ExpandArr['FieldAndValue'].(($WhereValue != "")?" WHERE ".$WhereValue:""));
			if(!$result){
				$this->sql_err = @mysqli_error($this->conn_id);
				$this->sql_ExpandArr = array();
				return(FALSE);
			}
			else{
				$this->sql_ExpandArr = array();
				return(TRUE);
			}
		}
*/
		// $TableName - название таблицы без префикса
		// $WhereValue - правильное условие MySQL запроса qwe1='123' and qwe2='456' and ...='...' 
		// возвращает true, если запрос выполне успешно и false если произошла ошибка. в переменную $sql_err будет занесено значение ошибки MySQL
		public function sql_delete($TableName,$WhereValue){
			$result = $this->sql_query("DELETE FROM `".$this->db_prefix.$TableName."` WHERE ".$WhereValue);
			if(!$result){
				$this->sql_err = @mysqli_error($this->conn_id);
				return(FALSE);
			}
			else{
				return(TRUE);
			}
		}


		//-------------------------------------------------------------------------- 
		// Функция разбирает массив для записи в базу данных
		// возвращаем массив из двух строк для дальнейшего использования в функциях sql_insert и sql_update
		//-------------------------------------------------------------------------- 
		// Параметры: arr_data - массив где key имя поля таблици, value - значение которе надо записать
		// На выходе: 	пустой массив (для совместимости), если запрос выполнен успешно 
		// 				и false если произошла ошибка
		// $no_quote = true необходимо для использования выражения в вместо записываемого значения
		//-------------------------------------------------------------------------- 
		public function sql_ExpandArr($arr_data, $no_quote = false){
			$this->sql_ExpandArr = array();
			if(is_array($arr_data)){
				if(count($arr_data) > 0){
					$ArrField			 = array();
					$ArrValue			 = array();
					$ArrFieldAndValue	 = array();
					foreach ($arr_data as $lf => $lv){
						$ArrField[]			 = "`".$lf."`";
						if($no_quote){
							$ArrValue[]			 = "".$lv."";
							$ArrFieldAndValue[]	 = "`".$lf."`=".$lv."";
						}
						else{
							$ArrValue[]			 = "'".$lv."'";
							$ArrFieldAndValue[]	 = "`".$lf."`='".$lv."'";
						}
					}
					$this->sql_ExpandArr['ListField']		 = implode(",",$ArrField);
					$this->sql_ExpandArr['ListValue']		 = implode(",",$ArrValue);
					$this->sql_ExpandArr['FieldAndValue']	 = implode(",",$ArrFieldAndValue);
				}
				return(array());
			}
			else{
				return(FALSE);
			}
		}
	}

	function clear_str($str = ""){
		$str = strip_tags($str);
		$str = preg_replace("/[^a-zA-Zа-яА-Я\s]/u","",$str);
		$str = preg_replace("/ {2,}/", " ", $str);
		$str = trim($str);
		return($str);
	}
	function clear_str_filt($str = ""){
		$str = strip_tags($str);
		$str = preg_replace("/[^a-zA-Zа-яА-Я0-9@\.\s]/u","",$str);
		$str = preg_replace("/ {2,}/", " ", $str);
		$str = trim($str);
		return($str);
	}
	function clear_phone($str = ""){
		$str = strip_tags($str);
		$str = preg_replace("/[^0-9\+\(\)\-\s]/","",$str);
		$str = preg_replace("/ {2,}/", " ", $str);
		$str = trim($str);
		return($str);
	}
	function check_mail($eml){
		if(!preg_match('/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i', $eml))
		{return(false);}
		return(true);
	}	

	function page_get_count($page, $num, $count_on_page){
		$count_on_page = (int)$count_on_page;
		$start = 0;
		if($count_on_page > 0 ){
			if(!isset($page) && intval($page) == 0) {$page = 1;}
			$count_pages = intval($num / $count_on_page);
			$ostatok	 = $num % $count_on_page;
			if($ostatok > 0){ $count_pages++;}
			$start		 = $count_on_page * $page - $count_on_page;
		}
		return($start);
	}
	function page_list_show($page, $num, $count_on_page, $lnk = ''){
		//сколько ссылок на страницы делать
		$diapazon = 6;
		// если количество строк в базе больше чем количество выводимых строк на страницу, то показываем постраничную навигацию
		if($num > $count_on_page){
			//если линк не указан
			if($lnk == ''){$lnk = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];}

			$pBack	 = "#";
			$pList	 = "";
			$pNext	 = "#";
			
			if(!isset($page) && intval($page) == 0){$page = 1;}
			$count_pages = intval($num / $count_on_page);
			$ostatok = $num % $count_on_page;
			if($ostatok > 0) {$count_pages++;}
			if($page > 1){$pBack = $lnk.((isset($_GET['action']))?"&":"?")."pagenum=".($page - 1)."";}
			$page_from	 = $page - $diapazon; 
			if($page_from < 1) $page_from = 1;
			$page_to	 = $page + $diapazon; 
			if($page_to > $count_pages) $page_to = $count_pages;
			for($i = $page_from; $i <= $page_to; $i++){
				if($i == $page) {
					$pList.="<strong>[ ".$i." ]</strong> ";
				}
				else{
					$pList.="<a href=\"".$lnk.((isset($_GET['action']))?"&":"?")."pagenum=".$i."\">[ ".$i." ]</a> ";
				}
			}

			if($page < $count_pages){$pNext = $lnk.((isset($_GET['action']))?"&":"?")."pagenum=".($page + 1)."";}
			
			$return_val = "";
			$return_val .= "<a href=\"".$lnk.((isset($_GET['action']))?"&":"?")."pagenum=1"."\">[ На первую ]</a> ";
			$return_val .= "<a href=\"".$pBack."\">[ На предыдущую ]</a> ";
			$return_val .= $pList;
			$return_val .= "<a href=\"".$pNext."\">[ На следующую ]</a> ";
			$return_val .= "<a href=\"".$lnk.((isset($_GET['action']))?"&":"?")."pagenum=".$count_pages."\">[ На последнюю ]</a> ";

			return($return_val);
		}
	}

	function page_list_show_aj($page, $num, $count_on_page){
		//сколько ссылок на страницы делать
		$diapazon = 6;
		// если количество строк в базе больше чем количество выводимых строк на страницу, то показываем постраничную навигацию
		if($num > $count_on_page){
			$pBack	 = "1";
			$pList	 = "";
			$pNext	 = "1";

			if(!isset($page) && intval($page) == 0){$page = 1;}
			$count_pages = intval($num / $count_on_page);
			$ostatok = $num % $count_on_page;
			if($ostatok > 0) {$count_pages++;}
			if($page > 1){$pBack = $page - 1;}
			$page_from	 = $page - $diapazon;
			if($page_from < 1) $page_from = 1;
			$page_to	 = $page + $diapazon;
			if($page_to > $count_pages) $page_to = $count_pages;
			for($i = $page_from; $i <= $page_to; $i++){
				if($i == $page) {
					$pList.="<strong>[ ".$i." ]</strong> ";
				}
				else{
					$pList.="<a href=\"javascript:void(0);\" onclick=\"list('".$i."');\">[ ".$i." ]</a> ";
				}
			}

			if($page < $count_pages){$pNext = $page + 1;}

			$return_val = "";
			$return_val .= "<a href=\"javascript:void(0);\" onclick=\"list('1');\">[ На первую ]</a> ";
			$return_val .= "<a href=\"javascript:void(0);\" onclick=\"list('".$pBack."');\">[ На предыдущую ]</a> ";
			$return_val .= $pList;
			$return_val .= "<a href=\"javascript:void(0);\" onclick=\"list('".$pNext."');\">[ На следующую ]</a> ";
			$return_val .= "<a href=\"javascript:void(0);\" onclick=\"list('".$count_pages."');\">[ На последнюю ]</a> ";

			return($return_val);
		}
	}
?>