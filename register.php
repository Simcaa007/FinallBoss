<?php 
    session_start();

    require_once('Db.php');
    Db::connect('127.0.0.1', 'form', 'root', '');

    if(isset($_POST['register'])){
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        Db::query('
            INSERT INTO users (name, password)
            VALUES (?, ?)
            ', $_POST['name'], $password);

        $_SESSION['user_id'] = Db::getLastId();
        header('Location: knihy.php');
        exit();
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="gold-card register-box">
    <h2>Nová registrace</h2>

    <form method="POST">
        <div class="input-group">
            <label>Uzivatelkse jmeno:</label>
            <input type="text" name="name"  required>
        </div>
        <div class="input-group">
            <label>Heslo</label>
            <input type="text" name="password" required>
        </div>
        <input type="submit" name="register" value="register">
    </form>
</div>
</body>
</html>