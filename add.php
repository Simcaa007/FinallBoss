<?php 
session_start();
require_once('Db.php');
Db::connect('127.0.0.1', 'books', 'root', '');

$jidlo = Db::queryAll('SELECT * FROM databaze');

$user_id = $_SESSION['user_id'];

if(isset($_POST["pridat"])){
    Db::insert("databaze", [
        "name" => $_POST['name'],
        "author" => $_POST['author'],
        "genre" => $_POST['genre'],
        "year_of_publication" => $_POST['year_of_publication'],
        "info" => $_POST['info'],
        "user_id" => $user_id
    ]);

    echo "kniha byla pridana";

    header("Location: knihy.php");
    die();
}

if (isset($_GET['book'])) {
    $book = Db::queryOne('SELECT * FROM databaze WHERE id = ?',  $_GET['book']);

    if (!$book || $book['user_id'] != $_SESSION['user_id']) {
        header("Location: knihy.php");
        $_SESSION['pridano'] = 'Kniha neexistuje';

        die();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session</title>
</head>
<body>

<style>
    table {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
    }

    td, th {
    border: 1px solid #ddd;
    padding: 8px;
    }

    tr:nth-child(even){background-color: #f2f2f2;}

    tr:hover {background-color: #ddd;}

    th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #04AA6D;
    color: white;
    }
</style>

<form method="POST">

    <label>name</label>
    <input type="text" name="name" value="<?= isset($book) ? $book['name'] : '' ?>">
    <label>author</label>
    <input type="text" name="author">
    <label>genre</label>
    <input type="text" name="genre">
    <label>year_of_publication</label>
    <input type="number" name="year_of_publication">
    <label>info</label>
    <input type="text" name="info">
    <input type="submit" name="pridat" value="Přidat do databáze">
</form>

</body>
</html>