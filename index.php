<?php 

session_start();

//$_SESSION["pridano"] = "Bylo přidáno jídlo";

require_once('Db.php');
Db::connect('127.0.0.1', 'books', 'root', '');

$jidlo = Db::queryAll('SELECT * FROM jidlo');


if (isset($_POST['delete'])) {
  Db::queryOne('DELETE FROM jidlo WHERE id=?', $_POST['id']);

  $_SESSION["pridano"] = "Bylo odstraněno jídlo";


  header("Location: /htdocs/session/index.php");
  die();
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

    .alert {
      padding: 15px;
      position: fixed;
      bottom: 15px;
      right: 15px;
      border-radius: 10px;
      background: green;
      color: white;
      font-size: 16px;
      box-sizing: border-box;
    }
</style>

<table>
  <tr>
    <th>Name</th>
    <th>Type</th>
    <th>Count</th>
    <th></th>
  </tr>
  <?php foreach($jidlo as $j): ?>
    <tr>
        <td><?= $j['name']?></td>
        <td><?= $j['type']?></td>
        <td><?= $j['count']?></td>
        <td>
          <form  method="POST">
            <input type="hidden" name="id" value="<?= $j['id'] ?>">
            <input type="submit" name="delete" value="delete">
          </form>
        </td>
    </tr>
  <?php endforeach; ?>
  
</table>

<form method="POST" action="add.php">
	<input type="submit" value="pridat" name="vytvorit">
</form>

<?php if (isset($_SESSION['pridano'])): ?>
    <div class="alert">
        <?= $_SESSION['pridano'] ?>
        <?php unset($_SESSION['pridano']); ?>
    </div>

    <script>

        const alert = document.querySelector('.alert');

        setTimeout(() => {
          
          if(alert){
            if (getComputedStyle(alert).opacity > 0){
              let animation = setInterval(() => {
                alert.style.opacity = getComputedStyle(alert).opacity - 0.01;
              }, 1);
            }
          }
          else{
            clearInterval(animation);
          }
        }, 3000);

    </script>

<?php endif; ?>

  

</body>
</html>