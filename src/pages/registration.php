
<?php session_start();?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/common.css" >
        <link rel="stylesheet" href="../css/regist.css" >
    <title>Авторизация</title>
</head>
<body>

<div class="regist">
    <div class="container">
        <div class="regist__body">
            <div class="regist__content">
                <div class="regist__title mb-4 fw-bold">Создайте свой профиль</div>
                <?php if(isset($_SESSION['error'])):?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error'];?></div>
                    <?php unset($_SESSION['error']);?>
                <?php endif;?>
                <form class="regist__form" action="../function/registration.php" method="post">
                    <div class="mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Электронная почта">
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" name="nickname" placeholder="Никнейм">
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" name="pass" placeholder="Пароль">
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" name="repeatPass" placeholder="Повторите пароль">
                    </div>
                    <button type="submit" class="regist__btn btn btn-primary mt-3">Зарегистрироваться</button>
                </form>
                <div class="regist__link regist__link-sing-in">У вас есть профиль <a href="sign_in.php">Войдите</a></div>
            </div>
        </div>
    </div>
</div>



</script>
</body>
</html>


