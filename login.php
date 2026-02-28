<?php 

    session_start();

    require_once('Db.php');
    Db::connect('127.0.0.1', 'finalboss', 'root', '');

    if (isset($_POST['prihlasit'])){
        $user = Db::queryOne('SELECT * FROM users WHERE username = ?', $_POST['username']);

        if ($user){
            if (password_verify($_POST['password'], $user['password'])){

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['location'] = $user['location']; 
                $_SESSION['logged_in'] = true;

                header('Location: dashboard.php');
                die();
            }
            else{
                $error = "Bylo zadano spatne heslo nebo jmeno";
            }
        }
        else{
            $error = "Bylo zadano spatne heslo nebo jmeno";
        }
    }

?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přihlášení | IoT Connect</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body class="auth-page">

    <div class="auth-container">
        <div class="auth-header">
            <span class="logo">IoTConnect</span>
        </div>
        
        <div class="auth-card">
            <h2>Přihlášení</h2>
            <form method="POST">
                <div class="input-group">
                    <label>Přezdívka</label>
                    <input type="text" name="username" placeholder="Novis" required>
                </div>
                <div class="input-group">
                    <label>Heslo</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit" name="prihlasit" class="btn btn-full">Vstoupit do systému</button>
            </form>
            <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
            <p class="switch-text">Nemáte účet? <a href="register.php" class="accent-link">Zaregistrujte se</a></p>
        </div>
        <a href="index.php" class="back-link">← Zpět na úvod</a>
    </div>

</body>
</html>