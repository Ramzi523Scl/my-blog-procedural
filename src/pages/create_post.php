<?php
session_start();

// Если пользователь не авторизован, его перекидывает на главную страничку
if(!$_SESSION['user']){
    header('Location: ../../index.php');
}
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
    <title>Добавление постов</title>
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
                    <a class="nav-link active" href="#">Добавить пост</a>
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
            <form action="../function/create_post.php"  method="post" class="post__form d-flex flex-column">
                <?php if(isset($_SESSION['msg-error'])): ?>
                    <p class="alert alert-danger"> <?php echo $_SESSION['msg-error']; ?></p>
                    <?php unset($_SESSION['msg-error']);?>
                <?php endif;?>
                <div class="post__item post__name d-flex flex-column">
                    <label class="form-label px-2">Название поста</label>

                    <?php if($_SESSION['name-error']): ?>
                        <input type="text" class="form-control error-input" name="post-title" style="width: 100%;" value="<?php echo $_SESSION['post']['title'];?>">
                        <?php unset($_SESSION['name-error']);?>
                    <?php else: ?>
                        <input type="text" class="form-control" name="post-title" style="width: 100%;" value="<?php echo $_SESSION['post']['title'];?>">
                    <?php endif;?>

                </div>

                <div class="post__item post__descr d-flex flex-column">
                    <label class="form-label px-2">Краткое описание поста</label>
                    <?php if($_SESSION['descr-error']): ?>
                        <input type="text" class="form-control error-input" name="post-descr" value="<?php echo $_SESSION['post']['descr'];?>">
                        <?php unset($_SESSION['descr-error']);?>
                    <?php else: ?>
                        <input type="text" class="form-control" name="post-descr" value="<?php echo $_SESSION['post']['descr'];?>">
                    <?php endif;?>

                </div>

                <div class="post__item post__text d-flex flex-column">
                    <label class="form-label px-2">Текст поста</label>

                    <?php if($_SESSION['text-error']): ?>
                        <textarea class="form-control error-input" cols="50" rows="20" name="post-text"><?php echo $_SESSION['post']['text'];?></textarea>
                        <?php unset($_SESSION['text-error']);?>
                    <?php else: ?>
                        <textarea class="form-control" cols="50" rows="20" name="post-text"><?php echo $_SESSION['post']['text'];?></textarea>
                    <?php endif;?>
                </div>

                    <input type="file" class="post__item post__img" name="post-img">

                <div class="post__item post__btns d-flex justify-content-between">
                    <div>
                        <input class="post__btn btn btn-danger" type="submit" name="clear-btn" value="Очистить">
                        <input class="post__btn btn btn-warning" type="submit" name="save-btn" value="Сохранить">
                    </div>
                    <div>
                        <input class="post__btn btn btn-success" type="submit" name="post-btn" value="Опубликовать ->">
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

</body>
</html>
