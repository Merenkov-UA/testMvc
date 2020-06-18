<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
  <h5 class="my-0 mr-md-auto font-weight-normal " >Домашний бухгалтерия </h5>
  <button class="btn btn- btn-primary  mt-1 mainBtn" type="button" ><a href="/test" >На главную</a></button>
</div>
</header>
</form>
<div class="container w-50 text-center ">
<form class="form-signin" method="POST" action="authorization">
      
      <h1 class="h3 mb-3 font-weight-normal">Пожалуйста авторизируйтесь</h1>
      <label for="inputLogin" class="sr-only">Логин</label>
      <input type="text" id="inputLogin" name="login" class="form-control" placeholder="Логин" required="" autofocus="">
      <label for="inputPassword" class="sr-only">Логин</label>
      <input type="password"  id="inputPassword" name="password" class="form-control mt-1" placeholder="Пароль" required="">
     
      <button class="btn btn-lg btn-primary btn-block mt-2" type="submit">Sign in</button>
     
    </form>
  </div>

  <div  id="messenger" class="alert alert-info" role="alert">
    <?=$msg ?? ''?>
</div>