<?
session_start();

 # Соединямся с БД
mysql_connect("localhost","root","");
mysql_select_db("kursach"); 

 # Вывод логина пользователя
$login = $_SESSION['username']; 

 #Выводим ФИО пользователя
$fio = mysql_query("SELECT * FROM users WHERE user_login='".$login."'"); 
$fio_str = mysql_fetch_assoc($fio);

# id пользователя
$id = mysql_result(mysql_query("select user_id from `users` where user_login='$login'"), 0);
$_SESSION['id'] = $id;

# Кнопка выход
if (isset($_POST['close_session']))
	{
		session_destroy(); 
		unset($_SESSION['password']);
		unset($_SESSION['login']); 
		unset($_SESSION['id']);
		header('Location: index.php');
		exit;
	}
	
	 
	
# Удаление пользователя из друзей
?>

<?php
/*
функция для получения названия месяца по-русски
$num_month - номер месяца,
необязательный параметр, если параметр не задан,
то функция вернет название текущего месяца
*/
function getMonthRus($num_month = false) {
// если не задан номер месяца
if(!$num_month) {
// номер текущего месяца
$num_month = date('n');
}
 // массив с названиями месяцев
 $monthes = array(
1 => 'Января' , 2 => 'Февраля' , 3 => 'Марта' ,
4 => 'Апреля' , 5 => 'Мая' , 6 => 'Июня' ,
7 => 'Июля' , 8 => 'Августа' , 9 => 'Сентября' ,
10 => 'Октября' , 11 => 'Ноября' ,
12 => 'Декабря'
);
// получаем название месяца из массива
$name_month = $monthes[$num_month];
// вернем название месяца
 return $name_month;
}
?>


<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

<title>Главная страница</title>
<link href="style/style.css" rel="stylesheet">
<link href="Bootstrap/css/bootstrap.css" rel="stylesheet">
<script type="text/javascript" src="js/up.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>
</head>
<body>
   <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
   <!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="Bootstrap/js/bootstrap.js"></script>

