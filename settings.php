<?php
session_start();
require_once('Db.php');
Db::connect('127.0.0.1', 'finalboss', 'root', '');

if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

if (isset($_POST['update'])) {

    if (!empty($newPassword)) {

        Db::update('users', [
            'username' =>$_POST['username'],
            'password' => $_POST['password'],
            'location' => $_POST['location']
        ], 'WHERE username = ?', $_POST['username']);
    
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['location'] = $_POST['location'];
    }
    else{
        Db::update('users', [
            'username' =>$_POST['username'],
            'location' => $_POST['location']
        ], 'WHERE username = ?', $_POST['username']);
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['location'] = $_POST['location'];
    }

    header('Location: dashboard.php');
    die();
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Nastavení | IoT Connect</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body class="auth-page">

    <div class="auth-container" style="max-width: 500px;">
        <div class="auth-header">
            <a href="dashboard.php" class="logo">IoT<span>Connect</span></a>
        </div>
        
        <div class="auth-card">
            <h2>Nastavení účtu</h2>
            <?php echo $message; ?>

            <form method="POST">
                <div class="input-group">
                    <label>Uživatelské jméno</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>
                </div>

                <div class="input-group">
                    <label>Lokalita (Město pro počasí)</label>
                    <input type="text" name="location" value="<?php echo htmlspecialchars($_SESSION['location']); ?>" required>
                </div>

                <div class="input-group">
                    <label>Nové heslo (nechte prázdné, pokud nechcete měnit)</label>
                    <input type="password" name="password" placeholder="********">
                </div>

                <button type="submit" name="update" class="btn btn-full">Uložit změny</button>
            </form>
            
            <a href="dashboard.php" class="back-link">← Zpět na Dashboard</a>
        </div>
    </div>

</body>
</html>