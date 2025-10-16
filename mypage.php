<?php
//会員情報更新
//ログアウトページ
session_start();

include_once("./database/connect.php");

$sql = "SELECT user_name, email FROM users WHERE `id` = :id";

$statement = $pdo->prepare($sql);
$statement->bindParam(":id", $_SESSION["id"], PDO::PARAM_STR);
$statement->execute();
$stmt = $statement->fetch();

$escaped["name"] = htmlspecialchars($stmt["user_name"],ENT_QUOTES,"UTF-8");
$escaped["email"] = htmlspecialchars($stmt["email"],ENT_QUOTES,"UTF-8");
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ</title>
</head>
<body>
    <h1>マイページ</h1>
    <h2>
        <?php
            if(isset($_SESSION["id"])){
                echo "ID:" . $_SESSION["id"]."<br>";
                echo "NAME:" . $escaped["name"]."<br>";
                echo "EMAIL:" . $escaped["email"]; 
            } else{
                header("Location:/LibraryManagement/index.php");
                exit;
            }
        ?>
    </h2>
    <a href="info_edit.php">会員情報変更</a>
    <br>
    <a href="logout.php">ログアウト</a>
</body>
</html>