<div class="body-style">
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2 row-container">
				<div class="row footer-mes">
					<div class="col-sm-4 col-lg-4">
						<div class="navbar navbar-left">
							<p class="navbar-text">Пользователь: <b>
																	<? 
																		echo $login 
																	?>
																</b><br> 
																	(<? 
																		echo $fio_str['user_family']." ".$fio_str['user_name']." ".$fio_str['user_patronymic'];  
																	?>)
							</p>
						</div>
					</div>	
					<div class="col-sm-6 col-lg-6">
					<div class="row row-block">
						<div class="navbar-form">
							<div class="row">
								<form method="POST" >
									
							<div class="form-group col-xs-12">
									<div class="control-group required">
										<div class="controls">
											<input name="log" type="text" class="form-control" placeholder="Поиск друзей...">
										</div>
									</div>
										<p>
										<?# поиск пользователей
												if ((isset($_POST['poisk'])) && ($_POST['log'] !== ''))
												{
													$dia = mysql_query("SELECT user_login FROM users WHERE user_login='".mysql_real_escape_string($_POST['log'])."' LIMIT 1");
													$dial = mysql_fetch_assoc($dia);
														if (isset($dial['user_login'])) 
														{									
															
															echo " Найдено: ".$dial['user_login'];	
															$_SESSION['user_log'] = $dial['user_login'];			
            		
														}					     
														else
															echo "Такого пользователя нет!";
												}
												?>
												<?
												#добавление пользователя в свои друзья
												if (isset($_POST['add']))  
												{
												# Находим id нового друга
													$query = mysql_query("SELECT user_id, user_login FROM users WHERE user_login='".mysql_real_escape_string($_SESSION['user_log'])."' LIMIT 1");
													$data = mysql_fetch_assoc($query);
		
													# проверка на наличие этого пользователя в друзьях
													$mas = mysql_query("SELECT COUNT(id_to) FROM friends WHERE (id_to='".mysql_real_escape_string($data['user_id'])."') AND (id_from='".$_SESSION['id']."') LIMIT 1");
		
													//если нет найденного пользователя в друзьях, то добавляем его, иначе выводим сообщение об ошибке
													if(mysql_result($mas, 0) == 0) 
													{
														# Записываем пользователя в бд
														mysql_query("INSERT INTO friends SET id_from='".$_SESSION['id']."',login_from='".$login."',login_to='".$_SESSION['user_log']."',id_to='".$data['user_id']."'  ");
														echo " Пользователь ".$_SESSION['user_log']." успешно добавлен!";													
													} 
													else
													{
														echo "Такой пользователь уже имеется в ваших друзьях!";
													}		
												}
												?>
										</p>
							</div>
										<div class="form-group col-xs-6">
											<button type="submit" name="poisk" class="btn btn-primary btn-sm btn-block">Найти</button>
										</div>	
										<div class="form-group col-xs-6">
											<button name="add" type="submit" class="btn btn-primary btn-sm btn-block">Добавить</button>
										</div>									
								</form>							
							</div>								
						</div>
					</div>
					</div>

					<div class="col-sm-2 col-lg-2">
						<form method="POST" class="navbar-form navbar-right">			
							<button name="close_session"  type="submit" class="btn btn-primary btn-md"><span class="glyphicon glyphicon-log-out"></span> Выход</button>				
						</form>	
					</div>
				</div>
				<div class="row">
					<div class="col-md-9">
						<h3 class="text-center"><?php if (isset($_GET['id'])) echo "Диалог"; else echo "Список друзей";?></h3>
						
							
								<!--<textarea type="text" cols="15" rows="10">-->
								<?    
								
								
								if (isset($_GET['id']))
								{
									?><textarea type="text" class="form-control" rows="15" readonly="readonly" id="text_old"><?
									$query = "(SELECT id_to,id_from,text,time FROM `messages` WHERE (id_from='".$id."' AND id_to='".$_GET['id']."') OR (id_from='".$_GET['id']."' AND id_to='".$id."'))";
									$res = mysql_query($query);
									$a = mysql_fetch_assoc($res);
									//echo $a['text'];
									//echo $a['id_from'];
									//echo $_GET['id'];
									while($row = mysql_fetch_array($res))
									{ 
										$messag =mysql_query("SELECT user_name,user_family FROM users WHERE user_id='".$row['id_from']."'");
										$m = mysql_fetch_assoc($messag);
										//echo $m['user_name'];
										//echo "$row[id_from] -> $row[id_to]\n $row[text]\n";
										$d = explode(' ',$row['time']);
										$dd = explode(':',$d[1]);			
										$zz = explode('-',$d[1]);
										//echo $row['time'];
										$time = $dd[0].":".$dd[1].":".$dd[3]."(".$zz[0]."-".$zz[1].")";										
										//echo "$m[user_name] $m[user_family]   $time\n";
										//echo "$row[text]\n\n";
										$from = $row['id_from'];
										$to = $row['id_to'];
										
										
									}	
									
									if((isset($_POST['send'])) and ($_POST['text_to'] !== ''))
									{
										$text = $_POST['text_to'];										
										$date = date("Y-m-d H:i:s");
										mysql_query("INSERT INTO messages SET id_from='".$id."', id_to='".$_GET['id']."', text='".$text."', time='".$date."'");
										
										$query = "SELECT text,time FROM `messages` WHERE id='".mysql_insert_id()."'";
		
											
											/*$query = "SELECT text,time FROM `messages` WHERE id='".mysql_insert_id()."'";
											
											$res = mysql_query($query);
												while($row = mysql_fetch_array($res))
												{
													
													//printf("Идентификатор последней вставленной записи %d\n", mysql_insert_id());
													echo "Текст: ".$row['text']."<br>\n";
													echo "Время: ".$row['time']."<br>\n";
												}*/
									}
									
									$query = "(SELECT id_to,id_from,text,time FROM `messages` WHERE (id_from='".$id."' AND id_to='".$_GET['id']."') OR (id_from='".$_GET['id']."' AND id_to='".$id."'))";
									$res = mysql_query($query);	
									while($row = mysql_fetch_array($res))
									{ 
										$messag =mysql_query("SELECT user_name,user_family FROM users WHERE user_id='".$row['id_from']."'");
										$m = mysql_fetch_assoc($messag);
										//echo "$row[id_from] -> $row[id_to]\n $row[text]\n";
										$d = explode(' ',$row['time']);
										$dd = explode(':',$d[1]);			
										$zz = explode('-',$d[0]);
										//echo $row['time'];
										$time = $dd[0].":".$dd[1].":".$dd[2]." (".$zz[2]." ".getMonthRus($zz[1])." ".$zz[0].")";									
										echo "$m[user_name] $m[user_family]   $time\n";
										echo "$row[text]\n\n";
										$from = $row['id_from'];
										$to = $row['id_to'];
										
									}	
									//echo '<form id="form_1" style="display:none;">';
									# Скрываем форму для удаления
									
									?>
									</textarea></br>
									<form method="POST">
										<textarea name="text_to" type="text" class="form-control" rows="1" autofocus placeholder="Введите ваше сообщение..." onkeydown="keyDown(event)" onkeyup="keyUp(event)"></textarea></br>									
										<div class="form-group">
											<button name="send" id="text_enter" type="submit" class="btn btn-primary btn-md btn-block">Отправить</button>
										</div>	
									</form>
									<?
									
								} 
								else # иначе показываем список друзей 
								{
									?><div class="list-group"><?
									//echo '<form style="display:block;">'; 
									 # Список друзей
									//echo "Список друзей".'</br>';
									$query = "SELECT login_to, id_to FROM `friends` WHERE login_from='".mysql_real_escape_string($login)."'";
									$res = mysql_query($query);
									$k = 0;
								   // while($row = mysql_fetch_array($res))
									//{ 
										//$k++;
										//echo " <a href='user.php?id=".$row['id_to']."'>".$row['login_to']."</a> "; //<input type='image' src='images/delete.png' height='12' ><br>\n"; 
									//}				

								
								
									while($row = mysql_fetch_array($res))
										{ 												
											# ФИО пользователя
											$st = mysql_query("SELECT * FROM `users` WHERE user_login='".$row['login_to']."'");
											$str = mysql_fetch_assoc($st);
											
											# Поиск последнего сообщения с пользователем
											$meseg = mysql_query("SELECT text,time FROM messages WHERE id=(SELECT max(id) FROM `messages` WHERE (id_from='".$id."') AND (id_to='".$row['id_to']."'))");
											$mes = mysql_fetch_assoc($meseg);
											
											# Если есть сообщения, то выводим их со временем, иначе выводим "нет сообщений"
											if (isset($mes['text']))
											{
												$d = explode(' ',$mes['time']);
												$dd = explode(':',$d[1]);											
												$time = $dd[0].":".$dd[1].":".$dd[2]; 
												$dialog = $mes['text'];
											}
											else 
											{
												$time = "";
												$dialog = "Нет сообщений";
											}
											

											//echo $mes['text'];
												
											# Вывод сообщений с каждым пользователем
											echo "<div class='list-group-item'>													
														<a href='messages.php?id=".$row['id_to']."'>".$row['login_to']." ".$str['user_name']." ".$str['user_family']."</a>														
														<br>
														".$dialog."<div style='float:right;'> ".$time."</div>														
												  </div>";
																						
										} 
										?>
									</div><?
								}
								?>
								
								
								<!--</div>-->
							
					</div>
					<!--Если выводим списко друзей, то удаление показываем, а если выводим диалог, то нет-->
					<div class="col-md-3" style="display:<?php if (isset($_GET['id'])) echo "none"; else echo "block";?>">
						<form method="POST">
							<h3 class="text-center">Удалить</h3>
							<div class="form-group">
							<select name="friend" class="form-control">
								<?
									$query = "SELECT login_to FROM `friends` WHERE login_from='".$login."'";
									$res = mysql_query($query);
									while($row = mysql_fetch_array($res))
									{
										echo "<option>$row[login_to]</option>";
									}
								?>
							</select>
							</div>
							<div class="form-group" id="del">
								<button name="delete" type="submit" class="btn btn-primary btn-md btn-block">Удалить</button>
							</div>
							<?
							if(isset($_POST['friend']))
								//if (isset($_POST['delete']))
								{
									mysql_query("DELETE FROM friends WHERE login_from='".$login."' AND login_to='".$_POST['friend']."'");
									echo "Удаление прошло успещно!";
								}
							
							?>
						</form>	
					</div>
					<div class="col-md-3" style="display:<?php if (isset($_GET['id'])) echo "block"; else echo "none";?>">
						<form method="POST">
							<div class="form-group" id="naz"><br>
								<button name="nazad" type="button" class="btn btn-primary btn-md btn-block" onClick='location.href="messages.php"'>Назад</button>
							</div>
						
						</form>	
					</div>					
			</div>	
		</div>
	</div>
