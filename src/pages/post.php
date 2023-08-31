<?php
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_STRINGIFY_FETCHES => false,
    PDO::ATTR_EMULATE_PREPARES => false
];
$pdo = new PDO('mysql:host=localhost;dbname=my_blog', 'root', '', $options);
session_start();

if(!$_SESSION['user']) header('Location: ../../index.php');

$_SESSION['post-id'] = $_GET['id'];

$post_id = $_GET['id'];
$is_post = $_GET['posted'];

$sql = '';

if($is_post === 'yes') $sql = "SELECT * FROM public_posts WHERE id=:id";
if($is_post === 'no') $sql = "SELECT * FROM not_public_posts WHERE id=:id";

$statement = $pdo->prepare($sql);
$statement->execute(['id' => $post_id]);
$post = $statement->fetch(PDO::FETCH_ASSOC);
$_SESSION['post'] = $post;

$_SESSION['make_req'] = true;

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="/src/css/common.css" >
    <link rel="stylesheet" href="/src/css/create_post.css" >
    <title><?php echo $post['name'];?></title>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-primary " data-bs-theme="dark">
    <div class="container-fluid container d-flex justify-content-between">
        <a class="navbar-brand" href="../../index.php">
            <img src="../image/logo.svg" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
            Мой блог
        </a>
        <div class="collapse navbar-collapse flex-grow-1" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="../../index.php">Лента</a>
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
            </ul>
        </div>
        <?php if($_SESSION['user']): ?>
            <a href="profile.php" class="profile__link btn btn-success mx-2" role="button">Мой Профиль</a>
            <a href="../function/logout.php" class="profile__link btn btn-danger " role="button">Выйти</a>
        <?php else: ?>
            <a href="sign_in.php" class="profile__link btn btn-success mx-2" role="button">Войти</a>
        <?php endif; ?>

    </div>
</nav>

<main class="post">
    <div class="container">
        <div class="post__content">
            <form action="../function/post.php"  method="post" class="post__form d-flex flex-column">

                <fieldset disabled>
                <div class="post__item post__name d-flex flex-column">
                    <label class="form-label px-2">Название поста</label>
                    <input type="text" class="form-control" name="post-name" style="width: 100%;" value="<?php echo $post['name'];?>">
                </div>

                <div class="post__item post__descr d-flex flex-column">
                    <label class="form-label px-2">Краткое описание поста</label>
                    <input type="text" class="form-control" name="post-descr" value="<?php echo $post['description'];?>">
                </div>

                <div class="post__item post__text d-flex flex-column">
                    <label class="form-label px-2">Текст поста</label>
                    <textarea class="form-control" cols="50" rows="20" name="post-text"><?php echo $post['text_post'];?></textarea>
                </div>
                </fieldset>
                <img class="post__item post__img mx-auto" src="../image/<?php echo $post['image'];?>" alt="" width="300">

                <div class="d-flex flex-column mb-3 text-end">
                   <?php if($is_post == 'yes'):?>
                       <label class="form-label">Дата публикации: <i><?php echo $post['post_date'];?></i></label>
                   <?php elseif($is_post == 'no'):?>
                       <label class="form-label">Дата сохранения: <i><?php echo $post['post_date'];?></i></label>
                   <?php endif;?>
                </div>

                <div class="post__item post__btns d-flex justify-content-between">
                    <div>
                        <a href="../function/delete_post.php?posted=<?php echo $is_post; ?>&id=<?php echo $post_id; ?>"
                           class="post__btn btn btn-danger"
                           role="button"
                        >Удалить</a>
                        <a href="../pages/edit_post.php?posted=<?php echo $is_post; ?>&id=<?php echo $post_id; ?>"
                           class="post__btn btn btn-info"
                           role="button"
                        >Редактировать</a>
                    </div>
                    <div>
                        <?php if($is_post == 'no'):?>
                            <input class="post__btn btn btn-success" type="submit" name="post-btn" value="Опубликовать ->">
                        <?php endif;?>
                    </div>
                </div>

            </form>
        </div>
    </div>
</main>

</body>
</html>
