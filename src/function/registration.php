<?php
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_STRINGIFY_FETCHES => false,
    PDO::ATTR_EMULATE_PREPARES => false
];
$pdo = new PDO('mysql:host=localhost;dbname=my_blog', 'root', '', $options);
//require_once '../db_config.php';
session_start();

// Сохранение в переменные почты, ника, пароля
$email = $_POST['email'];
$nickname = $_POST['nickname'];
$pass = $_POST['pass'];
$repeat_pass = $_POST['repeatPass'];

// Проверка пустые ли поля почты, ника, пароля
$is_empty = ($email !== '') && ($nickname !== '') && ($pass !== '') && ($repeat_pass !== '');
// Если пустые то выходит предупреждение
if(!$is_empty) {
    $_SESSION['error'] = 'Заполните пустые поля';
    header('Location: /src/pages/registration.php');
    exit();
}
// Если пароль короткий то выводит предупреждение
if (strlen($pass) < 1) {
    $_SESSION['error'] = 'Пароль должен состоять минимум из 8 символов';
    header('Location: /src/pages/registration.php');
    exit();
}
// Если пароли не совпадают, выводит предупреждение
if($pass != $repeat_pass) {
    $_SESSION['error'] = 'Пароли не совпадают';
    header('Location: /src/pages/registration.php');
    exit();
}
// Если все верно, то проверяется через запрос, если такой ник в базе
$sql = 'SELECT * FROM users_login WHERE nickname=:nick';
$statement = $pdo->prepare($sql);
$statement->execute(['nick' => $nickname]);
$user = $statement->fetch(PDO::FETCH_ASSOC);

// Если есть, то выводится предупреждение
if(!empty($user)) {
    $_SESSION['error'] = 'Этот ник уже занят';
    header('Location: /src/pages/registration.php');
    exit();
}

// Хешируется пароль
$hashed_password = password_hash($pass, PASSWORD_DEFAULT);
// Запрос на добавление данных нового пользователя
$sql = 'INSERT INTO users_login (email, nickname, password) VALUES (:email, :nick, :pass)';
$statement = $pdo->prepare($sql);
$statement->execute(['email' => $email, 'nick' => $nickname, 'pass' => $hashed_password]);

// Провторный запрос на получение ново-добавленных данных по нику
$sql = 'SELECT * FROM users_login WHERE nickname=:nick';
$statement = $pdo->prepare($sql);
$statement->execute(['nick' => $nickname]);
$user = $statement->fetch(PDO::FETCH_ASSOC);

// Запись в сессию данных о пользователе, для отображения их на странице "Мой профиль"
$_SESSION['user']['isAuth'] = true;
$_SESSION['user']['id'] = $user['id'];
$_SESSION['user']['email'] = $user['email'];
$_SESSION['user']['nick'] = $user['nickname'];

header('Location: /src/pages/profile.php');
exit();
