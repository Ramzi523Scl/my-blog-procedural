<?php
// настроки подключения к бд
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_STRINGIFY_FETCHES => false,
    PDO::ATTR_EMULATE_PREPARES => false
];
$pdo = new PDO('mysql:host=localhost;dbname=my_blog', 'root', '', $options);
session_start();


// записываю в переменные необходимые данные
$title = $_POST['post-title'];
$description = $_POST['post-descr'];
$text = $_POST['post-text'];
$img = $_POST['post-img'];
$date = date(" Y-m-d");
$author = $_SESSION['user']['id'];


// Записть заполненной инфы в сессию
$_SESSION['post']['title'] = $title;
$_SESSION['post']['descr'] = $description;
$_SESSION['post']['text'] = $text;
$_SESSION['post']['img'] = $img;


// Запись в переменные инфы того какие поля заполнены
$is_title_filled = mb_strlen($title, 'UTF-8');
$is_descr_filled = mb_strlen($description, 'UTF-8');
$is_text_filled = mb_strlen($text, 'UTF-8');
$is_form_filled =  $is_title_filled && $is_descr_filled && $is_text_filled;



// Проверка на заполнение полей и выдача предупреждения пользователю
if (!$is_form_filled) {
    $_SESSION['msg-error'] = 'Заполните пустые поля';
    $_SESSION['name-error'] = !$is_title_filled;
    $_SESSION['descr-error'] = !$is_descr_filled;
    $_SESSION['text-error'] = !$is_text_filled;

    header('Location: ../pages/create_post.php');
    exit();
}

// Проверка текста поста на количество символов
if (mb_strlen($title, 'UTF-8') > 55) {
    $_SESSION['msg-error'] = 'Слишком длинное название поста';
    $_SESSION['name-error'] = true;

    header('Location: ../pages/create_post.php');
    exit();
}
if (mb_strlen($description, 'UTF-8') > 200) {
    $_SESSION['msg-error'] = 'Слишком длинное описание поста';
    $_SESSION['descr-error'] = true;

    header('Location: ../pages/create_post.php');
    exit();
}
if (mb_strlen($text, 'UTF-8') > 2500) {
    $_SESSION['msg-error'] = 'Слишком длинный текст поста';
    $_SESSION['text-error'] = true;

    header('Location: ../pages/create_post.php');
    exit();
}


// Очистка сессии и других данных при нажатии на кнопку "Очистить"
if($_POST['clear-btn']) {
    unset($_SESSION['post'], $name, $description, $text, $img, $date, $author);
    header('Location: ../pages/create_post.php');
    exit();
}

// Логика при нажатии на кнопку "Сохранить"
if($_POST['save-btn']) {

    $sql = "INSERT INTO not_public_posts(name, description, text_post, post_date, image, author_id) VALUES (:nn, :descr, :tp, :pd, :img, :ai)";
    $statement = $pdo->prepare($sql);
    $statement->execute(['nn' => $title, 'descr' => $description, 'tp' => $text, 'pd' => $date, 'img' => $img, 'ai' => $author]);

    unset($_SESSION['post']);
    header('Location: ../pages/my_posts.php');
    exit();
}

// Логика при нажатии на кнопку "Опубликовать"
if($_POST['post-btn']) {

    $sql = "INSERT INTO public_posts(name, description, text_post, post_date, image, author_id) VALUES (:nn, :descr, :tp, :pd, :img, :ai)";
    $statement = $pdo->prepare($sql);
    $statement->execute(['nn' => $title, 'descr' => $description, 'tp' => $text, 'pd' => $date, 'img' => $img, 'ai' => $author]);

    unset($_SESSION['post']);
    header('Location: ../pages/my_posts.php');
    exit();
}