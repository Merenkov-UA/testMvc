<!DOCTYPE html />
<html>
<head>

<link rel="stylesheet" href="/test1/public/css//bootstrap.min.css" />
<link rel="stylesheet" href="/test1/public/css/style.css" />
</head>

<body class="text-center">

<header>

<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
  <h5 class="my-0 mr-md-auto font-weight-normal " >Домашняя бухгалтерия </h5>
  <button class="btn btn- btn-primary  mt-1 mainBtn" type="button" ><a href="/test1" >На главную</a></button>
</div>



<h1 >Регистрация нового пользователя</h1>
<form method="POST" action="registration"  enctype="multipart/form-data" >

<div class="container myContainer">
<div class="input-group mb-1 myInput">
  <div class="input-group-prepend">
    <span class="input-group-text ">Логин:</span>
  </div>
  <input type="text" class="form-control" name="login" id="login_inp" value="<?=$_SESSION[ 'login' ] ?? ''?>">
  
  <input type="button" onclick="checkLogin()" value="Check for free" />
  <i id="logCheckResult"></i>
</div>
</div>



<div class="container myContainer">
<div class="input-group mb-1 myInput">
  <div class="input-group-prepend">
    <span class="input-group-text ">Имя:</span>
  </div>
  <input type="text" class="form-control" name="name" value="<?=$_SESSION[ 'name' ] ?? ''?>">
  </div>
</div>


<div class="container myContainer" >
<div class="input-group mb-1 myInput" >
  <div class="input-group-prepend">
    <span class="input-group-text ">Пароль:</span>
  </div>
  <input type="password" class="form-control" name='pass'>
  
</div>
</div>

<div class="container myContainer">
<div class="input-group mb-1 myInput">
  <div class="input-group-prepend">
    <span class="input-group-text ">Ещё раз:</span>
  </div>
  <input type="password" class="form-control" name='pass2'>
  
</div>
</div>

<div class="container myContainer">
<div class="input-group mb-1 myInput">
  <div class="input-group-prepend">
    <span class="input-group-text ">Емаил:</span>
  </div>
  <input type="email" class="form-control" name="email" value="<?=$_SESSION[ 'email' ] ?? ''?>">
  
</div>
</div>

<div class="container myContainer">
<div class="input-group mb-1 myInput">
  <div class="input-group-prepend">
    <span class="input-group-text ">Баланс:</span>
  </div>
  <input type="number" step="any" min="0.01" class="form-control" name="balance" value="<?=$_SESSION[ 'balance' ] ?? ''?>">
  
</div>
</div>



<input type="submit" class="btn btn-primary" value="Регистрация" />

</form>


<div  id="messenger" class="alert alert-info" role="alert">
    <?=$msg ?? ''?>
</div>

<script>
checkLogin = ()=>{
	var logTxt = login_inp.value;
	var x = new XMLHttpRequest();
	x.onreadystatechange = ()=>{
		if(x.readyState == 4) {
			var res = JSON.parse(x.responseText);
			switch(res['status']){
				case 1:
					logCheckResult.innerText = "Свободен";
					break;
				case -1:
					logCheckResult.innerText = "Логин не может быть пустым";
					break;
				case -2:
					logCheckResult.innerText = "Логин содержит недопустимые символы";
					break;
				case -4:
					logCheckResult.innerText = "Логин занят";
					break;
				default:
					logCheckResult.innerText = "Что-то пошло не так..."
			}
		}
	}
	x.open("GET","user?login=" + logTxt, true);
	x.send(null);	
}


</script>

<script src="/test1/public/js/jquery-3.4.1.min.js"></script>

<script src="/test1/public/js/bootstrap.bundle.min.js"></script>
</body>
</html>