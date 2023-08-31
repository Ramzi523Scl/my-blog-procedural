<?php
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_STRINGIFY_FETCHES => false,
    PDO::ATTR_EMULATE_PREPARES => false
];
$pdo = new PDO('mysql:host=localhost;dbname=my_blog', 'root', '', $options);
session_start();

// записываю в сессию информацию "нужно ли делать запрос"
$_SESSION['make_req'] = false;

// запись в переменные данных поста, id поста из сессии
$post = $_SESSION['post'];
$post_id = $_SESSION['post']['id'];
$_SESSION['post-id'] = $post['id'];

// записываю в переменные название, описание, текст, фотку дату и id автора поста
$name = $_POST['post-name'];
$description = $_POST['post-descr'];
$text = $_POST['post-text'];
$img = $_POST['post-img'];
$date = date(" Y-m-d");
$author = $_SESSION['user']['id'];

// сохранение названия, описания, текста, изображения в СЕССИЮ
$_SESSION['post']['name'] = $name;
$_SESSION['post']['description'] = $description;
$_SESSION['post']['text_post'] = $text;
$_SESSION['post']['image'] = $img;

// Запись в переменные инфы о том какие поля заполнены
$is_name_filled = mb_strlen($name, 'UTF-8');
$is_descr_filled = mb_strlen($description, 'UTF-8');
$is_text_filled = mb_strlen($text, 'UTF-8');
$is_form_filled =  $is_name_filled && $is_descr_filled && $is_text_filled;

// Проверка на заполнение полей и выдача предупреждения пользователю
if (!$is_form_filled) {
    $_SESSION['msg-error'] = 'Заполните пустые поля';
    $_SESSION['name-error'] = !$is_name_filled;
    $_SESSION['descr-error'] = !$is_descr_filled;
    $_SESSION['text-error'] = !$is_text_filled;

    header('Location: ../pages/edit_post.php');
    exit();

}

// Проверка на количество символов в полях
if (mb_strlen($name, 'UTF-8') > 55) {
    $_SESSION['msg-error'] = 'Слишком длинное название поста';
    $_SESSION['name-error'] = true;

    header('Location: ../pages/edit_post.php');
    exit();
}
if (mb_strlen($description, 'UTF-8') > 200) {
    $_SESSION['msg-error'] = 'Слишком длинное описание поста';
    $_SESSION['descr-error'] = true;

    header('Location: ../pages/edit_post.php');
    exit();
}
if (mb_strlen($text, 'UTF-8') > 2500) {
    $_SESSION['msg-error'] = 'Слишком длинный текст поста';
    $_SESSION['text-error'] = true;

    header('Location: ../pages/edit_post.php');
    exit();
}


// При нажатии на кнопку "Очистить", очищается текст в полях
if($_POST['clear-btn']) {
    unset($_SESSION['post']);
    header('Location: ../pages/edit_post.php');
    exit();
}

// Логика при нажатии на кнопку "Сохранить"
if($_POST['save-btn']) {
    $sql = "UPDATE not_public_posts SET name=:nn, description=:dp, text_post=:tp, post_date=:pd, image=:img, author_id=:ai WHERE id=:pi";
    $statement = $pdo->prepare($sql);
    $statement->execute(['nn' => $name, 'dp' => $description, 'tp' => $text, 'pd' => $date, 'img' => $img, 'ai' => $author, 'pi' => $post_id]);

    unset($_SESSION['post']);
    header('Location: my_posts.php');
    exit();
}
// логика опубликовывания поста при нажатии на кнопку 'опубликовать'
if($_POST['post-btn']) {

    // сохранение в переменную инфы является ли пост опубликованным
    $is_public = $_SESSION['posted'];

    // если пост не опубликован, пост добавляется в опубликованные посты и удаляется из черновиков
    // если пост опубликован, то просто пост обновляется
    if($is_public === 'no') {

        // Добавление данного поста в таблицу опубликованных постов
        $sql = "INSERT INTO public_posts(name, description, text_post, post_date, image, author_id) VALUES (:n, :d,:tp, :pd, :img, :ai)";
        $statement = $pdo->prepare($sql);
        $statement->execute(['n' => $name, 'd' => $description, 'tp' => $text, 'pd'=> $date, 'img' => $img, 'ai' => $author]);

        // Удаление поста из таблицы не опубликованных постов
        $sql = "DELETE FROM not_public_posts WHERE id=:id";
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $post_id]);

    } elseif($is_public === 'yes') {
        // Обновление поста в таблице опубликованных постов
        $sql = "UPDATE public_posts SET name=:n, description=:d, text_post=:tp, post_date=:pd, image=:img, author_id=:ai WHERE id=:id";
        $statement = $pdo->prepare($sql);
        $statement->execute(['n' => $name, 'd' => $description, 'tp' => $text, 'pd'=> $date, 'img' => $img, 'ai' => $author, 'id' => $post_id]);
    }
    header('Location: ../pages/my_posts.php');
    exit();
}
header('Location: ../pages/my_posts.php');