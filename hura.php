<?php

    session_start();

    require_once('Db.php');
    Db::connect('127.0.0.1', 'books', 'root', '');

    if (!isset($_SESSION['user_id'])){
        header('Location: login.php');
        exit();
    }

    if(isset($_POST['odhlasit'])){
        session_unset();  
        session_destroy();
        header('Location: login.php');
        exit();
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>hura</p>
    <form method="POST">

        <input type="submit" value="odhlasit" name="odhlasit">

    </form>
</body>
</html>