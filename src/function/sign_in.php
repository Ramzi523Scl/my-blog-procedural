<?php

// Настройки подключения
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_STRINGIFY_FETCHES => false,
    PDO::ATTR_EMULATE_PREPARES => false
];
$pdo = new PDO('mysql:host=localhost;dbname=my_blog', 'root', '', $options);
//include("../db_config.php");


session_start();

// Получение введенных данных для входа
$_SESSION['user']['isInfo'] = false;
$nickname = $_POST['nickname'];
$password = $_POST['password'];

// Проверка на пустату полей, если пустые просит заполнить заново
$is_empty = ($nickname === '') || ($password === '');

if($is_empty) {
    $_SESSION['error'] = 'Заполните пустые Поля';
    header('Location: /src/pages/sign_in.php');
    exit();
}

// Запрос на получение данных входа, по введенному нику
$sql = 'SELECT * FROM users_login WHERE nickname=:nick';
$statement = $pdo->prepare($sql);
$statement->execute(['nick' => $nickname]);
$user = $statement->fetch(PDO::FETCH_ASSOC);

// Если пользователя не удалось найти по данному нику, или пароль введен не верно, просьба повторить попытку
$is_auth = (!empty($user)) && password_verify($password, $user['password']);
if(!$is_auth) {
    $_SESSION['error'] = 'Логин или пароль введены неверно';
    header('Location: /src/pages/sign_in.php');
    exit();
}

// Запись в сессию некоторых данных входа
$_SESSION['user']['isAuth'] = true;
$_SESSION['user']['id'] = $user['id'];
$_SESSION['user']['email'] = $user['email'];
$_SESSION['user']['nick'] = $user['nickname'];

// Получение информации о данном пользователе
$sql = "SELECT * FROM users_info WHERE users_login_id=:id";
$statement = $pdo->prepare($sql);
$statement->execute(['id' => $user['id']]);
$user = $statement->fetch(PDO::FETCH_ASSOC);

// Запись в сессию информации о пользователе, если о нем есть информация
if(!empty($user)) {
    $_SESSION['user']['isInfo'] = true;
    $_SESSION['user']['firstname'] = $user['firstname'];
    $_SESSION['user']['lastname'] = $user['lastname'];
    $_SESSION['user']['birthday'] = $user['birthday'];
    $_SESSION['user']['tel'] = $user['tel'];
    $_SESSION['user']['address'] = $user['address'];
    $_SESSION['user']['gender'] = $user['gender'];
}

header('Location: /src/pages/profile.php');
exit();


