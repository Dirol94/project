<?

// Страница регситрации нового пользователя



# Соединямся с БД

mysql_connect("localhost","root","");

mysql_select_db("kursach");



if(isset($_POST['submit']))

{

    $err = array();
	$s = array();
	$s[] = '';


    # проверям логин

    if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))

    {

        $err[] = "Логин может состоять только из букв английского алфавита и цифр";

    }

    

    if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30)

    {

        $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";

    }
	
	if (($_POST['password'] == "") and ($_POST['group'] == ""))

	{
		$err[] = "Введите пароль и группу!";
	}

	if (($_POST['password'] == "") and ($_POST['group'] !== ""))

	{
		$err[] = "Введите пароль!";
	}

    

    # проверяем, не сущестует ли пользователя с таким именем

    $query = mysql_query("SELECT COUNT(user_id) FROM users WHERE user_login='".mysql_real_escape_string($_POST['login'])."'");

    if(mysql_result($query, 0) > 0)

    {

        $err[] = "Пользователь с таким логином уже существует в базе данных";

    }

    

    # Если нет ошибок, то добавляем в БД нового пользователя

    if(count($err) == 0)

    {

        
        $login = $_POST['login'];

        

        # Убераем лишние пробелы и делаем двойное шифрование

        $password = md5(md5(trim($_POST['password'])));
		
		$group = $_POST['group'];
		
		$position = $_POST['position'];
		
		$name = $_POST['user_name'];
		
		$family = $_POST['user_family'];
		
		$patronymic = $_POST['user_patronymic'];
		

        

        mysql_query("INSERT INTO users SET user_login='".$login."', user_password='".$password."', user_group='".$group."', user_position='".$position."', user_name='".$name."', user_family='".$family."', user_patronymic='".$patronymic."'");

        header("Location: index.php"); exit();

    }

    else

    {

        

        foreach($err AS $error)

        {
			//$s[] = $error."<br>";

        }

    }

}

?>






<html lang="ru">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>Регистрация</title>
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

<div class="container-fluid" style="height:100%;">
   <div class="row-fluid">
      <div class="col-xs-6 col-xs-offset-3 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-reg">
		<form method="POST">	    
			<div class="row row-header-reg">
				<div class="jumbotron-header col-xs-12">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">							
						<div class="col-md-10 col-md-offset-1">
							<h1 class="text-center" style="font-size:38px; color:#2166A3;"><strong>Регистрация</strong></h1>
						</div>	
					</div>	
				</div>
			</div>	
			
			<div class="row row-center-reg">
				<div class="jumbotron-center col-xs-12">
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">																				
							<div class="form-group">
								<input name="user_name" type="text" class="form-control input-lg" id="reg-family" placeholder="Фамилия">
							</div>
							<div class="form-group">
								<input name="user_family" type="text" class="form-control input-lg" id="reg-name" placeholder="Имя">
							</div>
							<div class="form-group">
								<input name="user_patronymic" type="text" class="form-control input-lg" id="reg-patronymic" placeholder="Отчество">
							</div>	
							<span class="help-block">
								<? if (isset($err)) foreach($err AS $error)
									{
										echo $error."<br>";
									} ?>
							</span>															
		            </div>	
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">																				
							<div class="form-group">
								<input name="login" type="text" class="form-control input-lg" id="reg-login" placeholder="Логин">
							</div>	
							<div class="form-group">							
								<input name="password" type="password" class="form-control input-lg" id="reg-password" placeholder="Пароль">							
							</div>	
							<div class="form-group">	
							
								<select class="form-control input-lg" name="position" onchange="showhideBlocks(this.value)">
									<option name="teacher" value="Преподаватель">Преподаватель</option>
									<option name="student" value="Студент">Студент</option>
								</select>
						
							</div>
							<div class="form-group" id="i1" style="display:none;">							
								<input name="group" type="text" class="form-control input-lg" id="reg-group" placeholder="Группа">							
							</div>															
		            </div>					
				</div>
			</div>

	    <div class="row row-footer-reg">
			<div class="jumbotron-footer">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">				
					<div class="form button-center navbar-form">
						<div class="col-md-6 col-md-offset-3">
							<div class="form-group">
								<button name="submit" type="submit" class="btn btn-primary btn-lg pull-right center-block">Зарегистрироваться</button>
							</div>
						</div>
					</div>
			    </div>
			</div>
        </div>	
    </form>
      </div>
   </div>
</div>
</div>



<!--
<div id="wrapper">
    <div class="user-icon"></div>
    <div class="pass-icon"></div>
	<div class="group-icon"></div>
	
<form name="login-form" class="login-form" method="POST">

    <div class="header">
		<h1>Регистрация</h1>
    </div>

    <div class="content">
		<input name="login" type="text" class="input username" value="Логин" onfocus="this.value=''" />
		<input name="password" type="password" class="input password" value="Пароль" onfocus="this.value=''" />
		<input name="group" type="text" class="input group" value="Группа" onfocus="this.value=''" />
		    <select class="input position" name="position">
		        <option name="teacher" value="Преподаватель">Преподаватель</option>
			    <option name="student" value="Студент">Студент</option>
		    </select>
    </div>

    <div class="footer">
		<input type="submit" name="submit" value="Зарегистрироваться" class="button" />
		<!--<input type="submit" name="submit" value="Регистрация" class="register" />-->
<!--    </div>

</form>
</div>
<div class="gradient"></div>
<script type="text/javascript">
$(document).ready(function() {
	$(".username").focus(function() {
		$(".user-icon").css("left","-48px");
	});
	$(".username").blur(function() {
		$(".user-icon").css("left","0px");
	});
	
	$(".password").focus(function() {
		$(".pass-icon").css("left","-48px");
	});
	$(".password").blur(function() {
		$(".pass-icon").css("left","0px");
	});
	
	$(".group").focus(function() {
		$(".group-icon").css("left","-48px");
	});
	$(".group").blur(function() {
		$(".group-icon").css("left","0px");
	});
});
</script>
-->
						<script type='text/javascript'>
								<!-- 
								function showhideBlocks(val){
										if (val == 'Преподаватель'){
											document.getElementById('i1').style.display='none';                                     
										}
										else{
										   document.getElementById('i1').style.display='block';    
										   //document.getElementById('i'+val).style.display='block';  
										}  
								}
								-->
						</script>
</body>
</html>