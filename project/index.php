<? 
session_start();
$s = ''
?>

<?



// Страница авторизации



# Функция для генерации случайной строки

function generateCode($length=6) {

    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";

    $code = "";

    $clen = strlen($chars) - 1;  
    while (strlen($code) < $length) {

            $code .= $chars[mt_rand(0,$clen)];  
    }

    return $code;

}



# Соединямся с БД

mysql_connect("localhost","root","");

mysql_select_db("kursach");


if(isset($_POST['submit']))

{

    # Вытаскиваем из БД запись, у которой логин равняеться введенному

    $query = mysql_query("SELECT user_id, user_password FROM users WHERE user_login='".mysql_real_escape_string($_POST['login'])."' LIMIT 1");

    $data = mysql_fetch_assoc($query);

    

    # Сравниваем пароли

    if($data['user_password'] === md5(md5($_POST['password'])))

    {

        # Генерируем случайное число и шифруем его

        $hash = md5(generateCode(10));
		
		# Запоминаем логин
		
		$_SESSION['username'] = $_POST['login'];

            

      /*  if(!@$_POST['not_attach_ip'])

        {

            # Если пользователя выбрал привязку к IP

            # Переводим IP в строку

            $insip = ", user_ip=INET_ATON('".$_SERVER['REMOTE_ADDR']."')";

        }*/

        

        # Записываем в БД новый хеш авторизации и IP

       // mysql_query("UPDATE users SET user_hash='".$hash."' ".$insip." WHERE user_id='".$data['user_id']."'");

        

        # Ставим куки

        //setcookie("id", $data['user_id'], time()+60*60*24*30);

        //setcookie("hash", $hash, time()+60*60*24*30);

        

        # Переадресовываем браузер на страницу проверки нашего скрипта

        //header("Location: check.php"); exit();
		//echo "Ура";
		//echo '<div class="header"><span>Авторизация прошла успешно! Подождите ...</span></div>';
		$s = '<h3 class="text-center" style="font-size:18px; color:green;">Авторизация прошла успешно! Подождите ...</h3>';
		//echo '<script>window.location.href = "reg.php";</script>';
		?>
		
		<script>					
		function func() {
        window.location.href = "messages.php";
        }
		setTimeout(func, 2000);
		</script>
		<?

    }

    else

    {
        $s = '<h3 class="text-center" style="font-size:18px; color:red;">Вы ввели неправильный логин или пароль</h3>';
        //print "Вы ввели неправильный логин/пароль";

    }

}

?>


<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

<title>Информационная система НфБашГУ</title>
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
      <div class="col-xs-4 col-xs-offset-4 col-sm-4 col-sm-offset-4 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4 col-index">
		<form method="POST">	    
			<div class="row row-header-index">
				<div class="jumbotron-header">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">							
						<div class="col-sx-10 col-sx-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 vertical-center">
							<h1 class="text-center" style="font-size:38px; color:#2166A3;"><strong>Авторизация</strong></h1>
						</div>	
					</div>	
				</div>
			</div>	
			
			<div class="row row-center-index">
				<div class="jumbotron-center col-xs-12">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">							
						<div class="col-md-10 col-md-offset-1">														
							<div class="form-group">
								<input name="login" type="text" class="form-control input-lg" id="exampleInputEmail1" placeholder="Логин">
								<span class="help-block"><? echo $s; ?></span>
							</div>	
							<div class="form-group">							
								<input name="password" type="password" class="form-control input-lg" id="exampleInputPassword1" placeholder="Пароль">							
							</div>						
						</div>									
		            </div>	
				</div>
			</div>

	    <div class="row row-footer-index">
			<div class="jumbotron-footer">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">				
					<div class="form button-center navbar-form">
						<div class="col-md-3 col-md-offset-2 ">
							<div class="form-group">
								<button name="submit" type="submit" class="btn btn-primary btn-lg">Вход</button>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<button name="submit" type="button" class="btn btn-primary btn-lg" onClick='location.href="register.php"'>Регистрация</button>
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

</body>
</html>