<?php 
	include("config.php");
?>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="#">
		<title>Список записей</title>
		<link href="theme/bootstrap.min.css" rel="stylesheet">
		<script src="theme/jquery-3.3.1.min.js"></script>

        <script type="text/javascript" language="javascript">
            function list(pagenum) {
                if(!pagenum){
                    pagenum = 1;
                }

                $.ajax({
                    type: 'POST',
                    url: 'list.php?pagenum=' + pagenum,
                    data: {
                        tz_name: $('#tz-name').val(),
                        tz_phone: $('#tz-phone').val(),
                        tz_email: $('#tz-email').val()
                    },
                    success: function(data) {
                        $('#results').html(data);
                    },
                    error:  function(xhr, str){
                        alert('Возникла ошибка: ' + xhr.responseCode);
                    }
                });
            }
            function clr(){
                $('.f-val').val('');
                list();
            }
            function del(did) {
                if(!did){
                    alert('Не выбрана запись для удаления');
                }
                $.ajax({
                    type: 'POST',
                    url: 'del.php',
                    data: {
                        id: did
                    },
                    success: function(data) {
                        // обновляем список
                        list();
                        alert(data);
                    },
                    error:  function(xhr, str){
                        alert('Возникла ошибка: ' + xhr.responseCode);
                    }
                });
            }

            $(document).ready(function() {
                list();
            });
        </script>

	</head>
	<body>
		
		<div class="container">
			<h1>Список записей</h1>
			<div class="row">
				<div class="col-xs-2"><a href="index.php">Вернуться к форме</a></div>
			</div>
		</div>
		
		<div class="container">
			<div class="row">
				<div class="col-xs-12">

                    <form id="tz-form" method="post" data-toggle="validator" action="javascript:void(0);">
                        <div class="col-xs-4 form-group ">
                            <label for="tz-name">Имя</label>
                            <input type="text" name="tz-name" id="tz-name" class="form-control f-val" value="" >
                            <span class="help-block with-errors"></span>
                        </div>
                        <div class="col-xs-4 form-group">
                            <label for="tz-phone">Телефон</label>
                            <input type="tel" name="tz-phone" id="tz-phone" class="form-control bfh-phone f-val" value="" data-format="+7 (ddd) ddd-dddd"  pattern="(\+[\d\ \(\)\-]{16})" />
                        </div>
                        <div class="col-xs-4 form-group">
                            <label for="tz-email">E-mail</label>
                            <input type="email" name="tz-email" id="tz-email" class="form-control f-val" value=""  >
                            <span class="help-block with-errors"></span>
                        </div>

                        <div class="col-xs-12">
                        <button type="button" class="btn btn-default" onclick="list();">Найти</button>
                        <button type="button" class="btn btn-default" onclick="clr();">Сброс</button>
                        </div>
                    </form>

				</div>
                <div class="col-xs-12" id="results">
                    <?php
                        //include('list.php');
                    ?>
				</div>
			</div>
		</div>	

		<script src="theme/bootstrap.min.js"></script>
	</body>
</html>		