<?php
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_STRINGIFY_FETCHES => false,
    PDO::ATTR_EMULATE_PREPARES => false
];
$pdo = new PDO('mysql:host=localhost;dbname=my_blog', 'root', '', $options);
session_start();

// id поста и инфа опубликован ли пост
$posted = $_GET['posted'];
$post_id = (int) $_GET['id'];

//  удаляется опубликованный пост
if($posted == 'yes') {
    $sql = "DELETE FROM public_posts WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(['id' => $post_id]);
}

//  удаляется черновик
if($posted == 'no') {
    $sql = "DELETE FROM not_public_posts WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(['id' => $post_id]);
}

// очистка информации о постах из сессии
unset($_SESSION['user']['public_posts']);
unset($_SESSION['user']['drafts']);

//  запрос на получение опубликованных постах и сохранение их в сессию
$user_id = $_SESSION['user']['id'];

$sql = 'SELECT * FROM public_posts WHERE author_id=:id';
$statement = $pdo->prepare($sql);
$statement->execute(['id' => $user_id]);
$public_posts = $statement->fetchAll(PDO::FETCH_ASSOC);
$_SESSION['user']['public_posts'] = $public_posts;

//  запрос на получение черновиков и сохранение их в сессию
$sql = 'SELECT * FROM not_public_posts WHERE author_id=:id';
$statement = $pdo->prepare($sql);
$statement->execute(['id' => $user_id]);
$drafts = $statement->fetchAll(PDO::FETCH_ASSOC);
$_SESSION['user']['drafts'] = $drafts;

header('Location: ../function/my_posts.php');

