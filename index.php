<?php
include_once "include/functions.php";
$pageName = "Главная";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo SITE_NAME."-".$pageName;?></title>
</head>
<body>

<h2><a href="index.php?do=1">Войти</a>/<a href="index.php?do=2">Зарегистрироваться</a></h2>
<?php
if($_GET['do'] == 2){
?>
<form action="index.php" method="post">
    <input type="hidden" name="doing" value = "2">
    <input type="text" name="firstName" maxlength="30" size="30" placeholder="Name"><br>
    <input type="text" name="secondName" maxlength="30" size="30" placeholder="Patronymic"><br>
    <input type="text" name="lastName" maxlength="30" size="30" placeholder="Sirname"><br>
    birthday:<input type="date" name="birthday" ><br>
    <input type="text" name="login" maxlength="30" size="30" placeholder="Login"><br>
    <input type="password" name="passwd"  size="30" placeholder="Password"><br>
    <input type="password" name="retypepasswd"  size="30" placeholder="Retype password"><br>
    <input type="email" name="email"  size="30" placeholder="E-mail"><br>
    <button>Registration</button>
    <br>

<?php
}
else{
?>
    <form action="index.php" method="post">
        <input type="hidden" name="doing" value = "1">
        <input type="text" name="login" maxlength="30" size="30" placeholder="Login"><br>
        <input type="password" name="passwd"  size="30" placeholder="Password"><br>

        <button>Sing in</button>
        <br>
<?php
}
include_once "db.php";
include_once "class/users.php";

echo "<br><hr>";


if($_POST['doing'] == 2) {
    $salt = time();
    $myUser = new users();
    $checkPassword = $myUser->checkPassword($_POST['passwd'], $_POST['retypepasswd'], $salt);

    //echo $checkPassword;
    if ($checkPassword != 1) {
        echo $checkPassword;
        exit(0);
    }
//установка свойств класса

    $myUser->setFirstName($_POST['firstName']);
    $myUser->setSecondName($_POST['secondName']);
    $myUser->setLastName($_POST['lastName']);
//    if(!$_POST['birthday']) {
//        $_POST['birthday'] = NULL;
//    }
    $myUser->setBirthday($_POST['birthday']);
    $myUser->setLogin($_POST['login']);
    $myUser->setEmail($_POST['email']);

    $checkLogin = $myUser->checkLogin($db);

    if ($checkLogin != 1) {
        echo $checkLogin;
        exit(0);
    } else {
        $myUser->putRegistration($salt, $db);
    }
}
if($_POST['doing'] == 1){
    //обрабатываем вход
    $myUser = new users();
    $getUser = $myUser->getEnter($db, $_POST['login'], $_POST['passwd']);

}

if($_SESSION['id']){
    print_r($_SESSION);
    echo '<a href='.HOST.'/include/exit.php>Exit</a>';
}

