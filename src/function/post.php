<?php

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_STRINGIFY_FETCHES => false,
    PDO::ATTR_EMULATE_PREPARES => false
];
$pdo = new PDO('mysql:host=localhost;dbname=my_blog', 'root', '', $options);
session_start();


// логика опубликовывания поста при нажатии на кнопку 'опубликовать'
if($_POST['post-btn']) {

    // Получил id поста
    $post_id = $_SESSION['post-id'];
    unset($_SESSION['post-id']);

    // Получить все данные по id поста
    $sql = "SELECT * FROM not_public_posts WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(['id' => $post_id]);
    $post = $statement->fetch(PDO::FETCH_ASSOC);

    // Сохранение данных в переменные, чтобы их эти данные добавить в таблицу опубликованных постов
    $name = $post['name'];
    $description = $post['description'];
    $text = $post['text_post'];
    $date = date(" Y-m-d");
    $img = $post['image'];
    $author = $post['author_id'];

    // Добавление данного поста в таблицу опубликованных постов
    $sql = "INSERT INTO public_posts(name, description, text_post, post_date, image, author_id) VALUES (:n, :d,:tp, :pd, :img, :ai)";
    $statement = $pdo->prepare($sql);
    $statement->execute(['n' => $name, 'd' => $description, 'tp' => $text, 'pd'=> $date, 'img' => $img, 'ai' => $author]);

    // Удаление поста из таблицы не опубликованных постов
    $sql = "DELETE FROM not_public_posts WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(['id' => $post_id]);

    header('Location: ../pages/my_posts.php');

}


