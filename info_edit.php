<?php
session_start();

//idが空なら未ログイン状態のためindexへリダイレクト
if (empty($_SESSION["id"])){
    header("Location:/LibraryManagement/index.php");
    exit;
}

include_once("./database/connect.php");
date_default_timezone_set('Asia/Tokyo');

if (!isset($_SESSION["csrf_token"])){
    $_SESSION["csrf_token"] = bin2hex(random_bytes(16));
}

$sql = "SELECT user_name, email FROM users WHERE id = :id";
$statement = $pdo->prepare($sql);

$statement->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);
$statement->execute();
//現在のdbに入っているレコードを取得
$stmt = $statement->fetch(PDO::FETCH_ASSOC);

$escaped["name"] = htmlspecialchars($stmt["user_name"],ENT_QUOTES,"UTF-8");
$escaped["email"] = htmlspecialchars($stmt["email"],ENT_QUOTES,"UTF-8");


if (isset($_POST["submitbutton"])){

    //リクエストにトークンが付与されていない場合
    if(!isset($_POST["csrf_token"])){
        echo "不正なリクエスト";
        exit;
    }

    //csrfトークン判定
    if (!hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"])){
        //トークンが一致しない場合
        echo "不正なリクエスト";
        exit;
    }

    $updated_date = date("Y-m-d H:i:s");

    //パス更新の有無を判定
    if(empty($_POST["pass"])){
        //パス変更なし
        $sql = "UPDATE `users` SET `user_name` = :username, `email` = :email, `updated` = :updated WHERE `users`.`id` = :id;";
        $statement = $pdo->prepare($sql);

        $pass_hash = password_hash($_POST["pass"], PASSWORD_DEFAULT);

        $statement->bindParam(":username", $_POST["username"], PDO::PARAM_STR);
        $statement->bindParam(":email", $_POST["email"], PDO::PARAM_STR);
        $statement->bindParam(":updated", $updated_date, PDO::PARAM_STR);
        $statement->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);
        session_regenerate_id(true);
    }else{
        //パス変更あり
        $sql = "UPDATE `users` SET `user_name` = :username, `password` = :pass, `email` = :email, `updated` = :updated WHERE `users`.`id` = :id;";
        $statement = $pdo->prepare($sql);

        $pass_hash = password_hash($_POST["pass"], PASSWORD_DEFAULT);

        $statement->bindParam(":username", $_POST["username"], PDO::PARAM_STR);
        $statement->bindParam(":pass", $pass_hash, PDO::PARAM_STR);
        $statement->bindParam(":email", $_POST["email"], PDO::PARAM_STR);
        $statement->bindParam(":updated", $updated_date, PDO::PARAM_STR);
        $statement->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);
        session_regenerate_id(true);
    }
    
    try{
        $statement->execute();
        header("Location: " . $_SERVER['PHP_SELF'], true, 303);
        exit;
    }catch(PDOException){
        echo "更新失敗";
    }
}

//セッション切れならindexへリダイレクト
if(!isset($_SESSION["id"])){
        header("Location:/LibraryManagement/index.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員情報変更</title>
</head>
<body>
    <h1>会員情報変更ページ</h1>
    <a href="mypage.php">マイページ</a>

    <form method="POST">
        <label for="username">ユーザー名</label>
        <input type="text" name="username" value="<?php echo $escaped["name"]?>" required>
        <br>

        <label for="email">メールアドレス</label>
        <input type="email" name="email" value="<?php echo $escaped["email"]?>" required>
        <br>

        <label for="pass">パスワード</label>
        <input type="password" name="pass">
        <br>

        <input type="submit" value="更新" name="submitbutton">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]; ?>">
    </form>
</body>
</html>