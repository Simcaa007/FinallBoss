<?php 

session_start();

//$_SESSION["pridano"] = "Bylo přidáno jídlo";

require_once('Db.php');
Db::connect('127.0.0.1', 'books', 'root', '');

//$books = Db::queryAll('SELECT * FROM databaze');


$books = Db::queryAll('SELECT * FROM databaze WHERE id= ?',  $_SESSION['user_id']);

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

<table>
  <tr>
    <th>Name</th>
    <th>author</th>
    <th>genre</th>
    <th>year_of_publication</th>
    <th>info</th>
    <th>user_id</th>
    <th>upravit</th>
  </tr>
  <?php foreach($books as $book): ?>
    <tr>
        <td><?= $book['name']?></td>
        <td><?= $book['author']?></td>
        <td><?= $book['genre']?></td>
        <td><?= $book['year_of_publication']?></td>
        <td><?= $book['info']?></td>
        <td><?= $book['user_id']?></td>
        <td><form method="POST" action="add.php?book=<?= $book['id'] ?>">
            <input type="hidden" name="id" value="<?= $book['id'] ?>">
            <input type="submit" value="upravit">
        </form></td>
    </tr>
  <?php endforeach; ?>
  
</table>

<form method="POST">
    <input type="submit" value="ohlasit" name="odhlasit">
</form>

<a href="add.php"> Pridat knihu </a>
    
</body>
</html>