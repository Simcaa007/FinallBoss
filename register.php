<?php
session_start();

    require_once('Db.php');
    Db::connect('127.0.0.1', 'finalboss', 'root', '');

    if (isset($_POST['vytvorit'])){
        Db::insert('users', [
            'username' => $_POST['username'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'location' => $_POST['location']
        ]);

        $_SESSION['username'] = $_POST['username'];
        $_SESSION['location'] = $_POST['location'];
        $_SESSION['logged_in'] = true;

        header('Location: index.php');
        Die();
    }

?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrace | IoT Connect</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body class="auth-page">

    <div class="auth-container">
        <div class="auth-header">
            <span class="logo">IoTConnect</span></a>
        </div>
        
        <div class="auth-card">
            <h2>Nový účet</h2>
            <form method="POST">
                <div class="input-group">
                    <label>Přezdívka</label>
                    <input type="text" name="username" placeholder="Novis" required>
                </div>
                <div class="input-group">
                    <label>Heslo</label>
                    <input type="password" name="password" placeholder="Minimálně 8 znaků" required>
                </div>
                <div class="input-group">
                    <label>Město</label>
                    <input type="text" name="location" placeholder="Praha" required>
                </div>
                <button type="submit" name="vytvorit" class="btn btn-full">Vytvořit účet</button>
            </form>
            <p class="switch-text">Již máte účet? <a href="login.php" class="accent-link">Přihlaste se</a></p>
        </div>
        <a href="index.php" class="back-link">← Zpět na úvod</a>
    </div>

</body>
</html>