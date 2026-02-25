<?php

    session_start();

    require_once('Db.php');
    Db::connect('127.0.0.1', 'form', 'root', '');

if ($_POST) {
    $user = Db::queryOne('
        SELECT id, password
        FROM users
        WHERE name=?
    ', $_POST['name']);

    if (!$user || !password_verify($_POST['password'], $user['password']))
        $zprava = 'Neplatné uživatelské jméno nebo heslo.';
    else {
        $_SESSION['user_id'] = $user['id'];
        header('Location: knihy.php');
        exit();
    }
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
    <div class="gold-card login-box">
    <h2>Vstup do systému</h2>
    <form method="POST">
        <div class="input-group">
            <label>Uživatelské jméno</label>
            <input type="text" name="name" required>
        </div>
        <div class="input-group">
            <label>Heslo</label>
            <input type="text" name="password" required>
        </div>
        <button type="submit" class="gold-btn">PŘIHLÁSIT SE</button>
    </form>
</div>
</body>
</html>