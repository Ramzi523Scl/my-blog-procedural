<?php
session_start();

if(!$_SESSION['user']) header('Location: ../../index.php');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/common.css" >
        <link rel="stylesheet" href="../css/profile.css" >
    <title>Мой профиль</title>
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
        <a href="../function/logout.php" class="profile__link btn btn-danger " role="button">Выйти</a>

    </div>
</nav>

    <div class="profile">
        <div class="container">
            <div class="profile__body">
                <div class="profile__my-name my-name">
                    <div class="my-name__item my-name__img d-flex align-items-center">
                        <img src="../image/ava.svg" alt="Logo" width="70" height="70" class="d-inline-block align-text-top">
                    </div>
                    <div class="my-name__item my-name__info ">
                        <p class="fs-3 fw-bold"><?php echo $_SESSION['user']['nick']; ?></p>
                        <p class="fs-6"><?php echo $_SESSION['user']['email']; ?></p>

                    </div>
                </div>
                <div class="profile__content">
                    <div class="profile__item item profile__about-me about-me">
                        <div class="item__left">
                            <div class="item-title">
                                <h4>
                                    О себе
                                </h4>
                            </div>
                        </div>

                        <form action="../function/profile.php" method="post" class="item__form">
                            <?php if($_SESSION['user']['isInfo']):?>
                                <input type="text" class="item__input about-me__inp" name="firstname" placeholder="Имя" value="<?php echo $_SESSION['user']['firstname']?>">
                                <input type="text" class="item__input about-me__inp" name="lastname" placeholder="Фамилия" value="<?php echo $_SESSION['user']['lastname']?>">
                                <input type="date" class="item__input about-me__inp" name="date-birth" placeholder="Дата Рождения" value="<?php echo $_SESSION['user']['birthday']?>">
                                <input type="tel" class="item__input about-me__inp" name="tel" placeholder="Номер телефона" value="<?php echo $_SESSION['user']['tel']?>">
                                <input type="text" class="item__input about-me__inp" name="address" placeholder="Адрес" value="<?php echo $_SESSION['user']['address']?>">

                                <?php if($_SESSION['user']['gender'] === 'male'): ?>
                                    <div class="item__radio-content">
                                        <p class="item__radio"><input type="radio" name="gender" value="male"  checked> Мужской</p>
                                        <p class="item__radio"><input type="radio" name="gender" value="female" > Женский</p>
                                    </div>
                                <?php elseif($_SESSION['user']['gender'] === 'female'): ?>
                                    <div class="item__radio-content">
                                        <p class="item__radio"><input type="radio" name="gender" value="male" > Мужской</p>
                                        <p class="item__radio"><input type="radio" name="gender" value="female" checked> Женский</p>
                                    </div>
                                <?php else: ?>
                            <div class="item__radio-content">
                                <p class="item__radio"><input type="radio" name="gender" value="male" > Мужской</p>
                                <p class="item__radio"><input type="radio" name="gender" value="female"> Женский</p>
                            </div>
                            <?php endif; ?>

                            <?php else:?>
                                <input type="text" class="item__input about-me__inp" name="firstname" placeholder="Имя">
                                <input type="text" class="item__input about-me__inp" name="lastname" placeholder="Фамилия">
                                <input type="date" class="item__input about-me__inp" name="date-birth" placeholder="Дата Рождения">
                                <input type="tel" class="item__input about-me__inp" name="tel" placeholder="Номер телефона">
                                <input type="text" class="item__input about-me__inp" name="address" placeholder="Адрес">

                                <div class="item__radio-content">
                                    <p class="item__radio"><input type="radio" name="gender" value="male" > Мужской</p>
                                    <p class="item__radio"><input type="radio" name="gender" value="female" > Женский</p>
                                </div>
                            <?php endif;?>



                            <input class="item__btn" type="submit" name="info-btn" value="Сохранить">
                        </form>
                    </div>
                    <div class="profile__item item profile__email email">
                        <div class="item__left">
                            <div class="item-title">
                                <h4>
                                    Изменение почты
                                </h4>
                            </div>
                        </div>
                        <div class="item__right column">
                            <?php if(isset($_SESSION['email-error'])):?>
                                <div class="alert alert-danger"><?php echo $_SESSION['email-error'];?></div>
                                <?php unset($_SESSION['email-error']);?>
                            <?php elseif(isset($_SESSION['email-msg'])):?>
                                <div class="alert alert-success"><?php echo $_SESSION['email-msg'];?></div>
                                <?php unset($_SESSION['email-msg']);?>
                            <?php endif;?>
                            <form action="../function/profile.php" method="post" class="item__form">

                                <input type="email" class="item__input email__inp" name="email" placeholder="Ваша почта: <?php echo $_SESSION['user']['email'] ?>">
                                <input type="email" class="item__input email__inp" name="new-email" placeholder="Новая почта">

                                <input class="item__btn" type="submit" name="email-btn" value="Сохранить">
                            </form>
                        </div>
                    </div>
                    <div class="profile__item item profile__nickname nickname">
                        <div class="item__left">
                            <div class="item-title">
                                <h4>
                                    Изменение никнейма
                                </h4>
                            </div>
                        </div>

                    <div class="item__right column">
                        <?php if(isset($_SESSION['nick-error'])):?>
                            <div class="alert alert-danger"><?php echo $_SESSION['nick-error'];?></div>
                            <?php unset($_SESSION['nick-error']);?>
                        <?php elseif(isset($_SESSION['nick-msg'])):?>
                            <div class="alert alert-success"><?php echo $_SESSION['nick-msg'];?></div>
                            <?php unset($_SESSION['nick-msg']);?>
                        <?php endif;?>
                        <form action="../function/profile.php" method="post" class="item__form">
                            <input type="text" class="item__input nickname__inp" name="old-nick" placeholder="Ваш ник: <?php echo $_SESSION['user']['nick'] ?>">
                            <input type="text" class="item__input nickname__inp" name="new-nick" placeholder="Новый ник">
                            <input type="password" class="item__input password__inp" name="nick-pass" placeholder="Пароль">

                            <input class="item__btn" type="submit" name="nick-btn" value="Сохранить">
                        </form>
                    </div>
                    </div>
                    <div class="profile__item item profile__password password">
                        <div class="item__left">
                            <div class="item-title">
                                <h4>
                                    Изменение пароля
                                </h4>
                            </div>
                        </div>
                        <div class="item__right column">
                            <?php if(isset($_SESSION['pass-error'])):?>
                                <div class="alert alert-danger"><?php echo $_SESSION['pass-error'];?></div>
                                <?php unset($_SESSION['pass-error']);?>
                            <?php elseif(isset($_SESSION['pass-msg'])):?>
                                <div class="alert alert-success"><?php echo $_SESSION['pass-msg'];?></div>
                                <?php unset($_SESSION['pass-msg']);?>
                            <?php endif;?>
                            <form action="../function/profile.php" method="post" class="item__form">
                                <input type="password" class="item__input password__inp" name="pass" placeholder="Пароль">
                                <input type="password" class="item__input password__inp" name="new-pass" placeholder="Новый пароль">
                                <input type="password" class="item__input password__inp" name="new-rpass" placeholder="Повторите пароль">

                                <input class="item__btn" type="submit" name="pass-btn" value="Сохранить">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>


