<?php 
	// форма обратной связи
	include("config.php");
?>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="#">
		<title>Тестовое задание</title>
		<link href="theme/bootstrap.min.css" rel="stylesheet">
		<script src="theme/jquery-3.3.1.min.js"></script>
		<script src="theme/bootstrap-formhelpers-phone.js"></script>
		<script src="theme/validator.js"></script>
		<style>
			.form-group label{
				font-size:18px;
			}
			.tz-req-field,
			.help-block{
				color: red;
				font-size:14px;
			}
			.tz-info{
				font-size:14px;
			}
			#tz-date,
			#tz-time{
				width: 180px;
				float: left;
				margin-right: 15px;
			}
			.tz-results{
				display: none;
			}

		</style>
		<script type="text/javascript" language="javascript">
			$(document).ready(function() {

			});
			function snd() {
				var msg   = $('#tz-form').serialize();
				$.ajax({
					type: 'POST',
					url: 'send.php',
					data: msg,
					success: function(data) {
						$('.tz-form').hide();
						$('.tz-results').show();

						//$('#results').html(data);
                        var obj = JSON.parse(data);
                        // если ошибки нет
                        if(obj.err == 0){
                            $('.f-val').val('');
                        }
                        $('#results').html(obj.msg);
					},
					error:  function(xhr, str){
						alert('Возникла ошибка: ' + xhr.responseCode);
					}
				});
			}
		</script>
	</head>
	<body>

		<div class="container tz-form">
			<h1>Тестовое задание:</h1>
			<div class="row">
				<div class="col-xs-12">
<pre> 
Нужно создать форму записи клиента состоящую из 3х полей : имя, телефон, мейл.
А также нужно создать страницу со списком всех записаных клиентов и с возможностью фильтровать по всем 3м полям. Также нужно создать возможность удаление клиентов.
Нужно сделать без использования фрейм-ворков, на чистом php (на фронте использование фреймворков - плюс).
То как будет выглядеть визуально интерфейс - не принципиально.
</pre> 
				</div>				<div class="col-xs-12">
					<p class="tz-req-field">* - обязательные поля</p>
				</div>
				<div class="col-xs-12">

					<form id="tz-form" method="post" data-toggle="validator" action="javascript:void(0);">
						<div class="form-group">
							<label for="tz-name">Имя <span class="tz-req-field">*</span></label>
							<input type="text" name="tz-name" id="tz-name" class="form-control f-val" value="" data-required-error="Поле не заполнено" required >
							<span class="help-block with-errors"></span>
						</div>
						<div class="form-group">
							<label for="tz-phone">Телефон</label>
							<input type="tel" name="tz-phone" id="tz-phone" class="form-control bfh-phone f-val" value="" data-format="+7 (ddd) ddd-dddd"  pattern="(\+[\d\ \(\)\-]{16})" />
						</div>
						<div class="form-group">
							<label for="tz-email">E-mail <span class="tz-req-field">*</span></label>
							<input type="email" name="tz-email" id="tz-email" class="form-control f-val" value="" data-required-error="Поле не заполнено" required >
							<span class="help-block with-errors"></span>
						</div>

						<button type="submit" class="btn btn-default" onclick="$('tz-form').off('submit');snd();">Отправить</button>
					</form>
					
				</div>
			</div>
		</div>	
		
		<div class="container tz-results">
			<h1>Отправка формы</h1>
			<div class="row">
				<div class="col-xs-12" id="results"></div>
				<div class="col-xs-12">
                    <button type="button" class="btn btn-default" onclick="$('.tz-form').show();$('.tz-results').hide();">Повторить ввод</button>
                </div>
			</div>
		</div>	
		<div class="container">
			<div class="row">
				<div class="col-xs-12">Для примера <a href="adm.php">Список записей</a></div>
			</div>
		</div>	
			<div class="container">
			<div class="row">
				<div class="col-xs-12"><a href="tz2.zip">Архив скрипта</a></div>
			</div>
		</div>

		<script src="theme/bootstrap.min.js"></script>
	</body>
</html>				