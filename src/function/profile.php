<?php
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_STRINGIFY_FETCHES => false,
    PDO::ATTR_EMULATE_PREPARES => false
];
$pdo = new PDO('mysql:host=localhost;dbname=my_blog', 'root', '', $options);
session_start();

// Сохранение в переменные данных из формы: id, фамилие, имя и другие
$id = $_SESSION['user']['id'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$dateBirth = $_POST['date-birth'];
$tel = $_POST['tel'];
$address = $_POST['address'];
$gender = $_POST['gender'];

$email = $_POST['email'];
$new_email = $_POST['new-email'];

$nick = $_POST['old-nick'];
$new_nick = $_POST['new-nick'];
$nick_pass = $_POST['nick-pass'];

$pass = $_POST['pass'];
$new_pass = $_POST['new-pass'];
$new_rPass = $_POST['new-rpass'];

// Запрос на получение основных сведений о пользователе, если они есть
$sql = "SELECT * FROM users_info WHERE users_login_id=:id";
$statement = $pdo->prepare($sql);
$statement->execute(['id' => $id]);
$user = $statement->fetch(PDO::FETCH_ASSOC);

// Сохранение в сессию есть ли пользователь в базе
$_SESSION['user']['isInfo'] = (bool)$user;
//var_dump($_SESSION['user']['isInfo']);


// При нажатии на кнопку "сохранить"  из формы "о себе" данные добавляются или обновляются в бд
if($_POST['info-btn']) {

    // Если пользователя нет, то добавляются новые данные о нем
    if(empty($user)) {
        // Добавление новой информации о пользователе
        $sql = 'INSERT INTO users_info(firstname, lastname, birthday, tel, address, gender, users_login_id) VALUES (:fn, :ln, :bd, :tel, :address, :gender, :id)';
        $statement = $pdo->prepare($sql);
        $statement->execute(['fn' => $firstname, 'ln' => $lastname, 'bd' => $dateBirth, 'tel' => $tel, 'address' => $address, 'gender' => $gender, 'id' => $id]);


        // Запрос на получение ново-добавленных данных о пользователе
        $sql = "SELECT * FROM users_info WHERE users_login_id=:id";
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $id]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        $_SESSION['user']['isInfo'] = (bool)$user;

        // Созранение этих данных в сессию, для отображения их на странице профиля profile.php
        $_SESSION['user']['firstname'] = $user['firstname'];
        $_SESSION['user']['lastname'] = $user['lastname'];
        $_SESSION['user']['birthday'] = $user['birthday'];
        $_SESSION['user']['tel'] = $user['tel'];
        $_SESSION['user']['address'] = $user['address'];
        $_SESSION['user']['gender'] = $user['gender'];

        header('Location: /src/pages/profile.php');
        exit();
    }
    // Если пользователь есть в базе, то данные о нем обновлляются
    $sql = 'UPDATE users_info SET firstname=:fn, lastname=:ln, birthday=:bd, tel=:tel, address=:address, gender=:gender WHERE users_login_id=:id';
    $statement = $pdo->prepare($sql);
    $statement->execute(['fn' => $firstname, 'ln' => $lastname, 'bd' => $dateBirth, 'tel' => $tel, 'address' => $address, 'gender' => $gender, 'id' => $id]);

    // Если в сессии нет акутульных данных о пользователе, то идет Запрос на получение данных
    if($_SESSION['user']['isInfo']) {
        $sql = "SELECT * FROM users_info WHERE users_login_id=:id";
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $id]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        // Созранение этих данных в сессию, для отображения их на странице профиля profile.php
        $_SESSION['user']['firstname'] = $user['firstname'];
        $_SESSION['user']['lastname'] = $user['lastname'];
        $_SESSION['user']['birthday'] = $user['birthday'];
        $_SESSION['user']['tel'] = $user['tel'];
        $_SESSION['user']['address'] = $user['address'];
        $_SESSION['user']['gender'] = $user['gender'];
    }

    header('Location: /src/pages/profile.php');
    exit();
}


// При нажатии на кнопку сохранить из формы "Изменение почты", почта меняется
if($_POST['email-btn']) {
    // Получение текущей почты пользователя из базы
    $sql = "SELECT * FROM users_login WHERE id=:id AND email=:e";
    $statement = $pdo->prepare($sql);
    $statement->execute(['id' => $id, 'e' => $email]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    // Если пользователь с такой почтой отсуствует то предупредить что почта не верна
    if(empty($user)) {
        $_SESSION['email-error'] = 'Почта введена не верно';
        header('Location: /src/pages/profile.php');
        exit();
    }
    // Если пользователь найден, то обновить почту на новую
    $sql = "UPDATE users_login SET email=:email WHERE nickname=:nn";
    $statement = $pdo->prepare($sql);
    $statement->execute(['email' => $new_email, 'nn' => $user['nickname']]);

    $_SESSION['email-msg'] = 'Почта успешно изменена';
    $_SESSION['user']['email'] = $new_email;


    header('Location: /src/pages/profile.php');
    exit();
}


// При нажатии на кнопку сохранить из формы "Изменение никнейма", никнейм меняется
if($_POST['nick-btn']) {

    // Запрос на получение актуального ника и пароля
    $sql = 'SELECT nickname, password FROM users_login WHERE id=:id';
    $statement = $pdo->prepare($sql);
    $statement->execute(['id' => $_SESSION['user']['id']]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    // Проверка на правильность ника и пароля, если нет то предупредить об этом
    $is_nick_right = ($user['nickname'] === $nick) && password_verify($nick_pass, $user['password']);
    if(!$is_nick_right) {
        $_SESSION['nick-error'] = 'Вы ввели не верные данные';
        header('Location: /src/pages/profile.php');
        exit();
    }

    // Если же ник и пароль введены верно, измерить ник на новый
    $sql = "UPDATE users_login SET nickname=:newnn WHERE nickname=:oldnn";
    $statement = $pdo->prepare($sql);
    $statement->execute(['newnn' => $new_nick, 'oldnn' => $nick]);

    $_SESSION['nick-msg'] = 'Никнейм успешно изменен';
    header('Location: /src/pages/profile.php');
    exit();
}


// При нажатии на кнопку сохранить из формы "Изменение пароля", пароль обновляется
if($_POST['pass-btn']) {

    //Проверка на совпадение нового пароля
    if($new_pass !== $new_rPass) {
        $_SESSION['pass-error'] = 'Пароли не совпадают';
        header('Location: /src/pages/profile.php');
        exit();
    }

    // Запрос на получение ника и пароля пользователя по id
    $sql = "SELECT nickname, password FROM users_login WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(['id' => $id]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    // Проверка на правильность введенного пароля с паролем из бд
    $is_match_pass = password_verify($pass, $user['password']);
    if(!$is_match_pass) {
        $_SESSION['pass-error'] = 'Текущий пароль указан не верно';
        header('Location: /src/pages/profile.php');
        exit();
    }

    // Захешировал новый пароль и записал его на бд вместо старого и уведомил что пароль успешно изменен
    $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);
    $sql = 'UPDATE users_login SET password=:pass WHERE id=:id AND nickname=:nn';
    $statement = $pdo->prepare($sql);
    $statement->execute(['pass' => $hashed_password, 'id' => $id, 'nn' => $user['nickname']]);

    $_SESSION['pass-msg'] = 'Пароль успешно изменен';
    header('Location: /src/pages/profile.php');
    exit();
}






