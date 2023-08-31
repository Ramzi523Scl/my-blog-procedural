<?php
global $pdo;
include('../db_config.php');
session_start();

$id = $_GET['id'];

$sql = 'SELECT * FROM public_posts WHERE id=:id';
$statement = $pdo->prepare($sql);
$statement->execute(['id' => $id]);
$post = $statement->fetch(PDO::FETCH_ASSOC);

$user_id = $post['author_id'];

$sql = "SELECT nickname FROM users_login WHERE id=:id";
$statement = $pdo->prepare($sql);
$statement->execute(['id'=> $user_id]);
$nick = $statement->fetch(PDO::FETCH_ASSOC);
$nick = $nick['nickname'];

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="/src/css/common.css" >
    <link rel="stylesheet" href="/src/css/read_post.css" >
    <title><?php echo $post['name'];?></title>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-primary " data-bs-theme="dark">
    <div class="container-fluid container d-flex justify-content-between">
        <a class="navbar-brand" href="../../index.php">
            <img src="../image/logo.svg" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
            Мой блог
        </a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <?php if($_SESSION['user']): ?>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="../../index.php">Лента</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="my_posts.php">Мои посты</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="create_post.php">Добавить пост</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link">Избранные</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled">Админка</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="../../index.php">Лента</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled">Мои посты</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled">Добавить пост</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled">Избранные</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled">Админка</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <?php if($_SESSION['user']): ?>
            <a href="../pages/profile.php" class="profile__link btn btn-success mx-2" role="button">Мой Профиль</a>
            <a href="../function/logout.php" class="profile__link btn btn-danger " role="button">Выйти</a>
        <?php else: ?>
            <a href="../pages/sign_in.php" class="profile__link btn btn-success mx-2" role="button">Войти</a>
        <?php endif; ?>

    </div>
</nav>

<main class="post">
    <div class="container">
        <div class="post__content">
            <div class="post__author post__item" >
                <img class="post__ava" src="../image/ava.svg" height="23"></img>
                <a class="post__nick"><?php echo $nick;?></a>
                <i class="post__date"><?php echo $post['post_date'];?></i>
            </div>
            <h2 class="post__name post__item"><?php echo $post['name'];?></h2>
            <div class="post__img post__item">
                <img class="img-fluid" src="../image/<?php echo $post['image'];?>">
            </div>
            <h4 class="post__description post__item"><?php echo $post['description'];?></h4>
            <div class="post__text post__item"><?php echo $post['text_post'];?></div>
        </div>
    </div>
</main>

</body>
</html>
