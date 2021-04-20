<?php
$login= trim(filter_var($_POST['login'], FILTER_SANITIZE_STRING));
$pass= trim(filter_var($_POST['password'], FILTER_SANITIZE_STRING));


$error='';
if (strlen($login) <= 3)
  $error ='Введите логин';
else if (strlen($pass) <= 3)
  $error ='Введите пароль';
if ($error !='') {
  echo $error;
  exit();
}

  include 'connectdb.php';
//Авторизация
  $sql='SELECT `id` FROM `users` WHERE `login` = :login AND `pass` = :pass ';
  $query = $pdo->prepare($sql);
  $query -> execute([':login'=>$login, ':pass'=>$pass]);
  $users = $query-> fetch(PDO::FETCH_OBJ);

if($users->id == 0)
  echo 'Пользователя нет';
else {
  setcookie('login', $login, time() +3600 * 24 *30 , "/");
  echo 'успех';
}


 ?>