</div>
<!--<script> 
           function show(divid){
             if(document.getElementById(divid).style.display=="none"){
              document.getElementById(divid).style.display="inline";
             }
            else{
              document.getElementById(divid).style.display="none";
            }
          }
        </script>

<div id="win" style="display:none;">
   <div class="overlay"></div>
      <div class="visible">
        <h2><center><b>Консультация</b></center></h2>
          <div class="content">
            <p><center>График проведения консультаций специалиста профконсультанта-психолога</center></p>
            <p><center>ВТОРНИК - ЧЕТВЕРГ С 16.30-17.30</center></p>
			<p><img src="index/images/skype.png" width="30px"> <b>Логин:</b> ubt_bashzan</p>
          </div>
        <button type="button" onClick="getElementById('win').style.display='none';">закрыть</button>
    </div>
</div>
-->
<script>
var show;
	function hidetxt(type){
		param=document.getElementById(type);
			if (param.style.display == "none") 
			{
				if(show)
					show.style.display = "none";
				param.style.display = "block";
				show = param;
			}
			else param.style.display = "none"
} 
</script>

<script>
<!-- Прокручиваем диалог в конец-->
document.getElementById("text_old").scrollTop=document.getElementById("text_old").scrollHeight;
</script>



<script>
//-----------------Cкрипт для отправки сообщения кнопкой Enter-------------------------
ctrl = false;
function keyDown(e){ // В качестве параметра будем передавать объект event
	if (e.keyCode == 16)	// Если нажата клавиша Shift, то присваиваем соответсвующей переменной значение true;
		ctrl = true;
	else if (e.keyCode == 13 && ctrl == false) // Если пользователь не держит нажатой клавишу Shift и нажмет enter
		document.getElementById("text_enter").click(); // Имитируем щелчёк по кнопке с id="go"
}
function keyUp(e){
	if (e.keyCode == 16) // Если пользователь отпускает Shift
		ctrl = false;
}
</script>

</body>
</html>